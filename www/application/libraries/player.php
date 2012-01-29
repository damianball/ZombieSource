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
  }

  public function getUser(){
      if($this->userid == null){
          if($this->playerid == null){
              throw new UnexpectedValueException('userid and playerid cannot be null');
          }
          return User::getUserByPlayerID($this->playerid);
      }
      return User::getUserByUserID($this->userid);
  }

  public static function getPlayerByPlayerID($playerid){
      if($playerid != null){
          $instance = new self();
          $instance->playerid = $playerid;
          return $instance;
      } else {
          throw new Exception("Playerid cannot be null.");
      }
  }

  public static function getPlayerByUserIDGameID($userid, $gameid){
      if(!$userid || !$gameid){
          throw new Exception("Userid nor Gameid can be null.");
      }
      $instance = new self();
      $instance->userid = $userid;
      $instance->playerid = $instance->ci->Player_model->getPlayerID($userid, $gameid);
      return $instance;
  }

  public static function getNewPlayerByJoiningGame($userid, $gameid, $params){
      if(!$userid || !$gameid){
          throw new UnexpectedValueException('userid and gameid cannot be null');
      }
      $instance = new self();
      $playerid = $instance->Player_model->createPlayerInGame($userid, $gameid);
      $instance->playerid = $playerid;
      foreach($params as $key => $value){
          $instance->saveData($key, $value);
      }
      return $instance;
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
      return $data;
  }

  public function current_team(){
    return 5;
  }

  public function getHumanCode(){
    // if(!$this->getData("human_code") || $this->getData("human_code") == ''){
      $human_code = $this->newHumanCode();
      $this->saveData('human_code', $human_code);
    // }
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

  // @TODO: write this function
  public function getGameID(){}

  public function getTeamID(){
    $this->ci->load->model('Player_team_model');
    try{
        return $this->ci->Player_team_model->getTeamIDByPlayerID($this->playerid);
    } catch (PlayerNotMemberOfAnyTeamException $e){
        return null;
    }
  }

  public function joinTeam($teamid){
      if(!$teamid) throw new UnexpectedValueException("teamid cannot be null");
      $this->ci->load->model('Player_team_model');
      $this->ci->Player_team_model->addPlayerToTeam($teamid, $this->playerid);
  }

  public function leaveCurrentTeam(){
      $this->ci->load->model('Player_team_model');
      $this->ci->Player_team_model->removePlayerFromTeam($this->getTeamID(), $this->playerid);
  }

  public function getStatus(){
    return "zombie"; 
  }

  public function TimeSinceLastFeed(){
    return "6";
  }

  public function getKills(){
    return "6";
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
}