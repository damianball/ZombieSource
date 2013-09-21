
<h1> <?php echo $game_name; ?></h1>
<hr>
<div class="row-fluid">
  <?php
    $data["slug"] = $url_slug;
    $this->load->view("layouts/game_sidebar", $data);
  ?>
  <div class="span10">
     <strong class="<?php echo $error ? 'alert alert-danger' : 'alert alert-info'?>"><?php echo $message; ?></strong>
  </div>
</div>

