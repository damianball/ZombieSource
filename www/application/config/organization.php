<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['organization_name'] = 'University of Idaho';
// The Tumblr username to display on the homepage for announcements etc.
$config['tumblr_username'] = 'uihz';
// What the Tumblr post holding game information will be tagged with
$config['tumblr_info_tag'] = 'info';
// Tumblr application api key
$config['tumblr_api_key'] = "QTCRcblt2a8XOalgBp2F7KIpZUqjtrHUIkMmDVMAgj1PvgCUJv";
// The number of Tumblr announcements (posts) to display
$config['tumblr_num_posts'] = 1;


//// Twitter API information (for sending tweets, not listing them; user-based)
// @TODO: delete the secret token and replace (NOT in git!) with another
$config['twitter_oauth_token'] = '783583686-HLIGmYVBW1zH6njX17ASoG7LJDhUzlbEf6tTbAx8';
$config['twitter_oauth_token_secret'] = 'PibQhSxAYlOHkfiQ71Gqf9iSmkjG3pIAxGZ7cm7j68';
// The Twitter hashtag for people to get their tweets posted
$config['twitter_hashtag'] = '#VandalsHvZ';
// The search string for the Twitter feed
$config['twitter_search'] = json_encode('from:placedinbags OR ' . $config['twitter_hashtag']);

$config['twilio_account_sid'] = 'ACabd6be0f388b473592246ed204b78586';
$config['twilio_auth_token'] = '408f0ca9fc3ea08d63c6fd4b155db629';
$config['twilio_number'] = '208-402-4500';


