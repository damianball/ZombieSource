

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
  <script type="text/javascript" src="js/highcharts/highcharts.js"></script>
 </head>
  <body>
    <div class="topbar"> <?= $top_bar; ?></div>
    <div class="container">
      <div class="content"> <?= $content_body; ?> </div>
    <div>
    <?= $footer; ?>
  </body>
</html>