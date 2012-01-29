<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
  var $logged_in_player;
  var $logged_in_players_team;

  function __construct()
  {
      parent::__construct();

      if(!$this->tank_auth->is_logged_in()){
        redirect('/auth/login');
      }
      $this->load->helper(array('form', 'url'));
      $this->load->library('security');
      $this->load->library('tank_auth');
      $this->lang->load('tank_auth');
      $this->load->model('Player_model','',TRUE);
      $this->load->library('player');
      $this->load->library('team');

      $userid = $this->tank_auth->get_user_id();
      $this->logged_in_player = Player::getPlayerByUserIDGameID($userid, GAME_KEY);
      // @TODO: what if team is null?
      $teamid = $this->logged_in_player->current_team();      
      $this->logged_in_players_team = new team($teamid);
  }

  public function index()
  {
    //this needs to be run for everyone on the site
    // $player->save("username",$this->tank_auth->get_username());
    // $player->save("email",$this->tank_auth->get_email());

   $player = $this->logged_in_player;
   if($player->waiverSigned()){
      $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
      $layout_data['content_body'] = $this->load->view('profile/profile_page', $player->getDataArray(), true);
      $layout_data['footer'] = $this->load->view('layouts/footer', '', true);          
      $this->load->view('layouts/main', $layout_data);
   }
    else{
      $this->form_validation->set_rules('waiver', 'Waiver', 'trim|required|xss_clean');
      $this->form_validation->set_rules('sig', 'Signature', 'trim|required|xss_clean');
      $this->form_validation->set_rules('age', 'Age', 'integer|trim|xss_clean');
      $this->form_validation->set_rules('gender', 'Gender', 'trim|xss_clean');
      $this->form_validation->set_rules('major', 'Major', 'trim|xss_clean');
      $this->form_validation->set_rules('originalzombiepool', 'OriginalZombiePool', 'trim|xss_clean');
                  
        if ($this->form_validation->run()) {
          //store data
          $params = array('waiver_is_signed' => "TRUE",
                          'sig' => $this->input->post('sig'),
                          'age' => $this->input->post('age'),
                          'gender' => $this->input->post('gender'),
                          'major' => $this->input->post('major'),
                          'OriginalZombiePool' => $this->input->post('originalzombiepool'));

          $this->player->join_game($params);
          redirect('profile');
        }
        else{
          $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
          $layout_data['content_body'] = $this->load->view('auth/registration_page_two', '', true);
          $layout_data['footer'] = $this->load->view('layouts/footer', '', true);          
          $this->load->view('layouts/main', $layout_data);
        }
    }
  }

  public function edit_profile()
  {
      $player = $this->logged_in_player;
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
      $layout_data['content_body'] = $this->load->view('profile/edit_profile', $player->getDataArray(), true);
      $layout_data['footer'] = $this->load->view('layouts/footer', '', true);          
      $this->load->view('layouts/main', $layout_data);
  }


  public function public_profile()
  {
      $get = $this->uri->uri_to_assoc(1);
      $userid = $get['user'];
      $player = Player::getPlayerByUserIDGameID($userid, GAME_KEY);
      $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
      $layout_data['content_body'] = $this->load->view('profile/public_profile', $player->data, true);
      $layout_data['footer'] = $this->load->view('layouts/footer', '', true);          
      $this->load->view('layouts/main', $layout_data);
  }

    public function team_public_profile()
  {
      // how many members
      // total kills
      // description
      // team gravatar

      $get = $this->uri->uri_to_assoc(1);
      $teamid = $get['team'];
      $team = new team($teamid);

      $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
      $layout_data['content_body'] = $this->load->view('profile/team_public_profile', $team->data, true);
      $layout_data['footer'] = $this->load->view('layouts/footer', '', true);          
      $this->load->view('layouts/main', $layout_data);
  }
}
