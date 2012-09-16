<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag{
    private $tagid = null;
    private $ci = null;

    public function __construct($tagid)
    {
        $this->ci =& get_instance();
        $this->ci->load->model('Tag_model', '', true);
        $this->ci->load->library('PlayerCreator', null);


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

    public function getTagDateTimeClaimed(){
        return $this->ci->Tag_model->getData($this->tagid, 'datetimeclaimed');
    }

    public function getTagDateTime(){
        return $this->ci->Tag_model->getData($this->tagid, 'datetimecreated');
    }

    public function getTagger(){
        $taggerid = $this->getTaggerID();
        return $this->ci->playercreator->getPlayerByPlayerID($taggerid);
    }

    public function getTaggerID(){
        return $this->ci->Tag_model->getData($this->tagid, 'taggerid');
    }

    public function getTaggee(){
        $taggeeid = $this->getTaggeeID();
        return $this->ci->playercreator->getPlayerByPlayerID($taggeeid);
    }

    public function getTaggeeID(){
        return $this->ci->Tag_model->getData($this->tagid, 'taggeeid');
    }

    public function invalidate(){
        $this->ci->Tag_model->invalidateTag($this->tagid, 'taggerid');
    }

    public function isInvalid(){
        if( $this->ci->Tag_model->getData($this->tagid, 'invalid') == 1){
            return true;
        }else{
            return false;
        }
    }
}
