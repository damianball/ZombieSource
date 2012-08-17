<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Player{

    private $playerid = null;
    private $ci = null;
    private $data = array();

    public function __construct($playerid){
        $this->ci =& get_instance();
        $this->ci->load->model('Player_model','',TRUE);
        $this->ci->load->library('TeamCreator');
        $this->ci->load->library('GameCreator');

        if($playerid){
            $this->playerid = $playerid;
        } else {
            throw new ClassCreationException('cannot create new player with supplied arguments');
        }
    }

    public function isActive(){
        //TODO this is no longer accurate
        //also should check if isInGame(GAME_KEY)
        // dateremoved in the database

        $stateid = $this->ci->Player_model->getPlayerStateID($this->playerid);
        return $stateid == 1 ? true : false;

        // if(!$this->isBanned() && !$this->hasLeftGame()){
        //     return true;
        // }
        // return false;
    }

    public function hasLeftGame(){
        if($this->getData('dateremoved') > 0) {
            return true;
        } else {
            return false;
        }
    }

    // @TODO actually implement
    public function isBanned(){
        return false;
    }

    public function isModerator(){
        return $this->getData('moderator') == "1";
    }

    public function setModerator($make_moderator){
        $value = $make_moderator ? "1" : "0";
        $this->ci->Player_model->setPlayerData($this->playerid, 'moderator', $value);
    }

    public function toggleModerator(){
        return $this->setModerator(!$this->isModerator());
    }

    public function isViewable(){
        if($this->isActive()) {
            return true;
        } else {
            return false;
        }
    }

    public function canParticipate(){
        if($this->isActive()){
            return true;
        } else {
            return false;
        }
    }

    public function isActiveHuman(){
        return $this->canParticipate() && is_a($this, 'Human');
    }

    public function isActiveZombie(){
        return $this->canParticipate() && is_a($this, 'Zombie');
    }

    public function isGameClosed(){
        $gameid = $this->getCurrentGameId();
        $game = $this->ci->GameCreator->getGameByGameID(gameid);
        return $game->isClosedGame();
    }

    public function getGameName(){
        $game = $this->ci->gamecreator->getGameByGameID($this->getGameID());
        return $game->name();
    }

    public function getUser(){
        $this->ci->load->library('UserCreator');
        return $this->ci->usercreator->getUserByPlayerID($this->playerid);
    }

    public function waiverSigned(){
        return ($this->getData('waiver_is_signed') == "TRUE");
    }

    public function getData($key){
        if(!array_key_exists($key,$this->data)){
            $this->data[$key] = $this->ci->Player_model->getPlayerData($this->playerid, $key);
        }
        return $this->data[$key];
    }

    public function saveData($key, $value){
        $this->ci->Player_model->setPlayerData($this->playerid, $key, $value);
        $this->data[$key] = $value;
    }

    public function getPlayerID(){
        return $this->playerid;
    }

    public function getGameID(){
        return $this->ci->Player_model->getGameIDbyPlayerID($this->playerid);
    }

    public function getGame(){
        return $this->ci->gamecreator->getGameByGameID($this->getGameID());
    }

    public function isMemberOfATeam(){
        $hasTeam = FALSE;
        try{
            $this->ci->teamcreator->getTeamByTeamID($this->getTeamID());
            $hasTeam = TRUE;
        } catch (PlayerNotMemberOfAnyTeamException $e) {

        }
        return $hasTeam;
    }

    public function isMemberOfTeam($teamid){
        if(!$teamid) throw new UnexpectedValueException('teamid cannot be null');

        $isMember = FALSE;
        try{
            if($teamid == $this->getTeamID()) $isMember = TRUE;
        } catch (PlayerNotMemberOfAnyTeamException $e){

        }
        return $isMember;
    }

    public function getTeamID(){
        $this->ci->load->model('Player_team_model');
        return $this->ci->Player_team_model->getTeamIDByPlayerID($this->playerid);
    }

    public function leaveCurrentTeam(){
        $this->ci->load->model('Player_team_model');
        $this->ci->Player_team_model->removePlayerFromTeam($this->getTeamID(), $this->playerid);
    }

    public function leaveGame(){
        $this->ci->Player_model->makePlayerInactive($this->playerid);
    }


    public function isElligibleForTagUndo(){
        return false;
    }
}
