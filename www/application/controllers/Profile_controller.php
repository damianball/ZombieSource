<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if(!$this->tank_auth->is_logged_in()){
          redirect('/auth/login');
        }

        $this->load->model('Player_model','',TRUE);
        $this->load->library('PlayerCreator');
        $this->load->library('UserCreator');
        $this->load->library('TeamCreator');
        $this->load->helper('player_helper');
        $this->load->helper('team_helper');
        $this->load->helper('user_helper');

        // load the logged in player (if one exists) into the controller
        $userid = $this->tank_auth->get_user_id();
        $user = $this->usercreator->getUserByUserID($userid);
        $this->logged_in_user = $user;

        // @TODO: what if team is null?
        // $teamid = $this->logged_in_player->current_team();
        // $this->logged_in_players_team = new team($teamid);
    }

    public function index()
    {
        //if($this->logged_in_player){
            $data = getPrivateUserProfileDataArray($this->logged_in_user);
            $current_gameid = $this->logged_in_user->currentGameID();
            if($current_gameid){
                $userid = $this->logged_in_user->getUserID();
                $player = $this->playercreator->getPlayerByUserIDGameID($userid, $current_gameid);
                $data += getPrivatePlayerProfileDataArray($player);
            } else {
                // fill in defaults if user not in game
                $data['game_name'] = 'none';
                $data['status'] = '(not in game)';
                $data['link_to_team'] = '';
            }
            $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
            $layout_data['content_body'] = $this->load->view('profile/profile_page', $data, true);
            $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
            $this->load->view('layouts/main', $layout_data);
        //} else {
            //$this->form_validation->set_rules('waiver', 'Waiver', 'trim|required|xss_clean');
            //$this->form_validation->set_rules('sig', 'Signature', 'trim|required|xss_clean');
            //$this->form_validation->set_rules('age', 'Age', 'integer|trim|xss_clean');
            //$this->form_validation->set_rules('gender', 'Gender', 'trim|xss_clean');
            //$this->form_validation->set_rules('major', 'Major', 'trim|xss_clean');
            //$this->form_validation->set_rules('originalzombiepool', 'OriginalZombiePool', 'trim|xss_clean');

            //if ($this->form_validation->run()) {
                ////store data
                //$params =   array('waiver_is_signed' => "TRUE",
                                //'sig' => $this->input->post('sig'),
                                //'age' => $this->input->post('age'),
                                //'gender' => $this->input->post('gender'),
                                //'major' => $this->input->post('major'),
                                //'OriginalZombiePool' => $this->input->post('originalzombiepool')
                            //);
                //$userid = $this->tank_auth->get_user_id();
                //$player = $this->playercreator->createPlayerByJoiningGame($userid, GAME_KEY, $params);
                //redirect('profile');
            //} else {
                //$layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
                //$layout_data['content_body'] = $this->load->view('auth/registration_page_two', '', true);
                //$layout_data['footer'] = $this->load->view('layouts/footer', '', true);
                //$this->load->view('layouts/main', $layout_data);
            //}
        //}
    }

    public function edit_profile()
    {
        $user = $this->logged_in_user;
        $this->form_validation->set_rules('age', 'Age', 'integer|trim|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|xss_clean');
        $this->form_validation->set_rules('major', 'Major', 'trim|xss_clean');
        $this->form_validation->set_rules('gravatar_email', 'Gravatar Email', 'trim|xss_clean|valid_email');

        if ($this->form_validation->run()) {
            //save the data
            if ($user->getData('age') != $this->input->post('age')) {
                $user->saveData('age',$this->input->post('age'));
            }
            if ($user->getData('gender') != $this->input->post('gender')) {
                $user->saveData('gender',$this->input->post('gender'));
            }
            if ($user->getData('major') != $this->input->post('major')) {
                $user->saveData('major',$this->input->post('major'));
            }
            if ($user->getData('gravatar_email') != $this->input->post('gravatar_email')) {
                $user->saveData('gravatar_email',$this->input->post('gravatar_email'));
            }
            redirect("profile");
        }

        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('profile/edit_profile', getPrivateUserProfileDataArray($user), true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data);
    }


    public function public_profile()
    {
        // @TODO: potentially unsafe
        $get = $this->uri->uri_to_assoc(1);
        $userid = $get['user'];
        $userid = $this->security->xss_clean($userid);
        $user = $this->usercreator->getUserByUserID($userid);
        $data = getPublicUserProfileDataArray($user);
        $current_gameid = $user->currentGameID();
        if($userid == $this->logged_in_user->getUserID()){
            redirect("profile");
        }
        if($current_gameid){
            $player = $this->playercreator->getPlayerByUserIDGameID($userid, $current_gameid);
            $data += getPublicPlayerProfileDataArray($player);
        } else {
            // fill in defaults if user not in game
            $data['game_name'] = 'none';
            $data['status'] = '(not in game)';
            $data['link_to_team'] = '';
        }

        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('profile/public_profile', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data);
    }

    public function team_public_profile()
    {
        $get = $this->uri->uri_to_assoc(1);
        // @TODO: THIS IS PROBABLY A TERRIBLE IDEA
        $teamid = $get['team'];
        $teamid = $this->security->xss_clean($teamid);
        $team = $this->teamcreator->getTeamByTeamID($teamid);
        $gameid = $team->getGameID();
        $game = $this->gamecreator->getGameByGameID($gameid);
        try {
            $player = $this->playercreator->getPlayerByUserIDGameID($this->logged_in_user->getUserID(), $gameid);
        } catch (PlayerDoesNotExistException $e){
            $player = NULL;
        }
        $data = getTeamProfileDataArray($team);
        $data['url_slug'] = $game->slug();

        if ($player != NULL && $player->isMemberOfTeam($team->getTeamID()) && $player->canParticipate()) {
            $data['team_profile_buttons'] = $this->load->view('profile/leave_team_buttons.php', $data, true);
        } else if($player != NULL && $player->canParticipate()){
            $data['team_profile_buttons'] = $this->load->view('profile/join_team_buttons.php', $data, true);
        } else {
            $data['team_profile_buttons'] = '';
        }

        //this is also checked in the profile/team_edit_profile method
        if ($player != NULL && $team->canEditTeam($player)) {
            $data['team_edit_button'] = $this->load->view('profile/edit_team_buttons.php', $data, true);
        } else {
            $data['team_edit_button'] = '';
        }

        $data['members_list'] = $team->getArrayOfPlayersOnTeam();
        $data['slug'] = $this->Game_model->getGameSlugByGameID($gameid);
        $data['is_zombie'] = !is_null($player) && $player->isActiveZombie();

        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('profile/team_public_profile', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data);
    }


    public function edit_team_profile()
    {
        $default = array('edit');
        $get = $this->uri->uri_to_assoc(2, $default);
        $teamid = $get['edit'];
        $team = $this->teamcreator->getTeamByTeamID($teamid);
        $gameid = $team->getData('gameid');
        $player = $this->playercreator->getPlayerByUserIDGameID($this->logged_in_user->getUserID(), $gameid);
        if($team->canEditTeam($player)){
            $this->form_validation->set_rules('team_gravatar_email', 'Gravatar Email', 'email|trim|xss_clean');
            $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean');

            if ($this->form_validation->run()) {
            //save the data
                $name = $this->input->post('team_name');
                $gravatar_email = $this->input->post('team_gravatar_email');

                $description = $this->input->post('description');
                if($team->getData('gravatar_email') != $gravatar_email){
                  $team->setData('gravatar_email',$gravatar_email);
                }
                if($team->getData('description') != $description){
                  $team->setData('description', $description);
                }

                redirect("team/".$team->getTeamID());
            }
            $data = getTeamProfileDataArray($team);
            $data['is_zombie'] = !is_null($player) && $player->isActiveZombie();

            $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
            $layout_data['content_body'] = $this->load->view('profile/edit_team_profile', $data, true);
            $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
            $this->load->view('layouts/main', $layout_data);
        } else {

            $data['message'] = "Insufficient privileges to edit this team";
            $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
            $layout_data['content_body'] = $this->load->view('helpers/display_generic_message', $data, true);
            $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
            $this->load->view('layouts/main', $layout_data);
        }
    }
}
