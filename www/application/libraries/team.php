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

  public function getDataArray(){
      $data = array();
      $data['name'] = $this->getData('name')
      $data['description'] = $this->getData('name')
      $data['profile_pic_url'] = $this->getGravatarHTML();
      $data['gravatar_email'] = $this->getData('gravatar_email');
      return $data;
  }

  public function getData($key){
    return $this->Team_model->
  }

  public function getGravatarHTML($size = 250){
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