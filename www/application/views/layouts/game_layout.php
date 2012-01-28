<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Braaaaains</title>
  <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
   <?php
  echo link_tag("css/style.css");
  echo "\n";
  ?>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script type="text/javascript" src= <?php echo base_url("js/highcharts/highcharts.js") ?> ></script>
 </head>
  <body>
    <div class="topbar"> <?= $top_bar; ?></div>
    <div class="container">
      <div class = "leftnav"> 
       <div class="game_nav_options"> 
           <div class = "navitem" <?= ($active_sidebar == 'playerlist' ? 'id="selected"' : '') ?>> <a href = "<?php echo site_url("game"); ?> ">Players</a> </div>
           <div class = "navitem" <?= ($active_sidebar == 'teamlist' ? 'id="selected"' : '') ?>><a href = "<?php echo site_url("game/teams"); ?> ">Teams</a> </div>
           <div class = "navitem" <?= ($active_sidebar == 'logkill' ? 'id="selected"' : '') ?>><a href = "<?php echo site_url("game/register_kill"); ?> ">Register Kill</a> </div>
           <div class = "navitem" <?= ($active_sidebar == 'stats' ? 'id="selected"' : '') ?>> <a href = "<?php echo site_url("game/stats"); ?> ">Game stats</a> </div>
       </div>
      </div>
      <div class="content"> <?= $content_body; ?> </div>
    <div>
    <?= $footer; ?>
  </body>
    </html>