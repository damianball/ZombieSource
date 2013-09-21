<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Team{
    private $teamid = null;
    private $ci = null;

    public function __construct($teamid)
    {
        $this->ci =& get_instance();
        $this->ci->load->model('Team_model', '', true);
        $this->ci->load->model('Player_team_model', '', true);

        // @TODO: Verify that the team exists before allowing construction to finish
        if($teamid){
            $this->teamid = $teamid;
        } else {
            throw new ClassCreationException("Teamid cannot be null.");
        }
    }

    // MOVE THIS TO TEAM
    // Needs to take a playerid
    // Do proper game settings checks on size and such in this function
    // was joinTeam
    public function addPlayer($player){
        if(!is_a($player,'Player')) throw new UnexpectedValueException("object is not a player");
        try{
            $currentTeam = $player->getTeamID();
            //still in a team
            throw new PlayerMemberOfTeamException('Cannot join a team in this game while still a member of another team.');
        } catch (PlayerNotMemberOfAnyTeamException $e){

        }
        $this->ci->load->model('Player_team_model');
        $this->ci->Player_team_model->addPlayerToTeam($this->teamid, $player->getPlayerID());
    }

    public function unRemovePlayer($player){
        $this->ci->Player_team_model->undoDateRemoved($this->teamid, $player->getPlayerID());
    }

    public function getTeamSize(){
        return $this->ci->Player_team_model->getCountOfPlayersByTeamID($this->teamid);
    }

    public function getArrayOfPlayersOnTeam(){
        $this->ci->load->library('PlayerCreator');
        $playeridarray = $this->ci->Player_team_model->getListOfPlayerIDByTeamID($this->teamid);
        $playerArray = array();
        for($i = 0; $i<count($playeridarray); $i++){
            $potential_player = $this->ci->playercreator->getPlayerByPlayerID($playeridarray[$i]);
            if($potential_player->canParticipate()){
                $playerArray[] = $potential_player;
            }
        }

        return $playerArray;
    }

    public function getTeamMemberUserIDs(){
        $this->ci->load->model('Player_model', '', true);
        $playeridarray = $this->ci->Player_team_model->getListOfPlayerIDByTeamID($this->teamid);
        $userArray = array();
        for($i = 0; $i<count($playeridarray); $i++){
            $userArray[] = $this->ci->Player_model->getUserIDByPlayerID($playeridarray[$i]);   
        }
        return $userArray;
    }


    public function getArrayOfPlayersZombifiedOnTeam(){
        $this->ci->load->library('PlayerCreator');
        $playeridarray = $this->ci->Player_team_model->getListOfFormerPlayerIDByTeamID($this->teamid);
        $playerArray = array();
        for($i = 0; $i<count($playeridarray); $i++){
            $potential_player = $this->ci->playercreator->getPlayerByPlayerID($playeridarray[$i]);
            // ensure these are zombies, and not players that left their last team
            if($potential_player->getStatus() == 'zombie'){
                $playerArray[] = $potential_player;
            }
        }

        return $playerArray;
    }

    public function getTeamID(){
        return $this->teamid;
    }

    public function getGameID(){
        return $this->ci->Team_model->getGameIDByTeamID($this->teamid);
    }

    public function getData($key){
        return $this->ci->Team_model->getTeamData($this->teamid, $key);
    }

    public function setData($key, $value){
        $this->ci->Team_model->setTeamData($this->teamid, $key, $value);
    }

    public function leaveTime(){
        return $this->ci->Player_team_model->getDateRemovedByTeamID($this->teamid);
    }

    // Change to canPlayerEditTeam
    // hand player object or id... not sure
    public function canEditTeam($player){
        /* @TODO Damian 1/30/11
        This function should return true WHEN the player ($this->getPlayerID)
        is the oldest player on a team OR if they have been in the team for 12 hours
        */
        return $player->isMemberOfTeam($this->teamid);
    }

     public function getGravatarHTML($size=50){
         $this->ci->load->helper('gravatar_helper');

         return getGravatarHTML($this->getData('gravatar_email'), $this->getData('name'), $size, array('class' => 'twtr-pic'));
     }
}
