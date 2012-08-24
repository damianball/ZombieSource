<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sms_controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Player_model','',TRUE);
        $this->load->library('PlayerCreator');
        $this->load->library('UserCreator');
        $this->load->library('TeamCreator');
        $this->load->helper('player_helper');
        $this->load->helper('team_helper');
        $this->load->helper('user_helper');


    }

    public function index()
    {
        echo "hey";
    }

    public function receive_message(){
        $value = $this->input->post('Body');
        header("Content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Response>";
        echo "<Sms>Braaaains</Sms>";
        echo "</Response>";
    }

    public function send_message(){
        // Step 1: Download the Twilio-PHP library from twilio.com/docs/libraries, 
        // and move it into the folder containing this file.
        require_once(APPPATH . '/services/Twilio.php');

        // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
        $AccountSid = $this->config->item('twilio_account_sid');
        $AuthToken = $this->config->item('twilio_auth_token');

        // Step 3: instantiate a new Twilio Rest Client
        $client = new Services_Twilio($AccountSid, $AuthToken);

        // Step 4: make an array of people we know, to send them a message. 
        // Feel free to change/add your own phone number and name here.
        $people = array(
            "+12089912446" => "Chandler Abraham"
        );

        // Step 5: Loop over all our friends. $number is a phone number above, and 
        // $name is the name next to it
        foreach ($people as $number => $name) {

            $sms = $client->account->sms_messages->create(

            // Step 6: Change the 'From' number below to be a valid Twilio number 
            // that you've purchased, or the (deprecated) Sandbox number
                "208-402-4500", 

                // the number we are sending to - Any phone number
                $number,

                // the sms body
                "Hey $name, Monkey Party at 6PM. Bring Bananas!"
            );

            // Display a confirmation message on the screen
            echo "Sent message to $name";
        }
    }
}