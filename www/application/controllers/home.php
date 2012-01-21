<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    public function index()
    {
        // $is_logged_in = false;
        $is_logged_in = $this->tank_auth->is_logged_in();

        $this->load->model('Player_model','',TRUE);
        $user_id = $this->tank_auth->get_user_id();

        $player_id = $this->Player_model->getPlayerID($user_id,GAME_KEY);
        $waiver = $this->Player_model->getPlayerData($player_id,'waiver_is_signed');
        $num_players = $this->Player_model->getNumberOfPlayersInGame(GAME_KEY);
        $num_males = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','male');
        $num_females = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','female');
        $num_other_gender = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','other');
        $num_no_gender_response = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','');

        if($is_logged_in){
            $home_content = $this->load->view('home/logged_in_home','', true);
            $top_bar = $this->load->view('layouts/logged_in_topbar','', true);
            if(!$waiver){
              $home_banner = $this->load->view('home/waiver_banner','', true);
            }
            else{
              $home_banner = $this->load->view('home/beta_banner','', true);
            }
        }
        else{ 
            $home_content = $this->load->view('home/logged_out_home','', true); 
            $top_bar = $this->load->view('layouts/logged_out_topbar','', true);
            $home_banner = $this->load->view('home/beta_banner','', true);
        }

        $data = array('waiver'       => $waiver,
                      'count'        => $num_players,
			                'male'         => $num_males,
			                'female'       => $num_females,
			                'other'        => $num_other_gender,
			                'noresponse'   => $num_no_gender_response,
                      'home_banner'  => $home_banner,
                      'home_content' => $home_content

		    );

         //load the content variables
         $layout_data['top_bar'] = $top_bar;
         $layout_data['content_body'] = $this->load->view('home_page', $data, true);
         $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
         $this->load->view('layouts/main', $layout_data);
    }
 }