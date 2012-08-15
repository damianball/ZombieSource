 <script type="text/javascript" src=" <?php echo base_url("js/tablesort.js"); ?> "></script>
 <!-- <link rel="stylesheet" href="<?php echo base_url("css/table_style.css"); ?>" > -->
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
          To join a team, click their name to visit their profile.</br></br>
          Please make sure you are welcome on a team before you join.</br>
          <?php 
            if($is_closed == FALSE){
              echo "<br>";
              echo "<strong> Think you can do Better? </strong> </br>";

              echo "<a href="; 

              echo site_url("team/new");
              echo "id = \"create_new_team\" class = \"btn btn-margin btn-yellow\"> Create New Team </a>";
            }
          ?>
        </div>
    </div>
  
</div>

