<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'services/Twilio.php');

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

    //only for testing
    public function user_id_list(){
      return $this->user_id_list;
    }

    public function message(){
      return $this->message;
    }


    //Final message creation methods. If there are any exceptions return null;
    //Never use anything out of $data_obj that isn't an id.
    private function teammate_tagged($data_obj){
      try{
        //object hell.
        $tag          = $this->ci->tagcreator->getTagbyTagID($data_obj->tag_id);
        $taggee       = $tag->getTaggee()->getUser();
        $team         = $this->teamcreator->getTeamByTeamID($taggee->getTeamID());

        $user_id_list = $this->purgeUnsubscribedUsers($team->getTeamMemberUserIDs());
        $tagger_name  = $tag->getTagger()->getUser()->getUsername();
        $taggee_name  = $taggee->getUsername();
        $teamname     = $team->getData("name");

        return array($user_id_list, "Team \'$teamname\'' alert, your teammate $taggee_name has been turned into a zombie by $tagger_name");
      }catch (Exception $e){
        return array(null, null);
      }
    }

    private function daily_update($data_obj){
      try{
        $game = $this->ci->gamecreator->getGameByGameID($this->gameid);
        $user_id_list = $this->ci->Player_model->getActivePlayerUserIDsByGameID($game->getGameID());
        $user_id_list = $this->purgeUnsubscribedUsers($user_id_list);

        $date = new DateTime($data_obj->date_id);
        $date = $date->format('m/d');
        list($human_count, $zombie_count, $starved_zombies) = $game->playerStatusCounts();
        $day_kills = $game->daykills();
    
        return array($user_id_list, "Update for $date: Total zombie count: $zombie_count. $day_kills kills today");
      }catch (Exception $e){
        return array(null, null);
      }
    }

    private function purgeUnsubscribedUsers($user_id_list){
      $new_list = array();
      $group_id = $this->ci->Notification_model->groupIDfromNotificationID($this->notification_id);
      foreach($user_id_list as $user_id){
        if($this->ci->User_model->userSubscribedToGroup($group_id, $user_id)){
          $new_list[] = $user_id;
        }
      }
      return $new_list;
    }

    public function send(){
      if($this->message && $this->user_id_list){
        foreach($this->user_id_list() as $recipient_user_id){
          $recipient_number = $this->ci->User_model->getUserData($recipient_user_id, "phone");
          $message = $this->message;
          $message = substr($message,0,160); //precaution, don't send anything longer than 160 characters.
          
          echo "userid: ". $recipient_user_id. "<br>";
          echo "Destination: ".$recipient_number." --   Message: ".$this->message;
          echo "<br> <br>";

          // $client->account->sms_messages->create(
          //   $this->TwilioNumber,
          //   $recipient_number,
          //   $message
          // );
        }
      }
    }

}