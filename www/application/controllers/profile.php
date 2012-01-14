<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
    }

    public function index()
    {
        if ($this->tank_auth->is_logged_in()) { 
            $this->profile();
        }
        else{
            $this->load->view('header');
            $this->load->view('please_login');
            $this->load->view('footer');
        }
    }

    public function profile()
    {
        $this->load->view('header');
        $this->load->view('profile_page');
        $this->load->view('footer');
    }

}
