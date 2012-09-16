<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Achievement.php');

// Factory class for creating an achievement

class AchievementCreator{
    private $ci = null;

    public function __construct(){
        $this->ci =& get_instance();
    }

    public function getAchievement(){
        return new Achievement();
    }
}
