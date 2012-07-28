<div class = "row" >
   <div class="main">
  <div id = "player_status" class = "alert-message
   <?php
    if($status == 'zombie'){
      echo "danger";
    }else{
      echo "warning";
    }
    ?>
    ">
    <?php echo $status; ?>
  </div>

  <div id = "gravatar">
    <?php echo $profile_pic_url ?> </br>
  </div>
  <div class = "line">
    Username: <span class = "profile_data_item"> <?php echo $username; ?> </span>
  </div>
  <div class = "line">
    Age: <span class = "profile_data_item"> <?php echo $age; ?> </span>
  </div>   <div class = "line">
    Gender: <span class = "profile_data_item"> <?php echo $gender; ?> </span>
  </div>
  <div class = "line">
    Major: <span class = "profile_data_item"> <?php echo $major; ?> </span>
  </div>
  <div class = "line"> Team: <?php echo $link_to_team; ?></div>

   </div>
   <div class="sidebar">
   <div class="well">
      <?php $this->load->view("layouts/gameinfo"); ?>
   </div>
   </div>
</div>
