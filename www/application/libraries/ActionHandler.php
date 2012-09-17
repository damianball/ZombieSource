<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Notification.php');

class ActionHandler{
    private $ci = null;

    public function __construct(){
        $this->ci =& get_instance();
        $this->ci->load->library('AchievementCreator');
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

      //achievements
      $ach = $this->ci->achievementcreator->getAchievement();
      $new_ach = $ach->registerKillAchievements($tag->getTagID());
      foreach($new_ach as $info){
          achievement_earned($info['achievementid'], $info['playerid']);
      }

      //rewrite tree
      writeZombieTreeJSONByGameID($gameid);
    }
}
