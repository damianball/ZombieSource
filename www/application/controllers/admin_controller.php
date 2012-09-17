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
        $this->load->model('User_model', '', TRUE);
        $this->load->library('PlayerCreator', null);
        $this->load->library('UserCreator', null);
        $this->load->library('GameCreator', null);
        $this->load->library('TeamCreator', null);
        $this->load->helper('game_helper');
        $this->load->helper('tag_helper');

        $this->current_gameid = $this->Game_model->getCurrentGame();
        $userid = $this->tank_auth->get_user_id();
        $this->user = $this->logged_in_user = $this->usercreator->getUserByUserID($userid);
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
        $human_code = $this->input->post('human_code');
        $gameid = $this->input->post('gameid');
        try{
            try{
                $userid = getUserIDByUsername($username);
            } catch(UnexpectedValueException $e){
                try{
                    $playerid = getPlayerIDByHumanCodeGameID($human_code, $gameid);
                } catch(UnexpectedValueException $e){
                    throw new PlayerDoesNotExistException('Username and human code were empty');
                } catch(InvalidHumanCodeException $e ){
                    throw new PlayerDoesNotExistException('Username and human code were empty');
                };
            }
            try{
                if(isset($userid)){
                    $player = $this->playercreator->getPlayerByUserIDGameID($userid, $gameid);
                } else if(isset($playerid)){
                    $player = $this->playercreator->getPlayerByPlayerID($playerid);
                }
            } catch(InvalidParametersException $e){
                throw new PlayerDoesNotExistException('Player does not exist');
            }
            $data = getPrivatePlayerProfileDataArray($player);

            $is_mod = ($player->getData('moderator') == "1");
            $data['toggle_mod_to'] = $is_mod ? "0" : "1";
            $data['moderator_button_text'] = $is_mod ? "Remove Moderator" : "Make Moderator";
            $data['status'] = $player->getStatus();
            $is_active = $player->isActiveState();
            $data['active_button_text'] = $is_active ? "Deactivate Player" : "Activate Player";
            if($player->isActive() && $player->getStatus() == 'zombie'){
                $data['feed_disabled'] = "";
                $data['feed_message'] = "";

                if($player->isElligibleForTagUndo()){
                    $this->load->library('TagCreator');
                    $tagid = getInitialTagIDByPlayer($player);
                    $tag = $this->tagcreator->getTagByTagID($tagid);
                    $tagger_name = $tag->getTagger()->getUser()->getUsername();
                    $taggee_name = $player->getUser()->getUsername();

                    //probably need a time helper.
                    $message = "<b> $tagger_name </b> tagged <b> $taggee_name </b>";

                    $data['undo_tag_disabled'] = "";
                    $data['undo_tag_message'] = $message;
                }else{ //is a zombie but can't be untaggged
                    $data['undo_tag_disabled'] = "disabled";
                    $data['undo_tag_message'] = "Zombie not elligble to be untagged";
                }
            }else{ //is not a zombie, can't be feed or untagged
                if($player->isActive() && $player->getPublicStatus() == 'human'){
                    $data['human_code'] = $player->getHumanCode();
                }
                $data['feed_disabled'] = "disabled";
                $data['feed_message'] = "Not an active zombie";
                $data['undo_tag_disabled'] = "disabled";
                $data['undo_tag_message'] = "Not an active zombie";
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

                $taggee = $tag->getTaggee();
                $old_team_id = $taggee->getFormerTeam();
                if($old_team_id && !$taggee->isMemberOfATeam()){
                    $old_team = $this->teamcreator->getTeamByTeamID($old_team_id);
                    $old_team->unRemovePlayer($taggee);

                    $diff = strtotime($tag->getTagDateTime()) - strtotime($old_team->leaveTime());
                    if($diff<=60 ){
                        $old_team->unRemovePlayer($taggee);
                    }
                }

                // regenerate zombie tree


                $this->load->helper('tree_helper');
                writeZombieTreeJSONByGameID($player->getGameID());
                $this->loadGenericMessageWithoutLayout("Success! Tag invalidated");
                // event logging
                $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('admin_unto_tag','succeeded');
                $adminplayer = $this->playercreator->getPlayerByUserIDGameID($this->logged_in_user->getUserID(), $player->getGameID());
                $analyticslogger->addToPayload('admin_playerid',$adminplayer->getPlayerID());
                $analyticslogger->addToPayload('tagged_playerid', $player->getPlayerID());
                LogManager::storeLog($analyticslogger);
            }else{
                $this->loadGenericMessageWithoutLayout("$username is a Zombie still, something went wrong : /");
                // event logging
                $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('admin_unto_tag','failed');
                $adminplayer = $this->playercreator->getPlayerByUserIDGameID($this->logged_in_user->getUserID(), $player->getGameID());
                $analyticslogger->addToPayload('admin_playerid',$adminplayer->getPlayerID());
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

    public function change_active(){
        //mod
        $player = $this->playercreator->getPlayerByPlayerID($this->input->post('player'));
        $was_active = $player->isActive();

        $player->toggleActive();
        if($was_active !== $player->isActiveState()){
            // echo the button text to be applied via AJAX
            if($was_active){
                echo 'Activate Player';
            } else {
                echo 'Deactivate Player';
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
        $game = $this->gamecreator->getGameByGameID('0b84d632-da0e-11e1-a3a8-5d69f9a5509e');

        if ($type == 'all') {
            $list = $this->Game_model->emailListFall2012();
        } else if ($type == 'humans') {
            $list = $game->getHumanEmails();
        } else if ($type == 'zombies') {
            $list = $game->getZombieEmails();
        } else {
            // @TODO: Should be an error
            return null;
        }

        $output = '';
        foreach($list as $email){
            $output .= $email . ", ";
        }
        $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('admin_email_list','displayed');
        $analyticslogger->addToPayload('userid',$this->logged_in_user->getUserID());
        LogManager::storeLog($analyticslogger);

        $this->output->set_content_type('application/json')->set_output($output);
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

    public function regenerate_zombie_tree(){
        $gameid = $this->input->post('gameid');
        $this->load->helper('tree_helper');
        $bytes = writeZombieTreeJSONByGameID($gameid);
        $message = $bytes ? "Success" : "An error occurred";
        $this->loadGenericMessageWithoutLayout($message);
    }

    public function check_missed_achievements(){
        $gameid = $this->input->post('gameid');
        $this->load->library('AchievementCreator');
        $ach = $this->achievementcreator->getAchievement();
        $num_new = $ach->backgenerate();
        $this->loadGenericMessageWithoutLayout("Added $num_new achievements");
    }
}

