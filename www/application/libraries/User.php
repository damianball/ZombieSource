<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    private $userid = null;

    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model','',TRUE);
    }

    public function getUserID(){
        if($this->userid == null){
            throw new UnexpectedValueException('userid in User object is null');
        }
        return $this->userid;
    }

    public static function getUserByUserID($userid){
        if($userid != null){
            $instance = new self();
            $instance->userid = $userid;
            return $instance;
        } else {
            throw new UnexpectedValueException('userid cannot be null');
        }
    }

    public static function getUserByPlayerID($playerid){
        if($playerid == null){
            throw new UnexpectedValueException('playerid cannot be null');
        }
        $ci =& get_instance();
        $ci->load->model('User_player_model','',TRUE);
        $instance = new self();
        $instance->userid = $ci->User_player_model->getUserIDByPlayerID($playerid);
        return $instance;
    }

    public function getEmail(){
        return $this->User_model->getEmailByUserID($this->getUserID());
    }

    public function getUsername(){
        return $this->User_model->getUsernameByUserID($this->getUserID());
    }
}