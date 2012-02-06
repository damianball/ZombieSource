<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag{
    private $tagid = null;
    private $ci = null;

    public function __construct($tagid)
    {
        $this->ci =& get_instance();
        $this->ci->load->model('Tag_model', '', true);

        // @TODO: Verify that the tag exists before allowing construction to finish
        if($tagid){
            $this->tagid = $tagid;
        } else {
            throw new ClassCreationException("Tagid cannot be null.");
        }
    }

    public function getTagID(){
        return $this->tagid;
    }
}