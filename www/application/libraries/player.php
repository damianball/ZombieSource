<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Player{
  private $playerid = null;
  private $userid = null;
  private $ci = null;

  public function __construct(){
      //parent::__construct();
      $this->ci =& get_instance();
      $this->ci->load->model('Player_model','',TRUE);
      $this->ci->load->library('User');
      $this->ci->load->library('Team');
  }

  public function getUser(){
      if($this->userid == null){
          if($this->playerid == null){
              throw new UnexpectedValueException('userid and playerid cannot be null');
          }
          return $this->ci->user->getUserByPlayerID($this->playerid);
      }
      return $this->ci->user->getUserByUserID($this->userid);
  }

  public function getPlayerByPlayerID($playerid){
      if($playerid != null){
          $instance = new self();
          $instance->playerid = $playerid;
          return $instance;
      } else {
          throw new Exception("Playerid cannot be null.");
      }
  }

  public function getPlayerByUserIDGameID($userid, $gameid){
      if(!$userid || !$gameid){
          throw new Exception("Userid nor Gameid can be null.");
      }
      $instance = new self();
      $instance->userid = $userid;
      $instance->playerid = $instance->ci->Player_model->getPlayerID($userid, $gameid);
      return $instance;
  }

  public  function getNewPlayerByJoiningGame($userid, $gameid, $params){
      if(!$userid || !$gameid){
          throw new UnexpectedValueException('userid and gameid cannot be null');
      }
      $instance = new self();
      $playerid = $instance->ci->Player_model->createPlayerInGame($userid, $gameid);
      $instance->playerid = $playerid;
      foreach($params as $key => $value){
          $instance->saveData($key, $value);
      }
      return $instance;
  }


    public function getActivePlayersString(){
        //this exposes human codes, only use in admin panel!!
        $players = $this->ci->Player_model->getActivePlayers();
        $my_string = "[";
        foreach($players as $i){
            $player = $this->getPlayerByPlayerID($i['id']);
            $username = $player->getUser()->getUsername();
            $humancode = $player->getHumanCode();
            $my_string .= "\"$username -- $humancode\",";
        }
        $my_string .= "\"\"]";
        return $my_string;
    }

    public function getActiveZombiesString(){
        $players = $this->ci->Player_model->getActivePlayers();
        $my_string = "[";
        foreach($players as $i){
            $player = $this->getPlayerByPlayerID($i['id']);
            $username = $player->getUser()->getUsername();
            $my_string .= "\"$username\",";
        }
        $my_string .= "\"\"]";
        return $my_string;
    }


  public function getDataArray(){
      $data = array();
      $data['username'] = $this->getUser()->getUsername();
      $data['email'] = $this->getUser()->getEmail();
      $data['age'] = $this->getData("age");
      $data['gender'] = $this->getData("gender");
      $data['major'] = $this->getData("major");
      $data['profile_pic_url'] = $this->getGravatarHTML();
      $data['gravatar_email'] = $this->getData('gravatar_email');
      $data['human_code'] = $this->getHumanCode();
      $data['link_to_team'] = $this->getLinkToTeam();
      return $data;
  }

  public function getHumanCode(){
    if(!$this->humanCodeExists()){
      $human_code = $this->newHumanCode();
      $this->saveData('human_code', $human_code);
    }
    return $this->getData("human_code");
  }

  public function newHumanCode(){
     $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
      do {
        $string = '';
        for ($i = 0; $i < 5; $i++) {
          $string .= $characters[rand(0, strlen($characters) - 1)];
        } 
      }while(false);
      //$this->Player_model->humanCodeExists($string)
      return $string;
  }

  public function waiverSigned(){
    return ($this->getData('waiver_is_signed') == "TRUE");
  }

  public function getData($key){
      if($this->playerid != null){
        return $this->ci->Player_model->getPlayerData($this->playerid, $key);
      } else {
        throw new Exception('Playerid cannot be null');
      }
  }

  public function saveData($key, $value){
    $this->ci->Player_model->setPlayerData($this->playerid, $key, $value);
  }

  public function getPlayerID(){
	    return $this->playerid;
  }

  public function getLinkToProfile(){
    $username = $this->getUser()->getUsername();
    $id = $this->getUser()->getUserID();
    $link = "<a href = \"" . site_url("/user/$id") .  "\"> $username </a>";
    return $link; 
  }

  public function getLinkToTeam(){
    if($this->isMemberOfATeam()){
        $teamid = $this->getTeamID();
        $team = $this->ci->team->getTeamByTeamID($teamid);
        return $team->getLinkToTeam();
    }else{
        return "none";
    }
  }


  // @TODO: write this function
  public function getGameID(){}

  public function isMemberOfATeam(){
      $hasTeam = FALSE;
      try{
          $this->ci->team->getTeamByTeamID($this->getTeamID());
          $hasTeam = TRUE;
      }catch (PlayerNotMemberOfAnyTeamException $e) {
        
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

  public function joinTeam($teamid){
      if(!$teamid) throw new UnexpectedValueException("teamid cannot be null");
      try{
          $currentTeam = $this->getTeamID();
          //still in a team
          throw new PlayerMemberOfTeamException('Cannot join a team in this game while still a member of another team.');
      } catch (PlayerNotMemberOfAnyTeamException $e){

      }
      $this->ci->load->model('Player_team_model');
      $this->ci->Player_team_model->addPlayerToTeam($teamid, $this->playerid);
  }

  public function leaveCurrentTeam(){
      $this->ci->load->model('Player_team_model');
      $this->ci->Player_team_model->removePlayerFromTeam($this->getTeamID(), $this->playerid);
  }
  public function leaveTeam($teamid){
      $this->ci->load->model('Player_team_model');
      $this->ci->Player_team_model->removePlayerFromTeam($teamid, $this->playerid);
  }

  public function canEditTeam($teamid){
      /*TODO @Damian 1/30/11
      This function should return true 
      WHEN the player ($this->getPlayerID)
      is the oldest player on a team
      OR if they have been in the team for 12 hours

      */
      return $this->isMemberOfTeam($teamid);
  }

  public function humanCodeExists(){
      $isCode = FALSE;
      if($this->getData("human_code")){
          $isCode = TRUE;
      }
      return $isCode;
  }

  public function getStatus(){
    return "human"; 
  }

  public function TimeSinceLastFeed(){
    return "N/A";
  }

  public function getKills(){
    return "N/A";
    // . ' hours ago'
  }

  public function getGravatarHTML($size = 150){
    $gravatar_email = $this->getData('gravatar_email');
    $email = $this->getUser()->getEmail();
    if($gravatar_email && $gravatar_email != ''){
      return $this->build_gravatar($gravatar_email, $size, 'identicon', 'x', true);
    }
    else{
      return $this->build_gravatar($email, $size, 'identicon', 'x', true);
    }
  }

   public function build_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
      $url = 'http://www.gravatar.com/avatar/';
      $url .= md5( strtolower( trim( $email ) ) );
      $url .= "?s=$s&d=$d&r=$r";
      if ( $img ) {
          $url = '<img src="' . $url . '"';
          foreach ( $atts as $key => $val )
              $url .= ' ' . $key . '="' . $val . '"';
          $url .= ' />';
      }
      return $url;
  }

  public function array_to_string($array){
    $my_string = "[";
    foreach($array as $i){
        $player = $this->player->getPlayerByPlayerID($i['id']);
        $username = $player->getUser()->getUsername();
        $humancode = $player->$this->getHumanCode();
        $my_string .= "\"$username -- $humancode\",";
    }
    $my_string .= "\"\"]";
    return $my_string;
  }

}