<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User{
    private $userid = null;
    private $ci = null;

    function __construct($userid)
    {
        //parent::__construct();
        $this->ci =& get_instance();
        $this->ci->load->model('User_model','',TRUE);
        if($userid){
            $this->userid = $userid;
        } else {
            throw new ClassCreationException('userid in User object is null');     
        }
    }

    public function getUserID(){
        return $this->userid;
    }

    public function getEmail(){
        return $this->ci->User_model->getEmailByUserID($this->userid);
    }

    public function getUsername(){
        return $this->ci->User_model->getUsernameByUserID($this->userid);
    }
}