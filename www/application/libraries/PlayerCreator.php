<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Player.php');
require_once(APPPATH . 'libraries/Zombie.php');
require_once(APPPATH . 'libraries/Human.php');
require_once(APPPATH . 'libraries/OriginalZombie.php');

// Factory class for creating a player

class PlayerCreator{
    private $ci = null;

    public function __construct(){
        $this->ci =& get_instance();
    }

    // MOVE TO PLAYER CREATOR
    public function getPlayerByPlayerID($playerid){
        if($playerid != null){
            return new Human($playerid);
        } else {
            throw new Exception("Playerid cannot be null.");
        }
    }

    // MOVE TO PLAYER CREATOR
    public function getPlayerByUserIDGameID($userid, $gameid){
        if(!$userid || !$gameid){
            throw new Exception("Userid nor Gameid can be null.");
        }

        $this->ci->load->library('PlayerCreator');
        $playerid = null;
        if(userExistsInGame($userid,$gameid)){
            $playerid = getPlayerIDByUserIDGameID($userid, $gameid);
        } else {
            throw new PlayerDoesNotExistException('user does not exist in game');
        }

        return new Human($playerid);
    }

    // MOVE TO PLAYER CREATOR
    // was getNewPlayerByJoiningGame
    public  function createPlayerByJoiningGame($userid, $gameid, $params){
        if(!$userid || !$gameid){
            throw new UnexpectedValueException('userid and gameid cannot be null');
        }
        $this->ci->load->model('Player_model','',TRUE);
        $playerid = $this->ci->Player_model->createPlayerInGame($userid, $gameid);
        $newPlayer = new Human($playerid);
        foreach($params as $key => $value){
            $newPlayer->saveData($key, $value);
        }
        return $newPlayer;
    }
}