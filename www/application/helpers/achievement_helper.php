<?php

function countAchievementsByPlayerID($playerid){
    $CI =& get_instance();
    $CI->load->model('Achievement_model', '', NULL);

    return $CI->Achievement_model->countAchievementsByPlayerID($playerid);
}

?>
