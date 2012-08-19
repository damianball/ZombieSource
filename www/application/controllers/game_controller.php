<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL);

class game_controller extends CI_Controller {
    private $user;
    private $game;
    private $player;
    public function __construct()
    {
        parent::__construct();
        if(!$this->tank_auth->is_logged_in()){
            redirect('/auth/login');
        }
        $this->load->model('Player_model','',TRUE);
        $this->load->model('Game_model','',TRUE);
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
        $this->user = $this->usercreator->getUserByUserID($userid);

        // @TODO: possibly not safe.
        $get = $this->uri->uri_to_assoc(1);
        $game_slug = $this->security->xss_clean($get['game']);

        if(!validGameSlug($game_slug)){
            if($this->user->isActiveInCurrentGame()){
                redirect("/game/".$this->Game_model->getGameSlugByGameID($this->user->currentGameID()));
            }
            else{
                redirect("/overview");
            }
        }

        $this->game = $this->gamecreator->getGameByGameID($this->Game_model->getGameIDBySlug($game_slug));
        try {
            $this->player = $this->playercreator->getPlayerByUserIDGameID($userid, $this->game->getGameID());
        } catch (PlayerDoesNotExistException $e){
            // this is okay. it means this user isn't in the current game
            $this->player = NULL;
        }

    }

    public function index()
    {
        $is_player_in_game = $this->user->isActiveInGame($this->game->getGameID());
        //load the content variables
        $this->table->set_heading(
        array('data' => 'Avatar'),
        array('data' => 'Player'),
        array('data' => 'Team'),
        array('data' => 'Status'),
        array('data' => 'Kills'),
        array('data' => 'Last Feed'));

        # make the table bootstrap pretty! #
        $this->table->set_template(array('table_open' => '<table id="game_table" class="table table-striped tablesorter" border="0" cellpadding="4" cellspacing="0">'));

        $players = getViewablePlayers($this->game->getGameID());
        $this->load->helper('date_helper');
        foreach($players as $player){
            $user = $player->getUser();
            $row = array(
                getGravatarHTML($user->getData('gravatar_email'), $user->getEmail(), 50),
                getHTMLLinkToProfile($player),
                getHTMLLinkToPlayerTeam($player),
                $player->getPublicStatus(),
                (is_a($player, 'Zombie') ? $player->getKills() : null),
                (is_a($player, 'Zombie') ? getTimeStringFromSeconds($player->secondsSinceLastFeedOrGameEnd()): null)
            );
          $this->table->add_row($row);
        }

        //-- Display Table
        $game_table = $this->table->generate();
        $data = array('game_table' => $game_table);

        $data['is_player_in_game'] = $is_player_in_game;
        $data['game_name'] = $this->game->name();
        $data['url_slug'] = $this->game->slug();
        $data['is_closed'] = $this->game->isClosedGame();
        $data['is_zombie'] = !is_null($player) && $player->isActiveZombie();

        $layout_data = array();
        $layout_data['active_sidebar'] = 'playerlist';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game/game_page', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        #$this->load->view('layouts/game_layout', $layout_data);
        $this->load->view('layouts/main', $layout_data);
    }

    public function teams(){
        $is_player_in_game = $this->user->isActiveInGame($this->game->getGameID());

        $this->table->set_template(array('table_open' => '<table id="teams_table" class="table table-striped" border="0" cellpadding="4" cellspacing="0">'));

        $this->table->set_heading(
            array('data' => 'Avatar'),
            array('data' => 'Team', 'class' => 'sortable', 'class' => "medium_width_column"),
            array('data' => 'Size', 'class' => 'sortable')
        );

        $teams = getAllTeamsByGameID($this->game->getGameID());
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
        $data["is_player_in_game"] = $is_player_in_game;
        $data["url_slug"] = $this->game->slug();
        $data["game_name"] = $this->game->name();
        $data['is_closed'] = $this->game->isClosedGame();
        $data['is_zombie'] = !is_null($this->player) && $this->player->isActiveZombie();
        $data['is_human'] = !is_null($this->player) && $this->player->isActiveHuman();

        $layout_data = array();
        $layout_data['active_sidebar'] = 'teamlist';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game/team_page', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        #$this->load->view('layouts/game_layout', $layout_data);
        $this->load->view('layouts/main', $layout_data);
    }

    public function stats() {
        $is_player_in_game = $this->user->isActiveInGame($this->game->getGameID());

        # make the table bootstrap pretty! #
        $this->table->set_template(array('table_open' => '<table class="table table-striped" border="0" cellpadding="4" cellspacing="0" id="fd-table-1">'));

        //this should probably be done though the game library, whenever we write the game library.
        // $num_players = $this->Player_model->getNumberOfPlayersInGame($this->current_game_id);
        // $num_males = $this->Player_model->getNumberOfPlayersInGameByNVP($this->current_game_id,'gender','male');
        // $num_females = $this->Player_model->getNumberOfPlayersInGameByNVP($this->current_game_id,'gender','female');
        // $num_other_gender = $this->Player_model->getNumberOfPlayersInGameByNVP($this->current_game_id,'gender','other');
        // $num_no_gender_response = $this->Player_model->getNumberOfPlayersInGameByNVP($this->current_game_id,'gender','');

        $zombie_count = 0;
        $human_count = 0;
        $starved_zombie_count = 0;

        $players = getViewablePlayers($this->game->getGameID());
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
        $data['is_player_in_game'] = $is_player_in_game;
        $data['url_slug'] = $this->game->slug();
        $data['is_closed'] = $this->game->isClosedGame();
        $data['game_name'] = $this->game->name();
        $data['is_zombie'] = !is_null($player) && $player->isActiveZombie();

        $layout_data = array();
        $layout_data['active_sidebar'] = 'stats';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game/game_stats',$data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        #$this->load->view('layouts/game_layout', $layout_data);
        $this->load->view('layouts/main', $layout_data);
    }

    public function register_kill() {


        $userid = $this->tank_auth->get_user_id();
        $player = $this->playercreator->getPlayerByUserIDGameID($userid, $this->game->getGameID());
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
                if(playerExistsWithHumanCodeByGameID($human_code, $this->game->getGameID())){
                    $playerid = getPlayerIDByHumanCodeGameID($human_code, $this->game->getGameID());

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
                                            $friend = $this->playercreator->getPlayerByUserIDGameID($friendUserID, $this->game->getGameID());
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
                $data['zombie_list'] = getActiveZombiesString($this->game->getGameID());
                $data['max_feeds'] = $max_feeds;
                $data['url_slug'] = $this->game->slug();
                $data['is_zombie'] = !is_null($player) && $player->isActiveZombie();

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
            $player = $this->playercreator->getPlayerByUserIDGameID($userid, $this->game->getGameID());
            if(is_a($player, 'Zombie') && $player->canParticipate()){
                return true;
            }
        }
        return false;
    }

    public function register_new_team(){

        $userid = $this->tank_auth->get_user_id();
        $player = $this->playercreator->getPlayerByUserIDGameID($userid, $this->game->getGameID());

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
        $data = array();
        $userid = $this->tank_auth->get_user_id();
        $player = $this->playercreator->getPlayerByUserIDGameID($userid, $this->game->getGameID());

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

        $userid = $this->tank_auth->get_user_id();
        $player = $this->playercreator->getPlayerByUserIDGameID($userid, $this->game->getGameID());
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

}


