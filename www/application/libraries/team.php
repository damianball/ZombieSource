<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Team extends CI_Controller {
  var $teamid;
  var $data = array();

  public function __construct($teamid = null)
  {
      parent::__construct();

      if($teamid != null){
        $this->teamid = $teamid;
        $this->load->model('Team_model');

        $this->data['name'] = $this->name();
        $this->data['description'] = $this->description();
        $this->data['profile_pic_url'] = $this->gravatar();
      }
  }

  public function name(){
    return "world";
  }

  public function description(){
    return "world";
  }

  public function members(){
    return "world";
  }

  public function gravatar_email(){
    return "world";
  }

  public function gravatar(){
      return $this->build_gravatar($this->gravatar_email(), 250, 'identicon', 'x', true);
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