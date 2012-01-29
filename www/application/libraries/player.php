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

      return $data;
  }

  public function current_team(){
    return 5;
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

  public function getGravatarHTML(){
    $gravatar_email = $this->getData('gravatar_email');
    $email = $this->getData('email');
    if($gravatar_email){
      return $this->build_gravatar($gravatar_email, 150, 'identicon', 'x', true);
    }
    else{
      return $this->build_gravatar($email, 150, 'identicon', 'x', true);
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