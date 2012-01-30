<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User{
    private $userid = null;
    private $ci = null;
    function __construct()
    {
        //parent::__construct();
        $this->ci =& get_instance();
        $this->ci->load->model('User_model','',TRUE);
    }

    public function getUserID(){
        if($this->userid == null){
            throw new UnexpectedValueException('userid in User object is null');
        }
        return $this->userid;
    }

    public function getUserByUserID($userid){
        if($userid != null){
            $instance = new self();
            $instance->userid = $userid;
            return $instance;
        } else {
            throw new UnexpectedValueException('userid cannot be null');
        }
    }

    public function getUserByPlayerID($playerid){
        if($playerid == null){
            throw new UnexpectedValueException('playerid cannot be null');
        }
        $instance = new self();
        $instance->ci->load->model('User_player_model','',TRUE);
        $instance->userid = $instance->ci->User_player_model->getUserIDByPlayerID($playerid);
        return $instance;
    }

    public function getEmail(){
        return $this->ci->User_model->getEmailByUserID($this->getUserID());
    }

    public function getUsername(){
        return $this->ci->User_model->getUsernameByUserID($this->getUserID());
    }
}