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
                array('data' => 'Zombie', 'class' => 'sortable'),
                array('data' => 'Status', 'class' => 'sortable'),
                array('data' => 'Kills', 'class' => 'sortable'),
                array('data' => 'Last Feed', 'class' => 'sortable'));

      for($i=0; $i<10; $i=$i+1){
        $this->table->add_row(
        array('User', 'active',rand(0,20), '6 hours ago')
        );       
      }

     //-- Display Table
     $zombie_table = $this->table->generate();



      $this->table->set_heading(
                array('data' => 'Human', 'class' => 'sortable'),
                array('data' => 'squad', 'class' => 'sortable'),
                array('data' => 'Status', 'class' => 'sortable'));

      for($i=0; $i<10; $i=$i+1){
        $this->table->add_row(
        array('User', 'Blue', 'active')
        );       
      }



     $human_table = $this->table->generate();


     $data = array('zombie_table' => $zombie_table,
                   'human_table' => $human_table
                   );

     $layout_data['player_list_active'] = 'id = "selected"';
     $layout_data['log_kill_active'] = " ";
     $layout_data['game_stats_active'] = " ";

     $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
     $layout_data['content_body'] = $this->load->view('game/game_page', $data, true);
     $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
     $this->load->view('layouts/game_layout', $layout_data);
    }

    function stats(){

     $layout_data['player_list_active'] = "";
     $layout_data['log_kill_active'] = "";
     $layout_data['game_stats_active'] = 'id = "selected"';

     $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
     $layout_data['content_body'] = $this->load->view('game/game_stats','', true);
     $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
     $this->load->view('layouts/game_layout', $layout_data);
      
    }


    function register_kill(){


     $layout_data['player_list_active'] = "";
     $layout_data['log_kill_active'] = 'id = "selected"';
     $layout_data['game_stats_active'] = "";
  
     $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
     $layout_data['content_body'] = $this->load->view('game/register_kill','', true);
     $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
     $this->load->view('layouts/game_layout', $layout_data);
      
    }
}