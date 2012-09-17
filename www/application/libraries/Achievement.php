<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('UTC');

class Achievement{
    private $ci = null;

    public function __construct(){
        $this->ci =& get_instance();

        $this->ci->load->model('Achievement_model', '', true);
        $this->ci->load->model('Player_team_model', '', true);
        $this->ci->load->model('Tag_model', '', true);
        $this->ci->load->library('TagCreator');
        $this->ci->load->library('PlayerCreator');

    }

    // recalculate achievements (does not delete, only adds)
    public function backgenerate(){
        // this cannot work for Brains are Better!
        $tags_raw = $this->ci->Tag_model->getTagsInOrder();
        $count = 0;
        foreach($tags_raw as $tag){
            $new = $this->registerKillAchievements($tag['id'], FALSE);
            $count += count($new);
        }
        return $count;
    }

    public function registerKillAchievements($tagid, $break_early=TRUE){
        $tag = $this->ci->tagcreator->getTagByTagID($tagid);
        $taggerid = $tag->getTaggerID();
        $new_ach = array();
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
                $success = $this->addAchievement($taggerid, $achievementid, $kill_info->latest);
                if($success){
                    array_push($new_ach, array('achievementid' => $achievementid, 'playerid' => $taggerid, 'date' => $kill_info->latest));
                }
                if($break_early) break; // presumably the other cases have already happened
            }
        }

        $kill_info = $this->ci->Achievement_model->getKillCountByPlayerID($taggerid);

        // test for time independent kill achievements
        $levels = array( // num_kills => achievement_id
            20 => 12,
            15 => 11,
            10 => 10,
            5  => 9,
            1  => 8
        );
        foreach($levels as $kills => $achievementid){
            if($kill_info->count >= $kills){
                $success = $this->addAchievement($taggerid, $achievementid, $kill_info->latest);
                if($success){
                    array_push($new_ach, array('achievementid' => $achievementid, 'playerid' => $taggerid, 'date' => $kill_info->latest));
                }
                if($break_early) break; // presumably the other cases have already happened
            }
        }

        // check for True Friend
        if ($kill_info->count == 1){ // must be first kill
            try{
                $tagger_team = $this->ci->Player_team_model->getLastTeam($taggerid);
                $taggee_team = $this->ci->Player_team_model->getLastTeam($tag->getTaggeeID());
                if($tagger_team == $taggee_team){ // former teammates
                    // this totally ignores corner cases, like if the tagger quit the team and didn't join another
                    $achievementid = 16; // True Friend
                    $success = $this->addAchievement($taggerid, $achievementid, $kill_info->latest);
                    if($success){
                        array_push($new_ach, array('achievementid' => $achievementid, 'playerid' => $taggerid, 'date' => $kill_info->latest));
                    }
                }
            } catch (PlayerNotMemberOfAnyTeamException $e){
                // no fear, and no achievement
            }
        }

        // check for Brains are Better
        $gameid = $this->ci->playercreator->getPlayerByPlayerID($taggerid)->getGameID();
        if($this->ci->Tag_model->countTagsByGameID($gameid) == 1){
            $taggeeid = $tag->getTaggeeID();
            $achievementid = 17; // Brains are Better
            $success = $this->addAchievement($taggeeid, $achievementid, $kill_info->latest);
            if($success){
                array_push($new_ach, array('achievementid' => $achievementid, 'playerid' => $taggeeid, 'date' => $kill_info->latest));
            }
        }
        return $new_ach; // array of new achievements
    }

    private function addAchievement($playerid, $achievementid, $date){
        if(!$this->ci->Achievement_model->checkAchievementExistsByPlayerIDAchievementID($playerid, $achievementid)){
            // achievements can only be earned once per game
            return $this->ci->Achievement_model->addAchievement($playerid, $achievementid, $date);
        }
        return false;
    }

}
