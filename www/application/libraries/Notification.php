<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'services/Twilio.php');
date_default_timezone_set('UTC');

class Notification{
    private $ci = null;
    private $notification_id = null;
    private $gameid = null;
    private $message = null;
    private $user_id_list = null;
    private $seconds_since_last_run = null;
    private $rate_limit_seconds = 0;

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
        $this->seconds_since_last_run = $this->getSecondsSinceLastRun();
      }catch(NoNotificationException $e){
        $this->message = null;
        //Make sure message is null, notification is dead.
      }
    }

    public function getSecondsSinceLastRun(){
      $last_run = strtotime($this->ci->Notification_model->getLastRunDate($this->notification_id));
      return time() - $last_run;
    }
    //Final message creation methods. If there are any exceptions return null;
    //Never use anything out of $data_obj that isn't an id.
    private function teammate_tagged($data_obj){
      try{
        //object hell.
        $tag          = $data_obj->tag;
        $team         = $this->ci->teamcreator->getTeamByTeamID($tag->getTaggee()->getTeamID());

        $user_id_list = $this->purgeUnsubscribedUsers($team->getTeamMemberUserIDs());
        
        $tagger_player = $tag->getTagger();
        if($tagger_player->getPublicStatus() == "human") {
          $tagger_name = "OriginalZombie";
        }else{
          $tagger_name = $tagger_player->getUser()->getUsername();
        }
        $taggee_name  = $tag->getTaggee()->getUser()->getUsername();
        $teamname     = $team->getData("name");

        return array($user_id_list, "Team \"$teamname\" alert: your teammate $taggee_name has been turned into a zombie by $tagger_name!");
      }catch (Exception $e){
        return array(null, null);
      }
    }

    private function daily_update($data_obj){
      $this->rate_limit_seconds = 3600; //a big window, other checks should prevent this anyway.
      try{
        $game = $this->ci->gamecreator->getGameByGameID($this->gameid);
        $user_id_list = $this->ci->Player_model->getActivePlayerUserIDsByGameID($game->getGameID());
        $user_id_list = $this->purgeUnsubscribedUsers($user_id_list);

        $date = new DateTime($data_obj->game_date_id);
        $print_date = $date->format('m/d');

        list($human_count, $zombie_count, $starved_zombies) = $game->playerStatusCounts();
        
        $day_kills = $game->daykills($date->format('Ymd'));
        $days_remaining = $game->daysRemaining();
        $day_text = $days_remaining == 1 ? "day" : "days";
        //"Nightly update -- Humans left: $human_count, Total zombies: $zombie_count, Check out the Zombie family tree for a breakdown. http://bit.ly/T1K5jY"
        //"Nightly update -- Zombie casualties today: $zombie_count, Days remaining $days_remaining, Text 'stats' to check the zombie count at any time"//
    
        return array($user_id_list, "Nightly update -- Humans left: $human_count, Total zombies: $zombie_count, Check out the Zombie family tree for a breakdown. http://bit.ly/T1K5jY");
      }catch (Exception $e){
        return array(null, null);
      }
    }


    private function broadcast($data_obj){
      try{
        $this->rate_limit_seconds = 450;
        $game = $this->ci->gamecreator->getGameByGameID($this->gameid);

        $user_id_list = array();
        if($data_obj->type == "all"){
            $user_id_list = $this->ci->Player_model->getActivePlayerUserIDsByGameID($game->getGameID());
            $prefix = "Humans & Zombies:";
        }elseif($data_obj->type == "humans"){
            $user_id_list = $game->getHumanUserIDs();
            $prefix = "Humans:";
        }elseif($data_obj->type == "zombies"){
            $user_id_list = $game->getZombieUserIDs();
            $prefix = "Zombies:";
        }

        $user_id_list = $this->purgeUnsubscribedUsers($user_id_list);

        $response = $prefix . ' " ' . $data_obj->message . ' " ';
        return array($user_id_list, $response);
      }catch (Exception $e){
        echo $e;
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
      $diff = $this->seconds_since_last_run - $this->rate_limit_seconds;
      if($diff <= 0){
        return "Sending messages too quickly, please try again in " . round($diff/-60,2) . " minutes";
      }

      if($this->message && $this->user_id_list){
        $message = $this->message;
        if(strlen($message)>159){
          return "message is too long, please try a shorter message";
        }
        $message = substr($message,0,160); //precaution, don't send anything longer than 160 characters. 
        info('sms_message: send called - ' . " message: " . $message);
        foreach($this->user_id_list as $recipient_user_id){
          $recipient_number = $this->ci->User_model->getUserData($recipient_user_id, "phone");
          //09-04-2012 Leaving this commented out until the final game deploy. Just for safety.
          debug('sms_message: created - recipient_number: ' . $recipient_number . " message: " . $message);
          $this->client->account->sms_messages->create(
            $this->TwilioNumber,
            $recipient_number,
            $message
          );
        }
      }
      $this->ci->Notification_model->updateLastRunTime($this->notification_id);
      return "notification sent";
    }
}