<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/Feed.php');

// Factory class for instanciating a team

class FeedCreator{
    private $ci;

    public function __construct(){
        $this->ci =& get_instance();
    }

    public function getFeedByFeedID($feedid){
        return new Feed($feedid);
    }

    public function getNewFeed($zombie, $tag, $datecreated, $isAdmin = null){
        $this->ci->load->model('Feed_model');
        $tagid = null;
        if(is_a($tag,'Tag')){
            $tagid = $tag->getTagID();
        }
        $feedid = $this->ci->Feed_model->storeNewFeed($zombie->getPlayerID(), $tagid, $datecreated, $isAdmin);

        if($feedid){
            // event logging
            $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('create_new_feed','succeeded');
            $analyticslogger->addToPayload('fed_zombie_playerid',$zombie->getPlayerID());
            $analyticslogger->addToPayload('tagid', $tagid);
            $analyticslogger->addToPayload('is_admin', $isAdmin);
            $analyticslogger->addToPayload('feedid', $feedid);
            LogManager::storeLog($analyticslogger);
        } else {
            // event logging
            $analyticslogger = AnalyticsLogger::getNewAnalyticsLogger('create_new_feed','failed');
            $analyticslogger->addToPayload('fed_zombie_playerid',$zombie->getPlayerID());
            $analyticslogger->addToPayload('tagid', $tagid);
            $analyticslogger->addToPayload('is_admin', $isAdmin);
            LogManager::storeLog($analyticslogger);
        }

        return $this->getFeedByFeedID($feedid);
    }
}