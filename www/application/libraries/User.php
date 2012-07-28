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

    public function joinGame($userid, $gameid){
        //TODO if player has already left game once what do
        $player = $CI->playercreator->createPlayerByJoiningGame($userid, $gameid);
        return $player->getPlayerID();
    }

    public function leaveGame($userid, $gameid){
        //change player state to inactive
        $player = getPlayerByUserIDGameID($userid, $gameid);
        $player->leaveGame();

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

    public function getModeratorPlayers(){
        try{
            $playerids = $this->ci->Player_model->getModeratorPlayerIDsByUserID($this->userid);
        } catch (UnexpectedValueException $e){
            return FALSE;
        } catch (UserIsNotModeratorException $e){
            return FALSE;
        }
        $player_objects = array();
        foreach($playerids as $playerid){
            $player_objects[$playerid] = $this->ci->PlayerCreator->getPlayerByPlayerID($playerid);
        }
        return $player_objects;
    }
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
