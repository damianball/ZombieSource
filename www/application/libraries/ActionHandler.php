<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Notification.php');

class ActionHandler{
    private $ci = null;

    public function __construct(){
        $this->ci =& get_instance();
    }

    public function tagAction($tag_id, $gameid){
      $data_obj = new stdClass();
      $data->notification_name = 'teammate_tagged';
      $notification = new Notification($gameid, $data_obj);
      // $notification->send();
    }
}