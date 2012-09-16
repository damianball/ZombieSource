<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Tag.php');

// Factory class for instanciating a team

class TagCreator{
    private $ci;

    public function __construct(){
        $this->ci =& get_instance();
    }

    public function getTagByTagID($tagid){
        return new Tag($tagid);
    }

    public function getNewTag($human, $zombie, $dateclaimed = null, $long = null, $lat = null){
        $this->ci->load->model('Tag_model');
        $tagid = $this->ci->Tag_model->storeNewTag($human->getPlayerID(), $zombie->getPlayerID(), $dateclaimed, $long, $lat);

        if($tagid){
            // event logging
            $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('create_new_tag','succeeded');
            $analyticslogger->addToPayload('human_playerid',$human->getPlayerID());
            $analyticslogger->addToPayload('zombie_playerid',$zombie->getPlayerID());
            $analyticslogger->addToPayload('tagid', $tagid);
            $analyticslogger->addToPayload('dateclaimed', $dateclaimed);
            $analyticslogger->addToPayload('long', $long);
            $analyticslogger->addToPayload('lat', $lat);
            LogManager::storeLog($analyticslogger);
        } else {
            // event logging
            $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('create_new_tag','failed');
            $analyticslogger->addToPayload('human_playerid',$human->getPlayerID());
            $analyticslogger->addToPayload('zombie_playerid',$zombie->getPlayerID());
            $analyticslogger->addToPayload('dateclaimed', $dateclaimed);
            $analyticslogger->addToPayload('long', $long);
            $analyticslogger->addToPayload('lat', $lat);
            LogManager::storeLog($analyticslogger);
        }

        return $this->getTagByTagID($tagid);
    }
}