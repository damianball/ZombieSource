<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User{
    private $userid = null;
    private $ci = null;

    function __construct($userid)
    {
        //parent::__construct();
        $this->ci =& get_instance();
        $this->ci->load->library('PlayerCreator');

        $this->ci->load->model('User_model','',TRUE);
        $this->ci->load->model('Player_model','',TRUE);

        if($userid){
            $this->userid = $userid;
        } else {
            throw new ClassCreationException('userid in User object is null');     
        }
    }

    public function joinGame($gameid){
        //TODO if player has already left game once what do
        //TODO if this user has users in another game they become inactive
        // should probably warn them about that
        if($this->userCanJoinGame($gameid)){
            $player = $CI->playercreator->createPlayerByJoiningGame($userid, $gameid);
        }
        return $this->isInGame($gameid);
    }

    public function leaveGame($gameid){
        //change player state to inactive
        $player = getPlayerByUserIDGameID($userid, $gameid);
        $player->leaveGame();
        return !$this->isInGame($gameid);
    }

    public function canJoinGame($gameid){
        if(validGameID($gameid)){
            $game = $this->gamecreator->getGameByGameID($gameid);
            if($game->registrationOpen() && !isInGame($gameid)){
                return true;
            }
        }
        return false;
    }

    public function isInGame($gameid){
        try{
            $player = $this->playercreator->getPlayerByUserIDGameID($this->userid, $gameid);
            return true;
        }catch(PlayerDoesNotExistException $e){
            return false;
        }
    }

    public function isActiveInCurrentGame(){
        $current_game_id = GAME_KEY; //TODO fix
        try{
            $player = $this->ci->playercreator->getPlayerByUserIDGameID($this->userid, $this->currentGameID());
            return $player->isActive();
        }catch(InvalidParametersException $e){
            return false;
        }
    }

    public function getUserID(){
        return $this->userid;
    }

    public function getEmail(){
        return $this->ci->User_model->getEmailByUserID($this->userid);
    }

    public function getUsername(){
        return $this->ci->User_model->getUsernameByUserID($this->userid);
    }

    public function currentGameID(){
        return $this->ci->Player_model->getCurrentGameIDByUserID($this->userid);
    }

    //====== Data methods to migrate from player model

    // public function getData($key){
    //     if(!array_key_exists($key,$this->data)){
    //         $this->data[$key] = $this->ci->User_model->getUserData($this->userid, $key);
    //     } 
    //     return $this->data[$key];
    // }

    // public function saveData($key, $value){
    //     $this->ci->User_model->setUserData($this->userid, $key, $value);
    //     $this->data[$key] = $value;
    // }

}