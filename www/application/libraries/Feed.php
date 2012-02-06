<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feed{
    private $feedid = null;
    private $ci = null;

    public function __construct($feedid)
    {
        $this->ci =& get_instance();
        $this->ci->load->model('Feed_model', '', true);

        // @TODO: Verify that the feed exists before allowing construction to finish
        if($feedid){
            $this->feedid = $feedid;
        } else {
            throw new ClassCreationException("Feedid cannot be null.");
        }
    }

    public function getFeedID(){
        return $this->feedid;
    }
}