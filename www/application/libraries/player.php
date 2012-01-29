<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Player extends CI_Controller {
  var $playerid;
  var $userid;
  var $data = array();

  public function __construct($userid = null)
  {
      parent::__construct();

      if($userid != null){
        $this->load->model('Player_model','',TRUE);

        $this->userid = $userid;
        $this->playerid = $this->Player_model->getPlayerID($this->userid, GAME_KEY);
        
        $this->data['username'] = $this->data("username");
        $this->data['email'] = $this->data("email");
        $this->data['age'] = $this->data("age");
        $this->data['gender'] = $this->data("gender");
        $this->data['major'] = $this->data("major");
        $this->data['profile_pic_url'] = $this->gravatar(); 
      }
  }


  public function waiver_is_signed(){
    return ($this->data('waiver_is_signed') == "TRUE");
  }

  public function data($key){
    return $this->Player_model->getPlayerData($this->playerid, $key);
  }

  public function save($key, $value){
    return $this->Player_model->setPlayerData($this->playerid, $key, $value);
  }

  public function join_game($params){
    $this->Player_model->createPlayerInGame($this->userid, GAME_KEY);
    foreach($parmas as $key => $value){
        $this->save($key, $value);
    }
  }

  public function gravatar(){
    $gravatar_email = $this->data('gravatar_email');
    $email = $this->data('email');
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