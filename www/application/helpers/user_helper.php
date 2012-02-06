<?php
// user helper
// @TODO: do some checking for null... ya know
function getUserIDByUsername($username){
    $CI =& get_instance();
    $CI->load->model('User_model','',TRUE);
    return $CI->User_model->getUserIDByUsername($username);
}
?>