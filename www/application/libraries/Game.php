<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game{
    private $teamid = null;
    private $ci = null;

    public function __construct($gameid)
    {
        $this->ci =& get_instance();
        $this->ci->load->model('Game_model', '', true);

        if($gameid){
            $this->gameid = $gameid;
        } else {
            throw new ClassCreationException("gameid cannot be null.");
        }
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
        return $this->ci->Game_model->description($this->gameid);
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
