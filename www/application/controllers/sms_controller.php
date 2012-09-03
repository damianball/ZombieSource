<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sms_controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Player_model','',TRUE);
        $this->load->library('PlayerCreator');
        $this->load->library('UserCreator');
        $this->load->library('GameCreator');
        $this->load->library('TeamCreator');
        $this->load->library('NotificationHandler');

        $this->load->helper('player_helper');
        $this->load->helper('team_helper');
        $this->load->helper('user_helper');
    }

    public function receive_message(){
        $value = $this->input->post('Body');
        $number = $this->input->post('From');
        $user= $this->usercreator->getUserByPhone($number);

        $response = $this->generate_response($user, $value);

        header("Content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Response>";
        echo "<Sms>$response</Sms>";
        echo "</Response>";
    }

    private function generate_response($user, $value){
        $response = "";
        $split_value = explode(" ", trim($value), 2);
        $command = $split_value[0];

        if($user==null || $user->currentGameID() == null){
            $game = $this->gamecreator->getGameByGameID("0b84d632-da0e-11e1-a3a8-5d69f9a5509e");
        }else{
            $game = $this->gamecreator->getGameByGameID($user->currentGameID());
        }

        if($command == "stats"){
            list($human_count, $zombie_count, $starved_zombies) = $game->playerStatusCounts();
            $response = "humans: $human_count active_zombies: $active_zombies starved_zombies: $starved_zombies"; 
        }else if($user){ //All commands except stats required a registered user.
            if($command == "start"){
                $user->subscribe("pause_updates", false);
                $response = "SMS alerts actived. To pause, text \"stop\". Visit http://bit.ly/Tf26sx to manage your subscription settings";
            }else if($command == "stop"){
                $user->subscribe("pause_updates", true);
                $response = "SMS alerts paused. to resume, text \"start\". Visit http://bit.ly/Tf26sx to manage your subscription settings";
            }else if($command == "tag"){ //TODO update before game starts.
                $response = "Cannot register a tag until game starts. Visit http://bit.ly/Tf26sx to see a list of valid commands";
            }else{
                $response = "Sorry, command not recognized. Visit http://bit.ly/Tf26sx to see a list of valid commands";
            }
        }else{
            $response = "To use Zombie Source texting register your phone at http://bit.ly/Tf26sx. Unregistered phones can still text \"stats\".";
        }

        return $response;
    }
}