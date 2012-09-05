<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'services/Twilio.php');
date_default_timezone_set('UTC');

class Notification{
    private $ci = null;
    private $notification_id = null;
    private $gameid = null;
    private $message = null;
    private $user_id_list = null;

    public function __construct($gameid, $data_obj){
        $this->ci =& get_instance();
        $this->gameid = $gameid;
    
        $AccountSid = $this->ci->config->item('twilio_account_sid');
        $AuthToken = $this->ci->config->item('twilio_auth_token');
        $this->TwilioNumber = $this->ci->config->item('twilio_number');
        $this->client = new Services_Twilio($AccountSid, $AuthToken);

        $this->ci->load->library('GameCreator', null);
        $this->ci->load->library('TagCreator', null);
        $this->ci->load->library('TeamCreator', null);
        $this->ci->load->model('User_model', '', true);
        $this->ci->load->model('Player_model', '', true);
        $this->ci->load->model('Notification_model', '', true);

        $this->initialize($data_obj);
    }

    //Notification could be reinitialized
    public function initialize($data_obj){
      try{
        $this->notification_id = $this->ci->Notification_model->getNotificationIDByName($data_obj->notification_name);
        list($this->user_id_list, $this->message) = $this->{$data_obj->notification_name}($data_obj);
      }catch(NoNotificationException $e){
        $this->message = null;
        //Make sure message is null, notification is dead.
      }
    }

    //Final message creation methods. If there are any exceptions return null;
    //Never use anything out of $data_obj that isn't an id.
    private function teammate_tagged($data_obj){
      try{
        //object hell.
        $tag          = $this->ci->tagcreator->getTagbyTagID($data_obj->tag_id);
        $team         = $this->ci->teamcreator->getTeamByTeamID($tag->getTaggee()->getTeamID());

        $user_id_list = $this->purgeUnsubscribedUsers($team->getTeamMemberUserIDs());
        $tagger_name  = $tag->getTagger()->getUser()->getUsername();
        $taggee_name  = $tag->getTaggee()->getUser()->getUsername();
        $teamname     = $team->getData("name");

        return array($user_id_list, "Team \"$teamname\" alert: your teammate $taggee_name has been turned into a zombie by $tagger_name!");
      }catch (Exception $e){
        return array(null, null);
      }
    }

    private function daily_update($data_obj){
      try{
        $game = $this->ci->gamecreator->getGameByGameID($this->gameid);
        $user_id_list = $this->ci->Player_model->getActivePlayerUserIDsByGameID($game->getGameID());
        $user_id_list = $this->purgeUnsubscribedUsers($user_id_list);

        $date = new DateTime($data_obj->game_date_id);
        $print_date = $date->format('m/d');

        $test_game = $this->ci->gamecreator->getGameByGameID('9a051bbc-3ebc-11e1-b778-000c295b88cf');

        list($human_count, $zombie_count, $starved_zombies) = $game->playerStatusCounts();
        
        $day_kills = $game->daykills($date->format('Ymd'));
        $days_remaining = $game->daysRemaining();
        $day_text = $days_remaining == 1 ? "day" : "days";
    
        return array($user_id_list, "Total zombie count: $zombie_count. $day_kills kills today. $days_remaining $day_text remain.");
      }catch (Exception $e){
        return array(null, null);
      }
    }

    private function purgeUnsubscribedUsers($user_id_list){
      $new_list = array();
      $group_id = $this->ci->Notification_model->groupIDfromNotificationID($this->notification_id);
      foreach($user_id_list as $user_id){
        $alerts_paused = $this->ci->User_model->userSubscribedToGroupByID(4, $user_id); //4 = pause_updates
        if($this->ci->User_model->userSubscribedToGroupByID($group_id, $user_id) && !$alerts_paused){
          $new_list[] = $user_id;
        }
      }
      return $new_list;
    }

    public function send(){
      if($this->message && $this->user_id_list){
        foreach($this->user_id_list as $recipient_user_id){
          $recipient_number = $this->ci->User_model->getUserData($recipient_user_id, "phone");
          $message = $this->message;
          $message = substr($message,0,160); //precaution, don't send anything longer than 160 characters.

          //09-04-2012 Leaving this commented out until the final game deploy. Just for safety.        
          // $this->client->account->sms_messages->create(
          //   $this->TwilioNumber,
          //   $recipient_number,
          //   $message
          // );
        }
      }
    }

}