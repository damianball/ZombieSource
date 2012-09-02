<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification{
    private $ci = null;
    private $gamid = null;
    private $message = null;
    private $user_id_list;

    public function __construct($gameid, $data_obj){
        $this->ci =& get_instance();
        $this->gameid = $gameid;
    
        $AccountSid = $this->config->item('twilio_account_sid');
        $AuthToken = $this->config->item('twilio_auth_token');
        $this->TwilioNumber = $this->config->item('twilio_number');
        $this->client = new Services_Twilio($AccountSid, $AuthToken);

        $this->ci->load->library('tagcreator', null);
        $this->ci->load->model('User_model', '', true);
        $this->initialize($data_obj);
    }

    public function initialize($data_obj){
      $obj->$notification_name;
    }

    public function user_id_list(){
      return $this->user_id_list;
    }

    public function message(){
      return $this->message;
    }

    private function teammate_tagged($tag_id){
      // $team = $this->teamcreator->getTeamByTeamID();
      // $teamname = $team->name();
      // $tagged_teammate_name = 
      // $tagger_name = 
      // return "Team \'$teamname\'' alert, your teammate $tagged_teammate has been turned into a zombie by $tagger_name";
    }

    private function daily_update($date_id){
      $teamname = $team->name();
      $tagged_teammate_name = 
      $tagger_name = 
      return "Update for $date: $num_zombies_day people have been turned into zombies today. Total zombie count: $total_zombie_count";
    }

    private function send(){
      foreach($this->user_id_list() as $user_id){
        $recipient_number = $this->ci->User_model->getUserData($recipient_user_id, "phone");
        $message = $notification->me;
        $message = substr($message,0,160); //precaution, don't send anything longer than 160 characters.
        // $client->account->sms_messages->create(
        //   $this->TwilioNumber,
        //   $recipient_number,
        //   $message
        // );
      }
    }
}