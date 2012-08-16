<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_controller extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Player_model','',TRUE);
        $this->load->helper('player_helper');
        $this->load->library('GameCreator');
    }

    public function index()
    {
        // $is_logged_in = false;
        $is_logged_in = $this->tank_auth->is_logged_in();

        $tumblr_num_posts = $this->config->item('tumblr_num_posts');
        $tumblr_username = $this->config->item('tumblr_username');

        $userid = $this->tank_auth->get_user_id();

        $num_players = $this->Player_model->getNumberOfPlayersInGame(GAME_KEY);
        $num_males = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','male');
        $num_females = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','female');
        $num_other_gender = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','other');
        $num_no_gender_response = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','');

        $game = $this->gamecreator->getGameByGameID(GAME_KEY);
        $name = $game->name();

        if($is_logged_in){
            $home_content = $this->load->view('home/logged_in_home','', true);
            $top_bar = $this->load->view('layouts/logged_in_topbar','', true);
            if(!userExistsInGame($userid, GAME_KEY)){
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

        $data = array(
            'count'        => $num_players,
            'male'         => $num_males,
            'female'       => $num_females,
            'other'        => $num_other_gender,
            'noresponse'   => $num_no_gender_response,
            'home_banner'  => $home_banner,
            'home_content' => $home_content,
            'tumblr_username'  =>  $tumblr_username,
            'tumblr_num_posts' =>  $tumblr_num_posts,
            'game_name'        =>  $name
        );


        //load the content variables
        $layout_data['top_bar'] = $top_bar;
        $layout_data['content_body'] = $this->load->view('home/home_page', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data);
    }
}
