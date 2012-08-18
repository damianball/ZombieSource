<?php
    // gravatar helper

function getGravatarHTML($email, $default, $size, $atts=array()){
    if($email && $email != ''){
        return build_gravatar($email, $size, 'identicon', 'x', true, $atts);
    }
    else{
        return build_gravatar($default, $size, 'identicon', 'x', true, $atts);
    }
}

// MOVE TO GRAVATAR HELPER
function build_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}
?>
