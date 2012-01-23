<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class game extends CI_Controller {
  function __construct()
  {
      parent::__construct();

      if(!$this->tank_auth->is_logged_in()){
        redirect('/auth/login');
      }
  }


    public function index()
    {
     //load the content variables

      $this->table->set_heading(
                array('data' => 'Avatar'),
                array('data' => 'Player', 'class' => 'sortable'),
                array('data' => 'Squad', 'class' => 'sortable'),
                array('data' => 'Status', 'class' => 'sortable'),
                array('data' => 'Kills', 'class' => 'sortable'),
                array('data' => 'Last Feed', 'class' => 'sortable'));

      for($i=0; $i<10; $i=$i+1){
        $gravatar = $this->get_gravatar(md5($i), 50, 'identicon', 'x', true);
        $this->table->add_row(
          array($gravatar, 'User', 'blue', 'zombie',rand(0,20), '6 hours ago')
        );       
      }

     //-- Display Table
     $game_table = $this->table->generate();     
     $data = array('game_table' => $game_table);

      $layout_data['team_list_active'] =  " ";
     $layout_data['player_list_active'] = 'id = "selected"';
     $layout_data['register_kill_active'] = " ";
     $layout_data['game_stats_active'] = " ";

     $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
     $layout_data['content_body'] = $this->load->view('game/game_page', $data, true);
     $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
     $this->load->view('layouts/game_layout', $layout_data);
    }



    function teams(){
     $this->table->set_heading(
                array('data' => 'Avatar'),
                array('data' => 'Squad', 'class' => 'sortable'),
                array('data' => 'Size', 'class' => 'sortable'),
                array('data' => 'Team Kills', 'class' => 'sortable'));

      for($i=0; $i<10; $i=$i+1){
        $gravatar = $this->get_gravatar(md5($i), 50, 'identicon', 'x', true);
        $this->table->add_row(
          array($gravatar, 'Team Name', rand(0,10) ,rand(0,20))
        );       
      }

     //-- Display Table
     $game_table = $this->table->generate();     
     $data = array('game_table' => $game_table);

     $layout_data['team_list_active'] = 'id = "selected"';
     $layout_data['player_list_active'] =  " ";
     $layout_data['register_kill_active'] = " ";
     $layout_data['game_stats_active'] = " ";

     $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
     $layout_data['content_body'] = $this->load->view('game/team_page', $data, true);
     $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
     $this->load->view('layouts/game_layout', $layout_data);
    }



    function stats(){

     $layout_data['team_list_active'] =  " ";
     $layout_data['player_list_active'] = "";
     $layout_data['register_kill_active'] = "";
     $layout_data['game_stats_active'] = 'id = "selected"';

     $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
     $layout_data['content_body'] = $this->load->view('game/game_stats','', true);
     $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
     $this->load->view('layouts/game_layout', $layout_data);
      
    }


    function register_kill(){


     $layout_data['team_list_active'] =  " ";
     $layout_data['player_list_active'] = "";
     $layout_data['register_kill_active'] = 'id = "selected"';
     $layout_data['game_stats_active'] = "";

     $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
     $layout_data['content_body'] = $this->load->view('game/register_kill','', true);
     $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
     $this->load->view('layouts/game_layout', $layout_data);
      
    }


    public function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
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