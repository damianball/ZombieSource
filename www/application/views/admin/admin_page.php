
  <div id = "game_panel">
  You wanted powers, now you've got them! (well some). Tread carefully here, if you undo a zombie tag on accident it's not exactly trival to reset it.
  Good luck and godspeed - Chandler

  </br>
  </br>
  </div>
  <div class = "tinyline"></div>
  <div id = "player_panel">
    Search for a player
    <input id ="player_chooser" type="text" class="span3" style="margin: 10px auto;" data-provide="typeahead" data-items="4" data-source='<?php echo $player_list?>'/>
    <button id = "manage_player" class = "btn success"> Manage Player</button> 
    <div class ="controls" id = "player_controls" ></div>
  </div>
  <div class = "tinyline"></div>

  <script type="text/javascript">
$(document).ready(function(){
  $("#manage_player").click(function(){
    $("#player_controls").load('admin/player_controls',{player:$('#player_chooser').val()});
  });
});

// $(document).ready(function(){
//   $("#manage_team").click(function(){
//     $("#team_controls").load('admin/team_controls',{player:$('#team_chooser').val()});
//   });
// });
</script>


