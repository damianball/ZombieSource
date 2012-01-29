<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Player extends CI_Controller{
  private $playerid = null;
  private $userid = null;

  public function __construct(){
      parent::__construct();
      $this->load->model('Player_model','',TRUE);
      $this->load->library('User');
  }

  public function getUser(){
      if($this->userid == null){
          if($this->playerid == null){
              throw new UnexpectedValueException('userid cannot be null');
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
      if($userid != null && $gameid != null){
          $instance = new self();
          $instance->userid = $userid;
          $instance->playerid = $instance->Player_model->getPlayerID($userid, $gameid);
          return $instance;
      } else {
          throw new Exception("Userid nor Gameid can be null.");
      }
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
        return $this->Player_model->getPlayerData($this->playerid, $key);
      } else {
        throw new Exception('Playerid cannot be null');
      }
  }

  public function saveData($key, $value){
    return $this->Player_model->setPlayerData($this->playerid, $key, $value);
  }

  public function join_game($params){
    $this->Player_model->createPlayerInGame($this->userid, GAME_KEY);
    foreach($params as $key => $value){
        $this->saveData($key, $value);
    }
  }
  
  public function getPlayerId(){
	    return $this->playerid;
  }

  public function getLinkToProfile(){
    $username = $this->getUser()->getUsername();
    $id = $this->getUser()->getUserID();
    $link = "<a href = \"" . site_url("/user/$id") .  "\"> $username </a>";
    return $link; 
  }
  public function getTeam(){
    return "team";
    
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
    if($gravatar_email){
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