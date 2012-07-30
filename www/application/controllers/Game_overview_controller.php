<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game_overview_controller extends CI_Controller {

    private $user = null;

    public function __construct()
    {
        parent::__construct();
        if(!$this->tank_auth->is_logged_in()){
          redirect('/auth/login');
        }
    
        $this->load->model('Game_model','',TRUE);
        $this->load->model('Player_model','',TRUE);
        $this->load->library('GameCreator');
        $this->load->library('PlayerCreator');
        $this->load->library('UserCreator');
        $this->load->helper('game_helper');
        $this->load->helper('player_helper');
        
        // load the logged in player (if one exists) into the controller
        $this->user = $this->usercreator->getUserByUserID($this->tank_auth->get_user_id());
    
    }

    public function index()
    {
        $zombie_count = 0;
        $human_count = 0;
        $starved_zombie_count = 0;

        $players = getViewablePlayers(GAME_KEY);
        foreach($players as $player){
                if(is_a($player, 'Zombie')){
                    if($player->isStarved()){
                      $starved_zombie_count += 1;
                    }else{
                      $zombie_count += 1;    
                    }
                }else {
                    $human_count += 1;
                }

        }

        $data = array(
                      'count'                 => $zombie_count + $human_count,
                      'human_count'           => $human_count,
                      'zombie_count'          => $zombie_count,
                      'starved_zombie_count'  => $starved_zombie_count
        );

        $gameid = $this->user->currentGameID();
        if($gameid){
            $game = $this->gamecreator->getGameByGameID($gameid);
            $data["current_game"] = $game->name();
        }else{
            $data["current_game"] = "Not in a game!";
        }


        $layout_data = array();
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game_overview/game_overview_page', $data, true); //$data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data);
        
                        
    }

    public function join_game()
    {
        $gameid = $username = $this->input->post('gameid');
        echo json_encode($this->user->joinGame($gameid));
    }

    public function leave_game()
    {
        $gameid = $username = $this->input->post('gameid'); 
        echo json_encode($this->user->leaveGame($gameid));
    }









}
