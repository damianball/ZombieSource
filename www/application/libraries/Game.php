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

    public function name(){
        return $this->ci->Game_model->getGameName($this->gameid);
    }

    //TODO actually check.
    public function registrationOpen(){
        return true;
    }
}