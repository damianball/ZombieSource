<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
  // var $logged_in_player;
  // var $logged_in_players_team;

    public function __construct()
    {
        parent::__construct();
    
        if(!$this->tank_auth->is_logged_in()){
          redirect('/auth/login');
        }
    
        $this->load->model('Player_model','',TRUE);
        $this->load->library('PlayerCreator');
        $this->load->library('TeamCreator');
        $this->load->helper('player_helper');
        $this->load->helper('team_helper');
    
        // @TODO: what if team is null?
        // $teamid = $this->logged_in_player->current_team();      
        // $this->logged_in_players_team = new team($teamid);
    }

    public function index()
    {
      //this needs to be run for everyone on the site
      // $player->save("username",$this->tank_auth->get_username());
      // $player->save("email",$this->tank_auth->get_email());
    
     // $player = $this->logged_in_player;
     // $userid = $this->tank_auth->get_user_id();
     // $this->Player_model->getPlayerID();
    
        $userid = $this->tank_auth->get_user_id();
        $player = null;
        if(userExistsInGame($userid, GAME_KEY)){
            $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
        }
        if($player){
            $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
            $layout_data['content_body'] = $this->load->view('profile/profile_page', getPrivatePlayerProfileDataArray($player), true);
            $layout_data['footer'] = $this->load->view('layouts/footer', '', true);          
            $this->load->view('layouts/main', $layout_data);
        } else {
            $this->form_validation->set_rules('waiver', 'Waiver', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sig', 'Signature', 'trim|required|xss_clean');
            $this->form_validation->set_rules('age', 'Age', 'integer|trim|xss_clean');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|xss_clean');
            $this->form_validation->set_rules('major', 'Major', 'trim|xss_clean');
            $this->form_validation->set_rules('originalzombiepool', 'OriginalZombiePool', 'trim|xss_clean');
                        
            if ($this->form_validation->run()) {
                //store data
                $params =   array('waiver_is_signed' => "TRUE",
                                'sig' => $this->input->post('sig'),
                                'age' => $this->input->post('age'),
                                'gender' => $this->input->post('gender'),
                                'major' => $this->input->post('major'),
                                'OriginalZombiePool' => $this->input->post('originalzombiepool')
                            );
            
                $player = $this->playercreator->createPlayerByJoiningGame($userid, GAME_KEY, $params);
                redirect('profile');
            } else {
                $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
                $layout_data['content_body'] = $this->load->view('auth/registration_page_two', '', true);
                $layout_data['footer'] = $this->load->view('layouts/footer', '', true);          
                $this->load->view('layouts/main', $layout_data);
            }
        }
    }

    public function edit_profile()
    {
        $userid = $this->tank_auth->get_user_id();
        if(userExistsInGame($userid, GAME_KEY)){
          $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
        } else {
          redirect("home");
        }
    
        $this->form_validation->set_rules('age', 'Age', 'integer|trim|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|xss_clean');
        $this->form_validation->set_rules('major', 'Major', 'trim|xss_clean');
        $this->form_validation->set_rules('gravatar_email', 'Gravatar Email', 'trim|xss_clean|valid_email');
    
        if ($this->form_validation->run()) {
          //save the data
          if($player->getData('age') != $this->input->post('age')){
            $player->saveData('age',$this->input->post('age'));
          }
          if($player->getData('gender') != $this->input->post('gender')){
            $player->saveData('gender',$this->input->post('gender'));
          }
          if($player->getData('major') != $this->input->post('major')){
            $player->saveData('major',$this->input->post('major'));
          }
          if($player->getData('gravatar_email') != $this->input->post('gravatar_email')){
            $player->saveData('gravatar_email',$this->input->post('gravatar_email'));
          }
          redirect("profile");
        }
    
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('profile/edit_profile', getPrivatePlayerProfileDataArray($player), true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data);
    }


    public function public_profile()
    {
          // @TODO: Are we checking input?
        $get = $this->uri->uri_to_assoc(1);
        $userid = $get['user'];
        if(userExistsInGame($userid, GAME_KEY)){
          $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
        } else {
          redirect("home");
        }
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('profile/public_profile', getPublicPlayerProfileDataArray($player), true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);          
        $this->load->view('layouts/main', $layout_data);
    }

    public function team_public_profile()
    {
        $userid = $this->tank_auth->get_user_id();
        if(userExistsInGame($userid, GAME_KEY)){
          $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
        } else {
          redirect("home");
        }
    
        $get = $this->uri->uri_to_assoc(1);
        // @TODO: THIS IS PROBABLY A TERRIBLE IDEA
        $teamid = $get['team'];
        $teamid = $this->security->xss_clean($teamid);
        $team = $this->teamcreator->getTeamByTeamID($teamid);
        $data = getTeamProfileDataArray($team);
    
        if($player->isMemberOfTeam($team->getTeamID())){
          $data['team_profile_buttons'] = $this->load->view('profile/leave_team_buttons.php', $data, true);
        }else{
          $data['team_profile_buttons'] = $this->load->view('profile/join_team_buttons.php', $data, true);
        }
    
        //this is also checked in the profile/team_edit_profile method
        if($team->canEditTeam($player)){
          $data['team_edit_button'] = $this->load->view('profile/edit_team_buttons.php', $data, true);
        }else{
          $data['team_edit_button'] = '';
        }
    
        $data['members_list'] = $team->getArrayOfPlayersOnTeam();
    
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('profile/team_public_profile', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);          
        $this->load->view('layouts/main', $layout_data);
    }


    public function edit_team_profile()
    {
    
        $userid = $this->tank_auth->get_user_id();
        if(userExistsInGame($userid, GAME_KEY)){
          $player = $this->playercreator->getPlayerByUserIDGameID($userid, GAME_KEY);
        } else {
          redirect("home");
        }
    
        $default = array('edit');
        $get = $this->uri->uri_to_assoc(2, $default);
        $teamid = $get['edit'];
        $team = $this->teamcreator->getTeamByTeamID($teamid);
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
              $team->setData('description',$description);
            }
    
            redirect("team/".$team->getTeamID());
          }
    
          $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
          $layout_data['content_body'] = $this->load->view('profile/edit_team_profile', getTeamProfileDataArray($team), true);
          $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
          $this->load->view('layouts/main', $layout_data);
        }
        else{
    
          $data['message'] = "Insufficient privileges to edit this team";
          $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
          $layout_data['content_body'] = $this->load->view('helpers/display_generic_message', $data, true);
          $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
          $this->load->view('layouts/main', $layout_data);
        }
    }
}
