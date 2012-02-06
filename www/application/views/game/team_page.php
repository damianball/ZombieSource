 <script type="text/javascript" src=" <?php echo base_url("js/tablesort.js"); ?> "></script>
  <link rel="stylesheet" href="<?php echo base_url("css/table_style.css"); ?>" >

  <div id = "sortable_tables" >
    <div class = "hvz_table" >
      <?php echo $game_table; ?>
    </div>
  </div>
  <div id = "manage_teams">
    <div class = "team_box">
      To join a team click their name to visit their profile</br></br>
      Please make sure you are welcome on a team before you join.</br>
    </div>
    <div class = "team_box">
      Think you can do Better? </br>
    </div>
      <a href = " <?php echo site_url("team/new"); ?> " id = "create_new_team" class = "alert-message success"> Create New Team </a>

  </div>


