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

}
