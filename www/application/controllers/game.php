<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class game extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        if(!$this->tank_auth->is_logged_in()){
            redirect('/auth/login');
        }
        $this->load->model('Player_model','',TRUE);
        $this->load->model('Team_model','',TRUE);
        $this->load->library('player', null);
        $this->load->library('team', null);

    }

    public function index()
    {
        //load the content variables
        $this->table->set_heading(
        array('data' => 'Avatar'),
        array('data' => 'Player', 'class' => 'sortable'),
        array('data' => 'Team', 'class' => 'sortable'),
        array('data' => 'Status', 'class' => 'sortable'),
        array('data' => 'Kills', 'class' => 'sortable'),
        array('data' => 'Last Feed', 'class' => 'sortable'));

        $players = $this->Player_model->getActivePlayers();
        foreach($players as $i){
          $player = Player::getPlayerByPlayerID($i['id']);
          $row = array(
                       $player->getGravatarHTML(50),
                       $player->getLinkToProfile(),
                       "team",#$player->getTeam(),
                       "Human", #$player->getStatus(),
                       "N/A", #$player->getKills(),
                       "N/A"); #$player->TimeSinceLastFeed() . ' hours ago');
          $this->table->add_row($row);
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

        $teams = $this->Team_model->getAllTeams();
        foreach($teams as $i){
          $team = $this->team->getTeamByTeamID($i['id']);
          $row = array(
                       $team->getGravatarHTML(50),
                       $team->getLinkToProfile(),
                       $team->teamSize(),
                       "N/A");#$team->totalTeamKills());
          $this->table->add_row($row);
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
        $this->load->model('Player_model','',TRUE);
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

    public function register_new_team(){
      $userid = $this->tank_auth->get_user_id();
      $player = Player::getPlayerByUserIDGameID($userid, GAME_KEY);
      $this->form_validation->set_rules('team_name', 'Team Name', 'trim|xss_clean|required');
      $this->form_validation->set_rules('team_gravatar_email', 'Gravatar Email', 'email|trim|xss_clean');
      $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean');

      if ($this->form_validation->run()) {
        // save the data
        $name = $this->input->post('team_name');
        $gravatar_email = $this->input->post('team_gravatar_email');
        $description = $this->input->post('description');

        $team = $this->Team->getNewTeam($name, $player->getPlayerID());
        $team->setData('gravatar_email', $gravatar_email);
        $team->setData('description', $description);

        redirect("team/".$team->getTeamID());
      }

      //display the regular page, with errors
      $layout_data['active_sidebar'] = 'logkill';
      $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
      $layout_data['content_body'] = $this->load->view('game/register_new_team', '', true);
      $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
      $this->load->view('layouts/main', $layout_data); 
    }

    public function join_team(){
      $data = array();
      $userid = $this->tank_auth->get_user_id();
      $player = Player::getPlayerByUserIDGameID($userid, GAME_KEY);
      $teamid = $this->input->post('teamid');
      $data['teamid'] = $teamid;
      $currentTeam = $this->team->getTeamByTeamID($player->getTeamID());
      $newTeam = $this->team->getTeamByTeamID($teamid);
      if($currentTeam){
        $player->leaveCurrentTeam();
        $player->joinTeam($teamid);
        $data['message'] = "Successfully left " . $currentTeam->getData('name') . " and joined " . $newTeam->getData('name');
        
      }else{
        $data['message'] = "Successfully joined " . $newTeam->getData('name');
        $player->joinTeam($teamid);
      }

      $layout_data['active_sidebar'] = 'logkill';
      $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
      $layout_data['content_body'] = $this->load->view('helpers/team_helper', $data, true);
      $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
      $this->load->view('layouts/main', $layout_data); 

      // @TODO: get old team

      // @TODO: check for size limit on incoming team (game_setting)

    }

    public function leave_team(){
      $userid = $this->tank_auth->get_user_id();
      $player = Player::getPlayerByUserIDGameID($userid, GAME_KEY);
      $teamid = $this->input->post('teamid');
      $currentTeam = $player->getTeamID();
      if($currentTeam){
        $player->leaveCurrentTeam(); 
        $data['message'] = "Successfully left " . $currentTeam->getData('name');
       
      }else{
        $data['message'] = "could not leave team because you were not on team";
      }

      $layout_data['active_sidebar'] = 'logkill';
      $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
      $layout_data['content_body'] = $this->load->view('helper/team_helper', $data, true);
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