<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class game extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        if(!$this->tank_auth->is_logged_in()){
            redirect('/auth/login');
        }
        $this->load->library('player', null);
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

        $layout_data = array();
        $layout_data['active_sidebar'] = 'playerlist';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game/game_page', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/game_layout', $layout_data);
    }

    public function teams(){
        $this->table->set_heading(
            array('data' => 'Avatar'),
            array('data' => 'Squad', 'class' => 'sortable', 'class' => "medium_width_column"),
            array('data' => 'Size', 'class' => 'sortable'),
            array('data' => 'Team Kills', 'class' => 'sortable')
        );

        for($i=0; $i<10; $i=$i+1){
            $gravatar = $this->get_gravatar(md5($i), 50, 'identicon', 'x', true);
            $this->table->add_row(
                array($gravatar, 'Team the =best really long team name in the worldName', rand(0,10) ,rand(0,20))
            );       
        }

        //-- Display Table
        $game_table = $this->table->generate();     
        $data = array('game_table' => $game_table);

        $layout_data = array();
        $layout_data['active_sidebar'] = 'teamlist';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game/team_page', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/game_layout', $layout_data);
    }

    public function stats() {
        $layout_data = array();
        $layout_data['active_sidebar'] = 'stats';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game/game_stats','', true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/game_layout', $layout_data);
    }

    public function register_kill() {
        $zombie_id = $this->tank_auth->get_user_id();
        $this->load->model('Player_model');
        if(!$this->Player_model->isActiveZombie($zombie_id)) {
            $layout_data = array();
            $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
            $layout_data['content_body'] = $this->load->view('game/invalid_zombie','', true);
            $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
            $this->load->view('layouts/game_layout', $layout_data);
            // load view you aren't a zombie
            exit();
        }

        $this->form_validation->set_rules('human_code', 'Human Code', 'trim|required|xss_clean|min_length[9]|max_length[12]|callback_validate_human_code');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        // on success, try to log the tag
        $form_error = '';
        if ($this->form_validation->run()) {
            //$this->load->library('Exceptions');
            $human_code = $this->input->post('human_code');
            $claimed_tag_time_offset = $this->input->post('claimed_tag_time_offset');
            try{
                $this->load->model('Tag_model');
                $this->Tag_model->storeNewTag($human_code, $zombie_id, $claimed_tag_time_offset, null, null);
                redirect('game');
            } catch (DatastoreException $e){
                $form_error = $e->getMessage();
            }
        }
        $error_data['form_error'] = $form_error;
        
        //display the regular page, with errors
        $layout_data['active_sidebar'] = 'logkill';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game/register_kill',$error_data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/game_layout', $layout_data);     
    }

    function register_new_team(){


      $playerid = $this->Player_model->getPlayerID($this->tank_auth->get_user_id(), GAME_KEY);
      $data = array();
      $this->form_validation->set_rules('team_name', 'Team Name', 'integer|trim|xss_clean|required');
      $this->form_validation->set_rules('team_gravatar_email', 'Team Photo', 'email|trim|xss_clean');
      $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean');

      if ($this->form_validation->run()) {
        //save the data
        if($data['age'] != $this->input->post('age')){
          $this->Player_model->setPlayerData($playerid, 'age', $this->input->post('age'));
        }
        if($data['gender'] != $this->input->post('gender')){
          $this->Player_model->setPlayerData($playerid, 'gender', $this->input->post('gender'));
        }
        if($data['major'] != $this->input->post('major')){
          $this->Player_model->setPlayerData($playerid, 'major', $this->input->post('major'));
        }
        if($data['gravatar_email'] != $this->input->post('gravatar_email')){
          $this->Player_model->setPlayerData($playerid, 'gravatar_email', $this->input->post('gravatar_email'));
        }
        redirect("profile");
      }

        //display the regular page, with errors
        $layout_data['active_sidebar'] = 'logkill';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game/register_new_team', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data); 
    }

    public function validate_human_code() {
        $this->form_validation->set_message('validate_human_code', 'The %s field did not validate.');
        return true;
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