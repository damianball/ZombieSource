<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL);

class Game_controller extends CI_Controller {
    private $logged_in_player = null;
    public function __construct()
    {
        parent::__construct();
        if(!$this->tank_auth->is_logged_in()){
            redirect('/auth/login');
        }
        $this->load->model('Player_model','',TRUE);
        $this->load->library('PlayerCreator', null);
        $this->load->library('UserCreator', null);
        $this->load->library('TeamCreator', null);
        $this->load->library('GameCreator', null);
        $this->load->helper('game_helper');
        $this->load->helper('player_helper');
        $this->load->helper('team_helper');
        $this->load->helper('gravatar_helper');

        // load the logged in player (if one exists) into the controller
        $userid = $this->tank_auth->get_user_id();
        $user = $this->usercreator->getUserByUserID($userid);


        // $get = $this->uri->uri_to_assoc(1);
        // // @TODO: THIS IS PROBABLY A TERRIBLE IDEA
        // $game_name = $get['game'];
        // $teamid = $this->security->xss_clean($teamid);

        // if(url is a valid game){
            

        // }else{
        //     if($user->isActiveInCurrentGame()){
        //         redirect(current_game_url)
        //     }
        //     else{
        //         redirect("game/overview");
        //     }
        // }






        $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
        if($player->isActive()){
            $this->logged_in_player = $player;
        }   
        // $game = $this->gameCreator->getGameByGameID(GAME_KEY);
        
    }

    public function index()
    {
        if(!$this->logged_in_player || !$this->logged_in_player->isActive()) {
            redirect("home");
        }

        // you land on the game page
        //   if you aren't in any game you get redirected to game/overview
        //   if you are in a game you get redirected to game/:gameid

        // if((params == nul) && validGames(params)){
        //     redirect("game/".default);
        // }
        
        // if(userExistsInGame($userid, GAME_KEY)){
        //     $player -> $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
        // }else{
        //     redirect("game/overview");
        // }

        //load the content variables
        $this->table->set_heading(
        array('data' => 'Avatar'),
        array('data' => 'Player', 'class' => 'sortable'),
        array('data' => 'Team', 'class' => 'sortable'),
        array('data' => 'Status', 'class' => 'sortable'),
        array('data' => 'Kills', 'class' => 'sortable'),
        array('data' => 'Last Feed', 'class' => 'sortable'));

        # make the table bootstrap pretty! #
        $this->table->set_template(array('table_open' => '<table class="table table-striped" border="0" cellpadding="4" cellspacing="0" id="fd-table-1">'));

        $players = getViewablePlayers(GAME_KEY);
        $this->load->helper('date_helper');
        foreach($players as $player){
            $row = array(
                getGravatarHTML($player->getData('gravatar_email'), $player->getUser()->getEmail(), 50),
                getHTMLLinkToProfile($player),
                getHTMLLinkToPlayerTeam($player),
                $player->getPublicStatus(),
                (is_a($player, 'Zombie') ? $player->getKills() : null),
                (is_a($player, 'Zombie') ? getTimeStringFromSeconds($player->secondsSinceLastFeed()) : null)
            );
          $this->table->add_row($row);
        }

        //-- Display Table
        $game_table = $this->table->generate();     
        $data = array('game_table' => $game_table);
        $gameid = $player->getCurrentGameId();
        $game = $this->gamecreator->getGameByGameID($gameid);
        $data['game_name'] = $game->name();


        $layout_data = array();
        $layout_data['active_sidebar'] = 'playerlist';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game/game_page', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        #$this->load->view('layouts/game_layout', $layout_data);
        $this->load->view('layouts/main', $layout_data);
    }

    public function teams(){
        if(!$this->logged_in_player || !$this->logged_in_player->isActive()) {
            redirect("home");
        }

        $this->table->set_template(array('table_open' => '<table class="table table-striped" border="0" cellpadding="4" cellspacing="0" id="fd-table-1">'));

        $this->table->set_heading(
            array('data' => 'Avatar'),
            array('data' => 'Team', 'class' => 'sortable', 'class' => "medium_width_column"),
            array('data' => 'Size', 'class' => 'sortable')
        );

        $teams = getAllTeamsByGameID(GAME_KEY);
        foreach($teams as $team){
            $row = array(
                getGravatarHTML($team->getData('gravatar_email'), $team->getData('name'), 50),
                getHTMLLinkToTeamProfile($team),
                $team->getTeamSize()
            );
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
        #$this->load->view('layouts/game_layout', $layout_data);
        $this->load->view('layouts/main', $layout_data);
    }

    public function stats() {
        if(!$this->logged_in_player || !$this->logged_in_player->isActive()) {
            redirect("home");
        }

        # make the table bootstrap pretty! #
        $this->table->set_template(array('table_open' => '<table class="table table-striped" border="0" cellpadding="4" cellspacing="0" id="fd-table-1">'));

        //this should probably be done though the game library, whenever we write the game library. 
        // $num_players = $this->Player_model->getNumberOfPlayersInGame(GAME_KEY);
        // $num_males = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','male');
        // $num_females = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','female');
        // $num_other_gender = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','other');
        // $num_no_gender_response = $this->Player_model->getNumberOfPlayersInGameByNVP(GAME_KEY,'gender','');

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


        $layout_data = array();
        $layout_data['active_sidebar'] = 'stats';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game/game_stats',$data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        #$this->load->view('layouts/game_layout', $layout_data);
        $this->load->view('layouts/main', $layout_data);
    }

    public function register_kill() {
        if(!$this->logged_in_player || !$this->logged_in_player->canParticipate()) {
            redirect("home");
        }

        $userid = $this->tank_auth->get_user_id();
        $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
        if((is_a($player, 'Zombie') && !$player->canParticipate()) || !is_a($player, 'Zombie')) {
            $layout_data['active_sidebar'] = 'logkill';
            $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
            $layout_data['content_body'] = $this->load->view('helpers/display_generic_message', 
                                                            array("message"=>"Not eligible to tag a kill"), true);
            $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
            $this->load->view('layouts/main', $layout_data); 
        } else {
            $zombie = $player;

            $max_feeds = 3; // @TODO: hard coded for now
            for($i = 1; $i <= $max_feeds; $i++){
                $this->form_validation->set_rules('zombie_friend_'.$i, 'Zombie Friend '.$i, 'trim|xss_clean|min_length[4]|callback_validate_username');
            }
            $this->form_validation->set_rules('human_code', 'Human Code', 'trim|required|xss_clean|min_length[5]|max_length[5]|callback_validate_human_code');
            $this->form_validation->set_rules('claimed_tag_time_offset', 'Tag Time Offset', 'trim|xss_clean');
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            // on success, try to log the tag
            $form_error = '';
            if ($this->form_validation->run()) {
                $human_code = $this->input->post('human_code');
                $claimed_tag_time_offset = $this->input->post('claimed_tag_time_offset');
                if(playerExistsWithHumanCodeByGameID($human_code, GAME_KEY)){
                    $playerid = getPlayerIDByHumanCodeGameID($human_code, GAME_KEY);

                    // is the player an active human?
                    $player = $this->playercreator->getPlayerByPlayerID($playerid);
                    if(is_a($player, 'Human') && $player->canParticipate()){
                        $human = $player;
                        try{
                            $this->load->library('TagCreator');

                            $dateclaimed = null;
                            // generate time claimed offset
                            $maxseconds = 14400;
                            $minseconds = 0;
                            if($claimed_tag_time_offset && $claimed_tag_time_offset != '' && $claimed_tag_time_offset >= $minseconds && $claimed_tag_time_offset <= $maxseconds){
                                $dateclaimed = gmdate("Y-m-d H:i:s", time() - ($claimed_tag_time_offset));
                            }

                            $tag = $this->tagcreator->getNewTag($human, $zombie, $dateclaimed, null, null);
                            if($tag){
                                // remove human from any teams
                                // was human on team?
                                if($human->isMemberOfATeam()){
                                    $human->leaveCurrentTeam();
                                }

                                // feed the tagger
                                $this->load->library('FeedCreator');
                                $feed = $this->feedcreator->getNewFeed($zombie, $tag, $dateclaimed, null);

                                // feed friends
                                $this->load->helper('user_helper');
                                for($i = 1; $i <= $max_feeds; $i++){
                                    if(!$this->input->post('zombie_friend_'.$i) == ''){
                                        $friendUserID = getUserIDByUsername($this->input->post('zombie_friend_'.$i));
                                        if($friendUserID && $friendUserID != $zombie->getUser()->getUserID()){
                                            $friend = $this->playercreator->getPlayerByUserIDGameID($friendUserID, GAME_KEY);
                                            if(is_a($friend, 'Zombie') && $friend->canParticipate()){
                                                $feed = $this->feedcreator->getNewFeed($friend, $tag, $dateclaimed, null);
                                            }
                                        }
                                    }
                                }
                            }
                            // @TODO: a message would probably be nice :-)
                            redirect('game');
                        } catch (DatastoreException $e){
                            $form_error = $e->getMessage();
                        }
                    } else {
                        // PLAYER IS NOT A HUMAN OR ACTIVE
                        $this->loadGenericMessage('Cannot tag player with human code: '.$human_code);
                    }
                } else {
                    // HUMAN CODE DOES NOT EXIST ... NOW WHAT?
                    $this->loadGenericMessage('Human code does not exist: '.$human_code);
                }
            } else {
                $data['form_error'] = $form_error;
                $data['zombie_list'] = getActiveZombiesString(GAME_KEY);
                $data['max_feeds'] = $max_feeds;
    
                //display the regular page, with errors
                $layout_data['active_sidebar'] = 'logkill';
                $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
                $layout_data['content_body'] = $this->load->view('game/register_kill',$data, true);
                $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
                #$this->load->view('layouts/game_layout', $layout_data);
                $this->load->view('layouts/main', $layout_data);
            }
        }
    }

    private function loadGenericMessage($message){
        $data = array("message" => $message);
        $layout_data['active_sidebar'] = 'logkill';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('helpers/display_generic_message',$data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        #$this->load->view('layouts/game_layout', $layout_data);
        $this->load->view('layouts/main', $layout_data);
    }

    public function validate_human_code() {
        $this->form_validation->set_message('validate_human_code', 'The %s field did not validate.');
        return true;
    }

    public function validate_username($string){
        $this->form_validation->set_message('validate_username', '%s not a valid zombie. :-(');
        if($string == ''){
            return true;
        }
        $this->load->helper('user_helper');
        $userid = getUserIDByUsername($string);
        if($userid){
            $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
            if(is_a($player, 'Zombie') && $player->canParticipate()){
                return true;
            }
        }
        return false;
    }

    public function register_new_team(){
        if(!$this->logged_in_player || !$this->logged_in_player->canParticipate()) {
            redirect("home");
        }

        $userid = $this->tank_auth->get_user_id();
        $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);

        if(!is_a($player,'Human')){
            $layout_data['active_sidebar'] = 'logkill';
            $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
            $layout_data['content_body'] = $this->load->view('helpers/display_generic_message', 
                                            array("message" => "Sorry, zombies cannot do that"), true);
            $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
            $this->load->view('layouts/main', $layout_data); 
        } else {
        
            $this->form_validation->set_rules('team_name', 'Team Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('team_gravatar_email', 'Gravatar Email', 'email|trim|xss_clean');
            $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean');
        
            if ($this->form_validation->run()) {
				$data['message'] = '';
				if($player->isMemberOfATeam()){
					$currentTeam = $this->teamcreator->getTeamByTeamID($player->getTeamID());
					$player->leaveCurrentTeam();
					$currentTeamLink = getHTMLLinkToTeam($currentTeam);
					$data['message'] = "Successfully left " . $currentTeamLink;
				}
			
                // save the data
                $name = $this->input->post('team_name');
                $gravatar_email = $this->input->post('team_gravatar_email');
                $description = $this->input->post('description');
            
                $team = $this->teamcreator->createNewTeamWithPlayer($name, $player);
				$newTeamLink = getHTMLLinkToTeam($team);
                $team->setData('gravatar_email', $gravatar_email);
                $team->setData('description', $description);
				
				// @TODO: We should use these messages!
				$data['message'] .= " and joined " . $newTeamLink;
            
                redirect("team/".$team->getTeamID());
            }
        
            //display the regular page, with errors
            $layout_data['active_sidebar'] = 'logkill';
            $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
            $layout_data['content_body'] = $this->load->view('game/register_new_team', '', true);
            $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
            $this->load->view('layouts/main', $layout_data);
        } 
    }

    public function join_team(){
        if(!$this->logged_in_player || !$this->logged_in_player->canParticipate()) {
            redirect("home");
        }

        $data = array();
        $userid = $this->tank_auth->get_user_id();
        $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);

        if(!is_a($player,'Human')){
            $layout_data['active_sidebar'] = 'logkill';
            $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
            $layout_data['content_body'] = $this->load->view('helpers/display_generic_message', 
                                            array("message" => "Sorry, zombies cannot do that"), true);
            $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
            $this->load->view('layouts/main', $layout_data); 
        } else {
            $teamid = $this->input->post('teamid');
            $data['teamid'] = $teamid;
            $newTeam = $this->teamcreator->getTeamByTeamID($teamid);
            $newTeamLink = getHTMLLinkToTeam($newTeam);
        
            if($player->isMemberOfATeam()){
                $currentTeam = $this->teamcreator->getTeamByTeamID($player->getTeamID());
                $player->leaveCurrentTeam();
                $newTeam->addPlayer($player);
                $currentTeamLink = getHTMLLinkToTeam($currentTeam);
                $data['message'] = "Successfully left " . $currentTeamLink . " and joined " . $newTeamLink;
              
            }else{
                $newTeam->addPlayer($player);
                $data['message'] = "Successfully joined " . $newTeamLink;
            }
        
            $layout_data['active_sidebar'] = 'logkill';
            $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
            $layout_data['content_body'] = $this->load->view('helpers/display_generic_message', $data, true);
            $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
            $this->load->view('layouts/main', $layout_data); 
        
            // @TODO: get old team
        
            // @TODO: check for size limit on incoming team (game_setting)
        }

    }

    public function leave_team(){
        if(!$this->logged_in_player || !$this->logged_in_player->canParticipate()) {
            redirect("home");
        }

        $userid = $this->tank_auth->get_user_id();
        $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
        $teamid = $this->input->post('teamid');
        $team = $this->teamcreator->getTeamByTeamID($teamid);
        $teamLink = getHTMLLinkToTeam($team);
        if($player->isMemberOfTeam($team->getTeamID())){
            $player->leaveCurrentTeam();
            $data['message'] = "Successfully left " . $teamLink;
        }else{
            $data['message'] = "You are not a member of " . $teamLink;
        }
    
        $layout_data['active_sidebar'] = 'logkill';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('helpers/display_generic_message', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data); 

    }

    public function overview(){
   
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game/overview','', true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);   
        $this->load->view('layouts/main', $layout_data); 
    }

}


