<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Braaaaains</title>
   <link rel="stylesheet/less" type="text/css" href="<?php echo base_url();?>css/bootstrap_zombies/less/bootstrap.less">
   <link rel="stylesheet/less" type="text/css" href="<?php echo base_url();?>css/bootswatch.less">

  <script type="text/javascript" src="<?php echo base_url();?>js/less.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script type="text/javascript" src= <?php echo base_url("js/highcharts/highcharts.js") ?> ></script>

  <script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js"></script>

 </head>
  <body>
    <div class="navbar navbar-fixed-top"> <?php echo $top_bar; ?></div>
    <div class = "container">
        <div class="tightcontainer">
         <?php echo $content_body; ?>
        </div>
      </div>
    <center>
      <?php echo $footer; ?>
    </center>

  </body>
</html>
