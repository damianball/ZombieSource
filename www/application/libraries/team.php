<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Team{
  private $teamid = null;
  private $ci = null;

  public function __construct($teamid = null)
  {
      //parent::__construct();
      $this->ci =& get_instance();
      $this->ci->load->model('Team_model', '', true);
      $this->ci->load->model('Player_team_model', '', true);

  }

  public static function getTeamByTeamID($teamid){
      if($teamid != null){
          $instance = new self();
          $instance->teamid = $teamid;
          return $instance;
      } else {
          throw new Exception("Teamid cannot be null.");
      }
  }

  public static function getNewTeam($name, $playerid){
          $instance = new self();
          $instance->teamid = $instance->ci->Team_model->createTeam($name, GAME_KEY);
          $instance->ci->Player_team_model->addPlayerToTeam($instance->teamid, $playerid);
          return $instance;
  }

  public function getDataArray(){
      $data = array();
      $data['team_name'] = $this->getData('name');
      $data['description'] = $this->getData('description');
      $data['profile_pic_url'] = $this->getGravatarHTML();
      $data['team_gravatar_email'] = $this->getData('gravatar_email');
      $data['teamid'] = $this->getTeamID();

      return $data;
  }

  public function getLinkToProfile(){
    $name = $this->getData('name');
    $id = $this->getTeamID();
    $link = "<a href = \"" . site_url("/team/$id") .  "\"> $name </a>";
    return $link; 
  }

  public function teamSize(){
    return 10;
  }

   public function totalTeamKills(){
    return 10;
  }


  public function getTeamID(){
    return $this->teamid;
  }

  public function getData($key){
    return $this->ci->Team_model->getTeamData($this->getTeamID(), $key);
  }

  public function setData($key, $value){
    $this->ci->Team_model->setTeamData($this->getTeamID(), $key, $value);
  }

  public function getGravatarHTML($size = 250){
    $gravatar_email = $this->getData('gravatar_email');
    $team_name = $this->getData('name');
    if($gravatar_email && $gravatar_email != ''){
      return $this->build_gravatar($gravatar_email, $size, 'identicon', 'x', true);
    }
    else{
      return $this->build_gravatar(md5($team_name), $size, 'identicon', 'x', true);
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