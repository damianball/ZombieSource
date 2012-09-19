<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'libraries/Notification.php');

class sms_controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Player_model','',TRUE);
        $this->load->library('PlayerCreator');
        $this->load->library('UserCreator');
        $this->load->library('GameCreator');
        $this->load->library('TeamCreator');
        $this->load->helper('player_helper');
        $this->load->helper('team_helper');
        $this->load->helper('game_helper');
        $this->load->helper('user_helper');
        $this->load->config('notifications');
    }

    public function receive_message(){
        $value = $this->input->post('Body');
        $number = trim($this->input->post('From'), ' +');
        $user= $this->usercreator->getUserByPhone($number);
        $response = $this->generate_response($user, $value, $number);

        debug('sms_response: value: ' . $value . ' userid: ' . $user->getUserID() . ' recipient_number: ' . $number . " message: " . $response);
        
        header("Content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Response>";
        echo "<Sms>$response</Sms>";
        echo "</Response>";
    }

    private function generate_response($user, $value, $number){
        $response = "";
        $split_value = explode(" ", strToLower(trim($value)), 2);
        $command = $split_value[0];

        if($user==null || $user->currentGameID() == null){
            $game = $this->gamecreator->getGameByGameID("0b84d632-da0e-11e1-a3a8-5d69f9a5509e");
        }else{
            $game = $this->gamecreator->getGameByGameID($user->currentGameID());
        }

        if(count($split_value) >= 2){
            $message = $split_value[1];
        }else{
            $message = null;
        }

        if(($command == "all" || $command == "humans" || $command == "zombies")){
            if(!$message){
                return;
            }
            if($this->isMiddleOfNight()){
                $response = "Sorry, it's really late at night. Try again betwween 8am and midnight PST";
            }else if($this->isOnAdminWhiteList($number)){
              $data_obj = new stdClass();
              $data_obj->notification_name = 'broadcast';
              $data_obj->type = $command;
              $data_obj->message = $message;
              $notification = new Notification($game->getGameID(), $data_obj);
              $response = $notification->send();
            }else{
                $response = "access denied";
            }
        }
        else if($command == "stats"){
            list($human_count, $zombie_count, $starved_zombies) = $game->playerStatusCounts();
            $response = "humans: $human_count active_zombies: $zombie_count starved_zombies: $starved_zombies"; 
        }else if($user){ //All commands except stats/all/humans/zombies required a registered user.
            if($command == "start"){
                $user->updateSubscription("pause_updates", false);
            }else if($command == "stop"){
                $user->updateSubscription("pause_updates", true);
            }else if($command == "tag"){ //TODO update before game starts.
                $response = $this->tagViaText($user, $message, $game);
            }else{
                $response = "Sorry, command not recognized. Visit http://bit.ly/Tf26sx to see a list of valid commands";
            }
        }else{
            $response = "To use Zombie Source texting register your phone at http://bit.ly/Tf26sx. Unregistered phones can still text \"stats\".";
        }

        return $response;
    }


    private function isMiddleOfNight(){
        $current_hour = date('H', time());
        return (($current_hour > 8) && ($current_hour < 16));
    }

    private function isOnAdminWhiteList($number){
        $whitelist = $this->config->item('sms_white_list');
        return in_array($number,$whitelist);
    }

    private function tagViaText($tagger, $message, $game){
        $human_code = strtoupper($message);
        try{
            $playerid = getPlayerIDByHumanCodeGameID($human_code, $game->getGameID());
        }catch (InvalidHumanCodeException $e){
            return "Not a valid human code, sorry!";
        }
        $zombie = $this->playercreator->getPlayerByUserIDGameID($tagger->getUserID(), $game->getGameID());
        return $game->register_kill($zombie,$human_code, null, null);
    }
}