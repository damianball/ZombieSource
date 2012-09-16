<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Notification.php');

class ActionHandler{
    private $ci = null;

    public function __construct(){
        $this->ci =& get_instance();
        $this->ci->load->helper('tree_helper');
        $this->ci->load->helper('tweet_helper');
    }

    public function tagAction($tag, $gameid){
      //SMS
      $data_obj = new stdClass();
      $data_obj->notification_name = 'teammate_tagged';
      $data_obj->tag = $tag;
      $notification = new Notification($gameid, $data_obj);
      $notification->send();

      //Tweet
      tweet_tag($tag);

      //rewrite tree
      writeZombieTreeJSONByGameID($gameid);
    }
}