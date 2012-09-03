<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game{
    private $teamid = null;
    private $ci = null;

    public function __construct($gameid)
    {
        $this->ci =& get_instance();
        $this->ci->load->model('Game_model', '', true);
        $this->ci->load->model('Stats_model', '', true);
        $this->ci->load->model('Tag_model', '', true);
        $this->ci->load->helper('game_helper');

        if($gameid){
            $this->gameid = $gameid;
        } else {
            throw new ClassCreationException("gameid cannot be null.");
        }
    }

    //Game Statistics

    public function playerStatusCounts(){
        $zombie_count = 0;
        $human_count = 0;
        $starved_zombie_count = 0;

        $players = getViewablePlayers($this->gameid);
        foreach($players as $player){
                if(is_a($player, 'Zombie')){
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

    public function getPlayerIDs(){

    }
    
    public function getUserIDs(){

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
}
