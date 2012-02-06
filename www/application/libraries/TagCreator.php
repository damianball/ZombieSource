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

    public function getNewTag($human, $zombie, $dateclaimed, $long = null, $lat = null){
        $this->ci->load->model('Tag_model');
        $tagid = $this->ci->Tag_model->storeNewTag($human->getPlayerID(), $zombie->getPlayerID(), $dateclaimed, $long, $lat);
        return $this->getTagByTagID($tagid);
    }
}