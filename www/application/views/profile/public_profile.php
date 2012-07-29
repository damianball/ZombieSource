<div class="row-fluid">
  <div class="span8">
    <div class="well">
      <div class = "line"> 
        <span class = "profile_data_item"> <h3><?php echo $username; ?></h3></span>
      </div>
      <div class="row-fluid">
        <div class="span3">

          <div id = "gravatar"> 
            <?php echo $profile_pic_url ?><br><br>
          </div>
        </div>
        <div class="span9">
          <div class="font-profile">
          <dl class="dl-horizontal"> 
            <dt>Age </dt> <span class = "profile_data_item"> <dd><?php echo $age; ?></dd></span>
            <dt>Gender </dt> <span class = "profile_data_item"> <dd><?php echo $gender; ?></dd> </span>
            <dt>Major </dt><span class = "profile_data_item"> <dd><?php echo $major; ?></dd> </span>
            <dt>Team </dt> <dd><?php echo $link_to_team; ?><dd> 
          </dl>
</div>
          <div id = "player_status" class = "alert-message">
                     <?php 
                      if($status == 'zombie'){
                      #  echo "danger";
                        echo "<br><br><br>
                        <button class=\"btn btn-large btn-yellow disabled pull-right\" disabled=\"disabled\">";   
                          echo $status;
                        echo "</button>";

                      }else if($status == 'starved zombie'){
                      #  echo "warning";
                        echo " <br><br><br>
                        <button class=\"btn btn-large btn-red disabled pull-right\" disabled=\"disabled\">";  
                          echo $status;
                        echo "</button>";
                      }else{
                        echo " <br><br><br>
                        <button class=\"btn btn-large btn-success disabled pull-right\" disabled=\"disabled\">";  
                          echo $status;
                        echo "</button>";
                      }

                      ?>    
          </div>
        </div>
      </div>
    </div>
  </div>

    <div class="span4">
        <div class="well">
        <?php $this->load->view("layouts/gameinfo"); ?>
      </div>
    </div>


</div>

