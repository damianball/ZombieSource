<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -  
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->load->model('Player_model','',TRUE);

        $user_id = $this->tank_auth->get_user_id();
        $player_id = $this->Player_model->getPlayerID($user_id,'9a051bbc-3ebc-11e1-b778-000c295b88cf');
        $data = array('waiver' => $this->Player_model->getPlayerData($player_id,'waiver_is_signed'),
            'count' => $this->Player_model->getNumberOfPlayersInGame('9a051bbc-3ebc-11e1-b778-000c295b88cf'),
			'male' => $this->Player_model->getNumberOfPlayersInGameByNVP('9a051bbc-3ebc-11e1-b778-000c295b88cf','gender','male'),
			'female' => $this->Player_model->getNumberOfPlayersInGameByNVP('9a051bbc-3ebc-11e1-b778-000c295b88cf','gender','female'),
			'other' => $this->Player_model->getNumberOfPlayersInGameByNVP('9a051bbc-3ebc-11e1-b778-000c295b88cf','gender','other'),
			'noresponse' => $this->Player_model->getNumberOfPlayersInGameByNVP('9a051bbc-3ebc-11e1-b778-000c295b88cf','gender','')
		);
        $this->load->view('header');
        $this->load->view('home_page', $data);
        $this->load->view('footer');


    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */