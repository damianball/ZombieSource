<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game_overview_controller extends CI_Controller {
  // var $logged_in_player;
  // var $logged_in_players_team;
    private $logged_in_player = null;
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
        $this->load->helper('game_helper');
        $this->load->helper('player_helper');
        

        // load the logged in player (if one exists) into the controller
        $userid = $this->tank_auth->get_user_id();
        if(userExistsInGame($userid, GAME_KEY)){
            $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
            if($player->isActive()){
                $this->logged_in_player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
            }   
        }
    
        // @TODO: what if team is null?
        // $teamid = $this->logged_in_player->current_team();      
        // $this->logged_in_players_team = new team($teamid);
    }

    public function index()
    {
        if(!$this->logged_in_player || !$this->logged_in_player->isActive()) {
            redirect("home");
        }
        $game_data = array();
        $games = $this->Game_model->getGameIDs();

        foreach($games as $game){
            $game_name = $this->Game_model->getGameName($game);
            $zombie_count = 0;
            $human_count = 0;
            $starved_zombie_count = 0;

            $players = getViewablePlayers($game);
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


            $game_data[$game] = array(
                          'game_name'             => $game_name,
                          'count'                 => $zombie_count + $human_count,
                          'human_count'           => $human_count,
                          'zombie_count'          => $zombie_count,
                          'starved_zombie_count'  => $starved_zombie_count
                        );
        }
        $data["game_data"] = $game_data;

        $layout_data = array();
        $player = $this->logged_in_player;
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game_overview/game_overview_page', $data, true); //$data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data);
        
                        
            
        
    }










}
