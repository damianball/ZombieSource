
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title> Braaaaains</title>
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
 <?php
  echo link_tag("css/style.css");
  echo "\n";
  ?>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

  <script type="text/javascript" src="js/highcharts/highcharts.js"></script>

  <link rel="shortcut icon" href="images/favicon.ico">
  <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
  <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
  </head>
  <body>
    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" > HvZ </a>
          <ul class="nav">
            <li> <a href = "<?php echo site_url("home"); ?> ">Home</a> </li>
          <?php 
            if($this->tank_auth->is_logged_in()){
               echo '
                    <li> <a href="' . site_url("profile") . '">Profile</a> </li>
                    </ul>
                    <div id = "signout" class="pull-right">
                        <a href="' . site_url("auth/logout") . '">
                          <button class="btn success"> Sign Out </button>
                        </a>
                    </div>';
            }
            else {
               echo ' </ul>
                        <form action = "'. site_url("auth/login") .'" method="post" accept-charset="utf-8" class="pull-right">
                          <input placeholder="Username" class="input-small" type="text" name="login" value="" id="login" maxlength="80" size="30"  />
                          <input placeholder="Password" class="input-small" type="password" name="password" value="" id="password" size="30"  />
                          <button class="btn success" type="submit">Sign in</button>
                      </form>';
                }
          ?>
        </div>
      </div>
    </div>
    <div id = topline>
    </div>
    <div class="container">
      <div class="content">