<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class game_overview_controller extends CI_Controller {

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

        $this->user = $this->usercreator->getUserByUserID($this->tank_auth->get_user_id());

    }

    public function index()
    {
        $game_data = array();
        $gameids = $this->Game_model->getGameIDs();
        foreach($gameids as $gameid){
            $game = $this->gamecreator->getGameByGameID($gameid);
            list($human_count, $zombie_count, $starved_zombie_count) = $game->playerStatusCounts();

            $game_data[$gameid] = array(
                          'gameid'                => $gameid,
                          'game_slug'             => $this->Game_model->getGameSlugByGameID($gameid),
                          'game_photo_url'        => $game->photoURL(),
                          'game_description'      => $game->description(),
                          'game_name'             => $game->name(),
                          'count'                 => $zombie_count + $human_count,
                          'human_count'           => $human_count,
                          'zombie_count'          => $zombie_count,
                          'starved_zombie_count'  => $starved_zombie_count,
                          'game_options'          => $this->gameOptionsView($gameid)
                        );
        }
        $data["game_data"] = $game_data;
        $layout_data = array();
        $layout_data['top_bar'] = $this->load->view('layouts/logged_in_topbar','', true);
        $layout_data['content_body'] = $this->load->view('game_overview/game_overview_page', $data, true);
        $layout_data['footer'] = $this->load->view('layouts/footer', '', true);
        $this->load->view('layouts/main', $layout_data);
    }

    public function gameOptionsView($gameid){
        $view = null;
        $game = $this->gamecreator->getGameByGameID($gameid);
        $game_stateid = $game->getStateID();
        $data["user_in_game"] = $this->user->isActiveInGame($gameid);
        $data["profile_is_empty"] = $this->user->profileIsEmpty(); 
        $data["waiver_signed"] = $this->user->SignedWaiverForGame($gameid);false; 

        $data["gameid"] = $gameid;
        $data["registration_open"] = $game->registrationIsOpen();


        // $data['join_game_sign_waiver'] = $this->load->view('profile/join_game_sign_waiver', '', true);
        $data['join_game_edit_profile'] = $this->load->view('profile/join_game_edit_profile', getPrivateUserProfileDataArray($this->user), true);

        
        if($game_stateid == 2){
            $view = $this->load->view('game_overview/active_game_options', $data, true);
        }elseif($game_stateid == 3){
            $view = $this->load->view('game_overview/closed_game_options', $data, true);
        }
        return $view;
    }

    public function join_game()
    {
        //sms subscription handling
        $phone           = $this->input->post('phone');
        $daily_updates   = $this->input->post('daily_updates');
        $team_updates    = $this->input->post('team_updates');
        $mission_updates = $this->input->post('mission_updates');
        
        $formatted_phone = validate_phone($phone);
        if($formatted_phone){
            $this->user->saveData("phone", $formatted_phone);
            
            if($daily_updates   != ""){ $this->user->updateSubscription("daily_updates",   $daily_updates == "true");}
            if($team_updates    != ""){ $this->user->updateSubscription("team_updates",    $team_updates == "true");}
            if($mission_updates != ""){ $this->user->updateSubscription("mission_updates", $mission_updates == "true");}

        }
        //profile info handling
        $gameid = $this->input->post('gameid');
        $age = $this->input->post('age');
        $major = $this->input->post('major');
        $gender = $this->input->post('gender');

        if($age != ""){ $this->user->saveData("age", $age);}
        if($major != ""){ $this->user->saveData("major", $major);}
        if($gender != ""){ $this->user->saveData("gender", $gender);}

        $originalzombiepool = $this->input->post('OriginalZombiePool');
        $waiversigned = $this->input->post('waiver_is_signed');

        $params = array();
        if($originalzombiepool != ""){ $params["OriginalZombiePool"] = $originalzombiepool;}
        if($waiversigned != ""){ $params["waiver_is_signed"] = $waiversigned;}

        echo json_encode(array("userInGame" => $this->user->joinGame($gameid, $params), "replacementView" =>$this->gameOptionsView($gameid)));
    }

    public function leave_game()
    {

        $gameid = $this->input->post('gameid'); 
        echo json_encode(array("userInGame" => $this->user->leaveGame($gameid), "replacementView" =>$this->gameOptionsView($gameid)));
    }
}
