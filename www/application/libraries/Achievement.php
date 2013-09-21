<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('UTC');

class Achievement{
    private $ci = null;
    private $achievements = array(
        'Double Kill' => 1,
        'MultiKill' => 2,
        'Flesh Feast' => 3,
        'Rampage' => 4,
        'Zombocalypse' => 5,
        'BrainLust' => 8,
        'Disease Vector' => 9,
        'PlagueBearer' => 10,
        'Harbinger of Undeath' => 11,
        'Überzombie' => 12,
        'A Striking Original' => 15,
        'True Friend' => 16,
        'Brains are Better' => 17,
        'Team Survivor' => 18,
        'Last One Standing' => 19,
        'Survivor' => 20,
        'Girl Badass' => 21,
        'Most Kills' => 22,
        'MVP' => 23
    );

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
        // this doesn't work for Brains are Better!
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
            6 => $this->achievements['Zombocalypse'],
            5 => $this->achievements['Rampage'],
            4 => $this->achievements['Flesh Feast'],
            3 => $this->achievements['MultiKill'],
            2 => $this->achievements['Double Kill'],
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
            20 => $this->achievements['Überzombie'],
            15 => $this->achievements['Harbinger of Undeath'],
            10 => $this->achievements['PlagueBearer'],
            5  => $this->achievements['Disease Vector'],
            1  => $this->achievements['BrainLust'],
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
                    $achievementid = $this->achievements['True Friend']; // True Friend
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
            $achievementid = $this->achievements['Brains are Better']; // Brains are Better
            $success = $this->addAchievement($taggeeid, $achievementid, $kill_info->latest);
            if($success){
                array_push($new_ach, array('achievementid' => $achievementid, 'playerid' => $taggeeid, 'date' => $kill_info->latest));
            }
        }
        return $new_ach; // array of new achievements
    }

    public function addAchievement($playerid, $achievementid, $date){
        if(!$this->ci->Achievement_model->checkAchievementExistsByPlayerIDAchievementID($playerid, $achievementid)){
            // achievements can only be earned once per game
            return $this->ci->Achievement_model->addAchievement($playerid, $achievementid, $date);
        }
        return false;
    }

    public function invalidateAchievement($playerid, $achievementid){
        if($this->ci->Achievement_model->checkAchievementExistsByPlayerIDAchievementID($playerid, $achievementid)){
            // must exist to invalidate
            return $this->ci->Achievement_model->invalidateAchievement($playerid, $achievementid);
        }
        return false;
    }
}
