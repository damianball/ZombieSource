<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User{
    private $userid = null;
    private $ci = null;
    private $data = array();

    function __construct($userid)
    {
        //parent::__construct();
        $this->ci =& get_instance();
        $this->ci->load->library('PlayerCreator');
        $this->ci->load->library('GameCreator');
        $this->ci->load->model('User_model','',TRUE);
        $this->ci->load->model('Player_model','',TRUE);
        if($userid){
            $this->userid = $userid;
        } else {
            throw new ClassCreationException('userid in User object is null');
        }
    }

    public function joinGame($gameid){
        if($this->isInGame($gameid)){
            $player = $this->ci->playercreator->getPlayerByUserIDGameID($this->userid, $gameid, NULL);
            if(!$player->isActive()){
                $currgameid = $this->currentGameID();
                if($currgameid){ $this->leaveGame($currgameid);}
                $this->ci->Player_model->makePlayerActive($player->getPlayerID());
            }

        }elseif($this->canJoinGame($gameid)){
            $currgameid = $this->currentGameID();
            if($currgameid){ $this->leaveGame($currgameid);}
            $player = $this->ci->playercreator->createPlayerByJoiningGame($this->userid, $gameid, NULL);
        }
        return $this->isInGame($gameid);
    }

    public function leaveGame($gameid){
        //change player state to inactive
        $player = $this->ci->playercreator->getPlayerByUserIDGameID($this->userid, $gameid);
        $player->leaveGame();
        return !$this->isInGame($gameid);
    }

    public function canJoinGame($gameid){
        if(validGameID($gameid)){
            $game = $this->ci->gamecreator->getGameByGameID($gameid);
            if($game->registrationOpen() && !$this->isInGame($gameid)){
                return true;
            }
        }
        return false;
    }

    public function isInGame($gameid){
        try{
            $player = $this->ci->playercreator->getPlayerByUserIDGameID($this->userid, $gameid);
            return true;
        }catch(PlayerDoesNotExistException $e){
            return false;
        }
    }

    public function isActiveInGame($gameid){
        try {
            $player = $this->ci->playercreator->getPlayerByUserIDGameID($this->userid, $gameid);
        } catch(PlayerDoesNotExistException $e){
            return false;
        }
        return $this->isInGame($gameid) && $player->isActive();
    }

    public function isActiveInCurrentGame(){
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
        return $this->ci->Player_model->getGameIDByUserID($this->userid);
    }


    public function getModeratorPlayers(){
        try{
            $playerids = $this->ci->Player_model->getModeratorPlayerIDsByUserID($this->userid);
        } catch (UnexpectedValueException $e){
            return false;
        } catch (UserIsNotModeratorException $e){
            return false;
        }
        $player_objects = array();
        foreach($playerids as $playerid){
            $player_objects[$playerid] = $this->ci->playercreator->getPlayerByPlayerID($playerid);
        }
        return $player_objects;
    }

     public function getData($key){
         if(!array_key_exists($key,$this->data)){
             $this->data[$key] = $this->ci->User_model->getUserData($this->userid, $key);
         }
         return $this->data[$key];
     }

     public function saveData($key, $value){
         $this->ci->User_model->setUserData($this->userid, $key, $value);
         $this->data[$key] = $value;
     }

}
