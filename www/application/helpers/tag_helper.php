<?php
function getInitialTagIDByPlayer($player){
    if(is_a($player, 'Zombie')){
        $CI =& get_instance();
        $CI->load->model('Tag_model','',TRUE);
        return $CI->Tag_model->getTagIDForPlayer($player->getPlayerID());
    } else {
        return false;  
    }
}
?>