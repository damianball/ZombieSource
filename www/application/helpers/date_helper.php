<?php
// datetime helper

function getUTCTimeDifferenceInSeconds($new, $old){
    date_default_timezone_set('UTC');
    $oldSeconds = strtotime($old);
    $newSeconds = strtotime($new);

    return $newSeconds - $oldSeconds;
}

function getTimeStringFromSeconds($seconds){
    if (!$seconds) {
        return null;
    }

    $days = floor($seconds / 86400);
    $hours = floor(($seconds - ($days * 86400)) / 3600);
    $mins = floor(($seconds - ($hours*3600 + $days*86400)) / 60);
    $sec = floor($seconds - ($mins*60 + $hours*3600 + $days*86400));

    return $days.' day(s) '.$hours.':'.$mins.':'.$sec;
}

?>