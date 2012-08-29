<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home_controller extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Player_model','',TRUE);
        $this->load->helper('player_helper');
        $this->load->library('GameCreator');
        $this->load->model('Game_model', '', TRUE);
    }

    public function index()
    {
        // $is_logged_in = false;
        $is_logged_in = $this->tank_auth->is_logged_in();

        $tumblr_num_posts = $this->config->item('tumblr_num_posts');
        $tumblr_username = $this->config->item('tumblr_username');
        $twitter_search = $this->config->item('twitter_search');
        $twitter_hashtag = $this->config->item('twitter_hashtag');

        $userid = $this->tank_auth->get_user_id();

        $current_gameid = $this->Game_model->getCurrentGame();

        $num_players = $this->Player_model->getNumberOfPlayersInGame($current_gameid);
        $num_males = $this->Player_model->getNumberOfPlayersInGameByNVP($current_gameid,'gender','male');
        $num_females = $this->Player_model->getNumberOfPlayersInGameByNVP($current_gameid,'gender','female');
        $num_other_gender = $this->Player_model->getNumberOfPlayersInGameByNVP($current_gameid,'gender','other');
        $num_no_gender_response = $this->Player_model->getNumberOfPlayersInGameByNVP($current_gameid,'gender','');

        $game = $this->gamecreator->getGameByGameID($current_gameid);
        $name = $game->name();

        if($is_logged_in){
            $home_content = $this->load->view('home/logged_in_home','', true);
            $top_bar = $this->load->view('layouts/logged_in_topbar','', true);
            if(!userExistsInGame($userid, $current_gameid)){
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
            'twitter_search' =>  $twitter_search,
            'twitter_hashtag' =>  $twitter_hashtag,
            'game_name'        =>  $name
        );


        //load the content variables
        $layout_data['top_bar'] = $top_bar;
        $layout_data['content_body'] = $this->load->view('home/home_page', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data);
    }
}
