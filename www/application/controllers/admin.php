<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {


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

	public function index(){
		$user_id = $this->tank_auth->get_user_id();
        $data['player_list'] = $this->player->getActivePlayersString();

        $layout_data = array();
        $layout_data['active_sidebar'] = 'playerlist';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('admin/admin_page', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data);
	}

    public function player_controls(){
        $user_id = $this->tank_auth->get_user_id();
        $data['player_list'] = $this->player->getActivePlayersString();
        $player_string = $this->input->post('player');
        $this->load->view('admin/player_controls.php', $data);
    }

    public function team_controls(){
        $user_id = $this->tank_auth->get_user_id();
        $data['player_list'] = $this->player->getActivePlayersString();
        $player_string = $this->input->post('player');
        $this->load->view('admin/team_controls.php', $data);
    }
}