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

      $table_data = array(
                 array('User', 'Status', 'Kills'),
                 array('Fred', 'Blue', 'Small'),
                 array('Mary', 'Red', 'Large'),
                 array('John', 'Green', 'Medium') 
                 );

     $game_table = $this->table->generate($table_data);
     $data = array('game_table' => $game_table);

     $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
     $layout_data['content_body'] = $this->load->view('game/game_page', $data, true);
     $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
     $this->load->view('layouts/main', $layout_data);
    }

    function register_kill(){
      
    }

}