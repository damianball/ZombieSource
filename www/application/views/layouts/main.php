<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
  <meta charset="utf-8">
  <title>Braaaaains</title>

  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootswatch.css">
  <link rel="icon" type="image/png" href="<?php echo base_url();?>images/zombiesource.png">

   <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootswatch.css">
   <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap_overrides.css"> -->


  <script type="text/javascript" src="<?php echo base_url('js/modernizr.custom.05649.min.js'); ?>"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('js/highcharts/highcharts.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('js/fix_placeholder.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url()?>js/bootstrap-tooltip.js"></script>
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
