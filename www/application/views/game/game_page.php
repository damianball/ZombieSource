<?php if(!$is_player_in_game){ ?>
<div class="alert fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>You aren't in a game!</strong>
         You can't play until you join a game. Go to the 
         <a href="<?php echo site_url('overview')?>">Game Overview</a> page to join a game. 
    </div>
    <?php } ?>

<h1> <?php echo $game_name; ?>
 <!-- Check if game is closed and style accordingly  -->
<?php 
  if($is_closed){
    echo "<small> (Closed)</small></h1>";
    echo "<script type=\"text/javascript\">";
    echo "$(\".container\")[1].style.opacity = 0.5;";
    echo "</script>";
  }else{
    echo "</h1>";
  }
?>

<hr>
<h2> Players </h2>
<br>
<div class="row-fluid">
  <?php
    $data["slug"] = $url_slug;
    $this->load->view("layouts/game_sidebar", $data); 
  ?>
  <div class="span10">
      <?php echo $game_table; ?>
  </div>
</div>


<script type="text/javascript">
$(document).ready(function() 
    { 
        $("#game_table").tablesorter({
          headers:{
            0: { // disable the avatar column sorting
              sorter: false
            }
          }
        }); 
    } 
); 
</script>
