 <script type="text/javascript" src=" <?php echo base_url("js/tablesort.js"); ?> "></script>
 <!-- <link rel="stylesheet" href="<?php echo base_url("css/table_style.css"); ?>" > -->
<h2> Teams </h2>
<hr>


<div class="row-fluid">
    <?php $this->load->view("layouts/game_sidebar"); ?>       
    <div class="span6">
      <?php echo $game_table; ?>
    </div>


    <div class="span4">
        <div class="well-side">
          <strong>Teams</strong> are groups of people banding together for fun and mutual benefit.
          <br>
          <br>
          To join a team, click their name to visit their profile.</br></br>
          Please make sure you are welcome on a team before you join.</br>
          <br>
          <strong> Think you can do Better? </strong> </br>

          <a href = " <?php echo site_url("team/new"); ?> " id = "create_new_team" class = "btn btn-margin btn-yellow"> Create New Team </a>
        </div>
    </div>
  
</div>

