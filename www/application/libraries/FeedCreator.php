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
        return $this->getFeedByFeedID($feedid);
    }
}