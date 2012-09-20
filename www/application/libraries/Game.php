<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game{
    private $teamid = null;
    private $ci = null;

    public function __construct($gameid)
    {
        $this->ci =& get_instance();
        $this->ci->load->model('Game_model', '', true);
        $this->ci->load->model('Tag_model', '', true);
        $this->ci->load->model('User_model', '', true);
        $this->ci->load->model('Player_model','',TRUE);
        $this->ci->load->library('PlayerCreator');
        $this->ci->load->helper('game_helper');
        $this->ci->load->helper('tweet_helper');
        $this->ci->load->library('FeedCreator');

        if($gameid){
            $this->gameid = $gameid;
        } else {
            throw new ClassCreationException("gameid cannot be null.");
        }
    }

    public function register_kill($zombie, $human_code, $claimed_tag_time_offset = null, $players_to_feed = null) {
        $error = null;
        $human_code = strtoupper($human_code);
        if(playerExistsWithHumanCodeByGameID($human_code, $this->getGameID())){
            $playerid = getPlayerIDByHumanCodeGameID($human_code, $this->getGameID());

            // is the player an active human?
            $player = $this->ci->playercreator->getPlayerByPlayerID($playerid);
            if(is_a($player, 'Human') && $player->canParticipate()){
                $human = $player;
                    $this->ci->load->library('TagCreator');
                    $this->ci->load->library('ActionHandler');

                    $dateclaimed = null;
                    // generate time claime\d offset
                    $maxseconds = 14400;
                    $minseconds = 0;
                    if($claimed_tag_time_offset && $claimed_tag_time_offset != '' && $claimed_tag_time_offset >= $minseconds && $claimed_tag_time_offset <= $maxseconds){
                        $dateclaimed = gmdate("Y-m-d H:i:s", time() - ($claimed_tag_time_offset));
                    }

                    $tag = $this->ci->tagcreator->getNewTag($human, $zombie, $dateclaimed, null, null);
                    $this->ci->actionhandler->tagAction($tag,$this->getGameID());

                    $this->ci->load->helper('tree_helper');
                    if($tag){
                        // remove human from any teams
                        // was human on team?
                        if($human->isMemberOfATeam()){
                            // tweet if this destroys the team
                            $teamid = $human->getTeamID();
                            $team = $this->ci->teamcreator->getTeamByTeamID($teamid);
                            if($team->getTeamSize() == 1){ // last player on team
                                tweet_team_destroyed($team);
                            }
                            $human->leaveCurrentTeam();
                        }

                        // feed the tagger
                        $feed = $this->ci->feedcreator->getNewFeed($zombie, $tag, $dateclaimed, null);
                        
                        if($players_to_feed){
                            $this->feed_friends($players_to_feed, $tag, $dateclaimed);
                        }
                        // else{
                        //     $this->feed_friends($this->getMostStarvingZombies(1), $tag, $dateclaimed);
                        // }
                    }
                    $error = "The kill was successfully recorded";

            } else {
                // PLAYER IS NOT A HUMAN OR ACTIVE
                $error = 'Cannot tag player with human code: '.$human_code;
            }
        } else {
            // HUMAN CODE DOES NOT EXIST ... NOW WHAT?
            $error = 'Human code does not exist: '.$human_code;
        }
        return $error;
    } 

    public function feed_friends($players, $tag, $dateclaimed =  null){
        foreach($players as $friend){
            if(is_a($friend, 'Zombie') && $friend->canParticipate()){
                $this->ci->feedcreator->getNewFeed($friend, $tag, $dateclaimed, null);
            }
        }
    }


    //Game Statistics

    public function playerStatusCounts(){
        $zombie_count = 0;
        $human_count = 0;
        $starved_zombie_count = 0;

        $players = getViewablePlayers($this->gameid);
        foreach($players as $player){
                if(strpos($player->getPublicStatus(),'zombie') !== FALSE){
                    if($player->isStarved()){
                      $starved_zombie_count += 1;;
                    }else{
                      $zombie_count += 1;
                    }
                }else {
                    $human_count += 1;
                }

        }

        return array($human_count, $zombie_count, $starved_zombie_count);
    }

    public function dayKills($date_id){
        return $this->ci->Tag_model->numTagsForDate($date_id, $this->gameid);
    }

    public function daysRemaining(){
        $end_date =  $this->ci->Game_model->getEndTime($this->gameid);
        $end_epoch = strtotime($end_date);
        $now = time();
        $diff = $end_epoch - $now;
        $diff_days = $diff/86400;
        return round($diff_days);
    }

    //Game Attributes


    public function UTCoffset(){
        return $this->ci->Game_model->getUTCoffset($this->gameid);
    }

    public function getGameID(){
        return $this->gameid;
    }

    public function getStateID(){
        return $this->ci->Game_model->getStateID($this->gameid);
    }

    public function registrationIsOpen(){
        return $this->ci->Game_model->getRegistrationState($this->gameid);
    }

    public function slug(){
        return $this->ci->Game_model->getGameSlugByGameID($this->gameid);
    }

    public function photoURL(){
        return $this->ci->Game_model->getPhotoURL($this->gameid);
    }

    public function description(){
        return $this->ci->Game_model->getDescription($this->gameid);
    }

    public function name(){
        return $this->ci->Game_model->getGameName($this->gameid);
    }

    //TODO actually check.
    public function registrationOpen(){
        return true;
    }

    public function isClosedGame(){
        return ($this->getStateID() == 3);
    }

    public function getEndTime(){
        return $this->ci->Game_model->getEndTime($this->gameid);
    }

    public function getZombieUserIDs(){
        $playerids = $this->ci->Player_model->getActivePlayerIDsByGameID($this->getGameID());
        $userids = array();
        foreach($playerids as $playerid){
            $player = $this->ci->playercreator->getPlayerByPlayerID($playerid);
            if($player->isActive() && $player->getStatus() == 'zombie'){
                $userids[]= $this->ci->Player_model->getUserIDbyPlayerID($playerid);
            }
        }
        return $userids;
    }

    public function getZombies(){
        $playerids = $this->ci->Player_model->getActivePlayerIDsByGameID($this->getGameID());
        $zombies = array();
        foreach($playerids as $playerid){
            $player = $this->ci->playercreator->getPlayerByPlayerID($playerid);
            if($player->isActive() && $player->getStatus() == 'zombie'){
                $zombies[]= $player;
            }
        }
        return $zombies;
    }

    // public function getMostStarvingZombies($limit){
    //     $zombies = $this->getZombies();
    //     @usort($zombies, function($a, $b) {
    //         return $a->secondsSinceLastFeed() <$b->secondsSinceLastFeed();
    //     });
    //     return array_slice($zombies, 0, $limit);
    // }
    
    public function getHumanUserIDs(){
        $playerids = $this->ci->Player_model->getActivePlayerIDsByGameID($this->getGameID());
        $userids = array();
        foreach($playerids as $playerid){
            $player = $this->ci->playercreator->getPlayerByPlayerID($playerid);
            if($player->getPublicStatus() == 'human'){
                $userids[]= $this->ci->Player_model->getUserIDbyPlayerID($playerid);
            }
        }
        return $userids;
    }

    public function getHumanEmails(){
        $userids = $this->getHumanUserIDs();
        $emails = array();
        foreach($userids as $userid){
            $emails[] = $this->ci->User_model->getEmailByUserID($userid);
        }
        return $emails;
    }


    public function getZombieEmails(){
        $userids = $this->getZombieUserIDs();
        $emails = array();
        foreach($userids as $userid){
            $emails[] = $this->ci->User_model->getEmailByUserID($userid);
        }
        return $emails;
    }

}
