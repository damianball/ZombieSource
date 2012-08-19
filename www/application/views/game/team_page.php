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
<h2> Teams </h2>
<br>
<div class="row-fluid">
    <?php
      $data["slug"] = $url_slug;
      $this->load->view("layouts/game_sidebar", $data);
    ?>
    <div class="span6">
      <?php echo $game_table; ?>
    </div>


    <div class="span4">
        <div class="well-side">
          <strong>Teams</strong> are groups of people banding together for fun and mutual benefit.
          <br>
          <br>
          <?php
            if($is_closed == FALSE && $is_human){
              echo "To join a team, click their name to visit their profile.</br></br>
              Please make sure you are welcome on a team before you join.</br>";
              echo "<br>";
              echo "<strong> Think you can do Better? </strong> </br>";

              echo "<a href=\"";

              echo site_url("game/$url_slug/register_new_team");
              echo "\" id = \"create_new_team\" class = \"btn btn-margin btn-yellow\"> Create New Team </a>";
            }
            else if ($is_closed){
              echo "This game is <b>closed</b>. You cannot create teams for this game.";
            }
            else{
              echo "<b> Join a Game</b> if you want to create/join a team!";
            }
          ?>
        </div>
    </div>

</div>

<script type="text/javascript">
$(document).ready(function()
    {
        $("#teams_table").tablesorter({
          headers:{
            0: { // disable the avatar column sorting
              sorter: false
            }
          }
        });
    }
);
</script>
