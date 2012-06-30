<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Braaaaains</title>
  <!--<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
   -->
   <?php
  #echo link_tag("css/style.css");
  echo link_tag("css/variables.less");
  echo link_tag("css/bootswatch.less");
  echo "\n";
  ?>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script type="text/javascript" src= <?php echo base_url("js/highcharts/highcharts.js") ?> ></script>
  <script type="text/javascript" src="js/less.js"></script>
  <!--<script type="text/javascript" src="../js/bootstrap.js"></script>
-->
 </head>
  <body>
    <div class="navbar navbar-fixed-top"> <?php echo $top_bar; ?></div>
      <div class="navbar-inner">
        <div class="container">
          <div class="nav-collapse">
            <ul class="nav">
              <li>
                <a href="../">See</a>
      <!--<div class = "leftnav"> 
       <div class="game_nav_options"> 
           <div class = "navitem" <?php echo ($active_sidebar == 'playerlist' ? 'id="selected"' : '') ?>> <a href = "<?php echo site_url("game"); ?> ">Players</a> </div>
           <div class = "navitem" <?php echo ($active_sidebar == 'teamlist' ? 'id="selected"' : '') ?>><a href = "<?php echo site_url("game/teams"); ?> ">Teams</a> </div>
           <div class = "navitem" <?php echo ($active_sidebar == 'logkill' ? 'id="selected"' : '') ?>><a href = "<?php echo site_url("game/register_kill"); ?> ">Register Kill</a> </div>
           <div class = "navitem" <?php echo ($active_sidebar == 'stats' ? 'id="selected"' : '') ?>> <a href = "<?php echo site_url("game/stats"); ?> ">Game stats</a> </div>
       </div>
      </div>
    -->
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
      <div class="content"> <?php echo $content_body; ?> </div>
    <div>
    <?php echo $footer; ?>
  </body>
    </html>