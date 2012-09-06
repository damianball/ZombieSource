<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/User.php');

// Factory class for instanciating a team

class UserCreator{
    private $ci;

    public function __construct(){
        $this->ci =& get_instance();
    }

    public function getUserByUserID($userid){
        if(!$userid){
            throw new UnexpectedValueException('userid cannot be null');
        }
        return new User($userid);
    }

    public function getUserByPlayerID($playerid){
        if(!$playerid){
            throw new UnexpectedValueException('playerid cannot be null');
        }
        $this->ci->load->model('User_player_model','',TRUE);
        $userid = $this->ci->User_player_model->getUserIDByPlayerID($playerid);
        return new User($userid);
    }

    public function getUserByPhone($phone){
        if(!$phone){
            throw new UnexpectedValueException('phone cannot be null');
        }
        $this->ci->load->model('User_model','',TRUE);
        $userid = $this->ci->User_model->getUserIDByPhone($phone);
        if($userid){
            return new User($userid);
        }else{
            return null;
        }
    }
}