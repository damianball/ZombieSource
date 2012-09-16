<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('UTC');

class Achievement{
    private $ci = null;

    public function __construct(){
        $this->ci =& get_instance();

        $this->ci->load->model('Achievement_model', '', true);
        $this->ci->load->model('Player_team_model', '', true);

    }

    public function register_kill_achievements($tag){
        $taggerid = $tag->getTaggerID();
        // test for killstreak achievements
        $kill_info = $this->ci->Achievement_model->getLargestKillStreakInXHours($tag, 3);
        $levels = array( // num_kills => achievement_id
            6 => 5,
            5 => 4,
            4 => 3,
            3 => 2,
            2 => 1
        );
        foreach($levels as $kills => $achievementid){
            if($kill_info->count >= $kills){
                $this->addAchievement($taggerid, $achievementid, $kill_info->latest);
                break; // presumably the other cases have already happened
            }
        }

        // test for time independent kill achievements
        $levels = array( // num_kills => achievement_id
            20 => 12,
            15 => 11,
            10 => 10,
            5  => 9,
            1  => 6
        );
        foreach($levels as $kills => $achievementid){
            if($kill_info->count >= $kills){
                $this->addAchievement($taggerid, $achievementid, $kill_info->latest);
                break; // presumably the other cases have already happened
            }
        }

        // check for True Friend
        if ($kill_info->count == 1){ // must be first kill
            $tagger_team = $this->ci->Player_team_model->getLastTeam($taggerid);
            $taggee_team = $this->ci->Player_team_model->getLastTeam($tag->getTaggeeID());
            if($tagger_team == $taggee_team){ // former teammates
                // this totally ignores corner cases, like if the tagger quit the team and didn't join another
                $this->addAchievement($taggerid, 8, $kill_info->latest);
            }
        }

    }

    private function addAchievement($playerid, $achievementid, $date){
        if(!$this->ci->Achievement_model->checkAchievementExistsByPlayerIDAchievementID($playerid, $achievementid)){
            // achievements can only be earned once per game
            $this->ci->Achievement_model->addAchievement($playerid, $achievementid, $date);
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
