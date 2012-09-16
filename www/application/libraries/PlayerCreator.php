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
        $this->ci->load->model('Player_model','',true);
        $this->ci->load->helper('player_helper');
        $this->ci->load->helper('user_helper');
        $this->ci->load->model('Tag_model','',true);
    }

    // was getNewPlayerByJoiningGame
    public  function createPlayerByJoiningGame($userid, $gameid, $params){
        if(!$userid || !$gameid){
            throw new UnexpectedValueException('userid and gameid cannot be null');
        }
        $this->ci->load->model('Player_model','',TRUE);
        $playerid = $this->ci->Player_model->createPlayerInGame($userid, $gameid);
        $newPlayer = $this->getPlayerByPlayerID($playerid);
        if($params != NULL){
            foreach($params as $key => $value){
                $newPlayer->saveData($key, $value);
            }
        }
        $this->ci->Player_model->makePlayerActive($newPlayer->getPlayerID());
        return $newPlayer;
    }

    // NEED TO CHECK IF THAT PLAYER EXISTS
    public function getPlayerByPlayerID($playerid){
        if(!$playerid){
            throw new Exception("Playerid cannot be null.");
        }
        if(!playerExistsByPlayerID($playerid)){
            throw new PlayerDoesNotExistException('Cannot create a player object for a player that does not exist. playerid: '.$playerid);
        }
        return $this->buildPlayerByStatusInGame($playerid);
    }

    public function getPlayerByUserIDGameID($userid, $gameid){
        if(!$userid || !$gameid){
            throw new InvalidParametersException("Userid nor Gameid can be null.");
        }

        $this->ci->load->library('PlayerCreator');
        $playerid = null;
        if(userExistsInGame($userid,$gameid)){
            $playerid = getPlayerIDByUserIDGameID($userid, $gameid);
        } else {
            throw new PlayerDoesNotExistException('user does not exist in game');
        }

        return $this->buildPlayerByStatusInGame($playerid);
    }

    private function buildPlayerByStatusInGame($playerid){
        $player = null;
        if($this->ci->Player_model->getPlayerData($playerid, 'original_zombie')){
            $player = new OriginalZombie($playerid);
        } else if ($this->ci->Tag_model->validTagExistsForPlayer($playerid)){
            $player = new Zombie($playerid);
        } else {
            $player = new Human($playerid);
        }

        return $player;
    }
}
