<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_controller extends CI_Controller {

    private $logged_in_user;
    private $current_gameid;

    public function __construct()
    {
        parent::__construct();
        if(!$this->tank_auth->is_logged_in()){
            redirect('/auth/login');
        }
        $this->load->model('Player_model','',TRUE);
        $this->load->model('Team_model','',TRUE);
        $this->load->model('Game_model', '', TRUE);
        $this->load->library('PlayerCreator', null);
        $this->load->library('UserCreator', null);
        $this->load->library('GameCreator', null);
        $this->load->library('TeamCreator', null);
        $this->load->helper('game_helper');
        $this->load->helper('tag_helper');

        $this->current_gameid = $this->Game_model->getCurrentGame();
        $userid = $this->tank_auth->get_user_id();
        $this->user = $this->usercreator->getUserByUserID($userid);
        $this->players = $this->user->getModeratorPlayers();
         if(!$this->players){
             redirect('/home');
         }
    }


	public function index(){
       //is mod check
        $data['player_in_game'] = array();
        foreach($this->players as $player){
            $gameid = $player->getGameID();
            $game = $this->gamecreator->getGameByGameID($gameid);
            $game_name = $game->name();
            $data['player_in_game'][$gameid] = getPlayerString($gameid);
            $data['game_names'][$gameid] = $game_name;
        }

        $layout_data = array();
        $layout_data['active_sidebar'] = 'playerlist';
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('admin/admin_page', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data);
	}

    public function player_controls(){
        $username = $this->input->post('player');
        $gameid = $this->input->post('gameid');
        try{
            $userid = getUserIDByUsername($username);
            $player = $this->playercreator->getPlayerByUserIDGameID($userid, $gameid);
            $data = getPrivatePlayerProfileDataArray($player);

            $is_mod = ($player->getData('moderator') == "1");
            $data['toggle_mod_to'] = $is_mod ? "0" : "1";
            $data['moderator_button_text'] = $is_mod ? "Remove Moderator" : "Make Moderator";
            if(is_a($player, 'zombie')){
                $data['feed_disabled'] = "";
                $data['feed_message'] = "";

                if($player->isElligibleForTagUndo()){
                    $this->load->library('TagCreator');
                    $tagid = getInitialTagIDByPlayer($player);
                    $tag = $this->tagcreator->getTagByTagID($tagid);
                    $tagger_name = $tag->getTagger()->getUser()->getUsername();
                    $taggee_name = $player->getUser()->getUsername();

                    //spent 30 min trying to convert utc datetime to current 12 hour PST time to show the tag time and gave up due to time constraints.
                    //probably need a time helper.
                    $message = "TAG: <h3> $tagger_name </h3> tagged <h3> $taggee_name </h3>";

                    $data['undo_tag_disabled'] = "";
                    $data['undo_tag_message'] = $message;
                }else{ //is a zombie but can't be untaggged
                    $data['undo_tag_disabled'] = "disabled";
                    $data['undo_tag_message'] = "Zombie not elligble to be untagged";
                }
            }else{ //is not a zombie, can't be feed or untagged
                $data['feed_disabled'] = "disabled";
                $data['feed_message'] = "Not a zombie";
                $data['undo_tag_disabled'] = "disabled";
                $data['undo_tag_message'] = "Not a zombie";
            }

            $this->load->view('admin/player_controls.php', $data);
        }catch (PlayerDoesNotExistException $e){
            $this->loadGenericMessageWithoutLayout("Player Not Found");
        }
    }

    public function undo_tag(){
        $this->load->library('TagCreator');
        //is mod check
        $player = $this->playercreator->getPlayerByPlayerID($this->input->post('player'));
        $username = $player->getUser()->getUsername();
        if($player->isElligibleForTagUndo()){ //just to be sure

            $tagid = getInitialTagIDByPlayer($player);
            $tag = $this->tagcreator->getTagByTagID($tagid);

            //the important part of this method.
            $tag->invalidate();
            if($tag->isInvalid()){
                $this->loadGenericMessageWithoutLayout("Success! Tag invalidated");
                // event logging
                $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('admin_unto_tag','succeeded');
                $analyticslogger->addToPayload('admin_playerid',$this->logged_in_user->getPlayerID());
                $analyticslogger->addToPayload('tagged_playerid', $player->getPlayerID());
                LogManager::storeLog($analyticslogger);
            }else{
                $this->loadGenericMessageWithoutLayout("$username is a Zombie still, something went wrong : /");
                // event logging
                $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('admin_unto_tag','failed');
                $analyticslogger->addToPayload('admin_playerid',$this->logged_in_user->getPlayerID());
                $analyticslogger->addToPayload('tagged_playerid', $player->getPlayerID());
                LogManager::storeLog($analyticslogger);
            }
        }
    }

    public function free_feed(){
        //mod
        $this->load->library('FeedCreator');
        $player = $this->playercreator->getPlayerByPlayerID($this->input->post('player'));
        $username = $player->getUser()->getUsername();
        if($player->getStatus() == 'zombie'){ //is_a Zombie scares me, I don't know how it works. So I check the status.
            //the important part of this method.
            $feed = $this->feedcreator->getNewFeed($player, null, gmdate("Y-m-d H:i:s", time()), true);
            if ($feed) {
                $this->loadGenericMessageWithoutLayout("Success! $username has been fed");
                // event logging
                $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('admin_free_feed','succeeded');
                $analyticslogger->addToPayload('admin_playerid',$this->logged_in_user->getPlayerID());
                $analyticslogger->addToPayload('feed_playerid', $player->getPlayerID());
                LogManager::storeLog($analyticslogger);
            } else {
                $this->loadGenericMessageWithoutLayout("Something went wrong, $username may not have been fed");
                //event logging
                $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('admin_free_feed','failed');
                $analyticslogger->addToPayload('admin_playerid',$this->logged_in_user->getPlayerID());
                $analyticslogger->addToPayload('feed_playerid', $player->getPlayerID());
                $analyticslogger->addToPayload('message', 'feed not generated');
                LogManager::storeLog($analyticslogger);
            }
        } else {
            $this->loadGenericMessageWithoutLayout("$username is not a zombie!");
        }
        // event logging
        $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('admin_free_feed','failed');
        $analyticslogger->addToPayload('admin_playerid',$this->logged_in_user->getPlayerID());
        $analyticslogger->addToPayload('feed_playerid', $player->getPlayerID());
        $analyticslogger->addToPayload('message', 'not a zombie');
        LogManager::storeLog($analyticslogger);
    }

    public function make_mod(){
        //mod
        $player = $this->playercreator->getPlayerByPlayerID($this->input->post('player'));
        $username = $player->getUser()->getUsername();
        $was_moderator = $player->isModerator();

        $player->toggleModerator();
        if($was_moderator == $player->isModerator()){
            // echo the button text to be applied via AJAX
            if($was_moderator){
                echo 'Make Moderator';
            } else {
                echo 'Remove Moderator';
            }
        } else {
            echo 'Error';
        }
    }

    public function email_list(){
        $get = $this->uri->uri_to_assoc(2);
        // @TODO: THIS IS PROBABLY A TERRIBLE IDEA
        $type = $get['email_list'];
        $type = $this->security->xss_clean($type);

        if ($type == 'all') {
            $players = getViewablePlayers($current_gameid);
        } else if ($type == 'human') {
            $players = getCanParticipateHumans($current_gameid);
        } else if ($type == 'zombie') {
            $players = getCanParticipateZombies($current_gameid);
        } else {
            // @TODO: Should be an error
            return null;
        }

        $output = '';
        foreach($players as $player){
            $output .= $player->getUser()->getEmail() . ", ";
        }
        $this->output->set_content_type('application/json')->set_output($output);

        // event logging
        $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('admin_email_list','displayed');
        $analyticslogger->addToPayload('playerid',$this->logged_in_user->getPlayerID());
        LogManager::storeLog($analyticslogger);
    }

    public function human_list(){
        $players = getCanParticipateHumans($current_gameid);
        foreach($players as $player){
            $human_names[] = $player->getUser()->getUsername();
        }

        $data['human_names'] = $human_names;
        $this->load->view('helpers/human_list', $data);
    }

    //Duplicated and modified from Game.php because I'm not sure how to load views from a helper
    private function loadGenericMessageWithoutLayout($message){
        $data = array("message" => $message);
        $this->load->view('helpers/display_generic_message',$data);
    }
}

