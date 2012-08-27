<?php

function tweet_text($subject){
    $tweets = array(
        'tag' => '%TAGGER% spread the zombie infection to %TAGGEE%!',
        'team_destroyed' => '%TEAM% has been wholly assimilated by the zombies!'
    );
    return $tweets[$subject];
}

function tweet($text){
    $CI =& get_instance();
    $CI->load->library('tweet');
    $CI->tweet->enable_debug(TRUE);
    $CI->tweet->logout();
    $CI->tweet->set_tokens(array('oauth_token' => $CI->config->item('twitter_oauth_token'),
                                 'oauth_token_secret' => $CI->config->item('twitter_oauth_token_secret'),
    ));
    $CI->tweet->get_tokens();
    // Returns an object with information about the tweet
    return $CI->tweet->call('post', 'statuses/update', array('status' => $text));
}

function tweet_tag($tag){
    $tagger = $tag->getTagger();
    if(is_a($tagger, 'OriginalZombie') && !$tagger->isExposed()){
        $tagger_username = "A deadly creature";
    } else {
        $tagger_username = $tagger->getUser()->getUsername();
    }
    $taggee_username = $tag->getTaggee()->getUser()->getUsername();
    $text = tweet_text('tag');
    $text = str_replace('%TAGGER%', $tagger_username, $text);
    $text = str_replace('%TAGGEE%', $taggee_username, $text);
    tweet($text);
}

function tweet_team_destroyed($team){
    $text = tweet_text('team_destroyed');
    $text = str_replace('%TEAM%', $team, $text);
    tweet($text);
}

?>
