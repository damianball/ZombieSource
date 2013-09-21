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
  }else{
    echo "</h1>";
  }
?>
<hr>
<h2> Achievements </h2>
<br>
<div class="row">
    <?php
      $data["slug"] = $url_slug;
      $this->load->view("layouts/game_sidebar", $data);
    ?>

    <div class="span10">
  <?php
      foreach($achievement_types as $ach_info){
          $img_url = $ach_info->image_url;
          $description = $ach_info->description;
          $name = $ach_info->name;

          $users = @$achievers[$ach_info->id];
          if(!$users) $users = array();
          $achieved_by = count($users);
          echo '<div class="row"><div class="span9 well">';
          echo '<div style="margin-left: 30px;">';
          echo "<h2>$name <small>Achieved by $achieved_by</small></h2>";
          echo "<img class=\"twtr-pic\" src=\"$img_url\">";
          echo "$description";
          echo '</div>';
          echo '<br>';
          if($achieved_by > 0){
            echo "<div class=\"accordian\" id=\"accordian2\">";
            echo "<div class=\"accordion-group\">";
            echo "<div class=\"accordion-heading\">";
            echo "<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion2\" href=\"#collapse" . $ach_info->id . "\">";
            echo "<i class=\"icon-chevron-down\"></i> Achieved by...";
            echo '</a>';
            echo '</div>';
            echo "<div id=\"collapse" . $ach_info->id . "\" class=\"accordian-body collapse\">";
            echo "<div class=\"accordian-inner\">";
          }

          foreach($users as $user){
              $userid = $user['userid'];
              $username = $user['username'];
              $date = $user['date'];
              $gravatar = $user['gravatar'];
              echo "<div class=\"team_member\" rel=\"tooltip\" title=\"Achieved: " . date('g:i A \o\n l F n, Y', strtotime($date . ' UTC')) . "\">";
              if($userid){
                  echo "<a href=" . base_url("/user/$userid") . ">";
              }
              echo $gravatar;
              echo "<div class=\"team_member_attribute\">$username</div>";
              if($userid){
                  echo "</a>";
              }
              echo '</div>';
          }
          echo '</div></div>';
          if($achieved_by > 0){
            echo '</div></div></div></div>';
          }
      }

?>

</div>
</div>


      <script>
        $(document).ready(function(){
        $('.team_member').tooltip();
        });
      </script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-collapse.js"/>
