<div class="row-fluid">
  <div class="span8">
    <div class="well">
      <div class = "line"> 
        <span class = "profile_data_item"> <h3><?php echo $username; ?></h3></span>
      </div>
      <div class="row-fluid">
        <div class="span4">

          <div id = "gravatar"> 
            <?php echo $profile_pic_url ?><br><br>
          </div>
          <div class="row-fluid">
            <div class="span6">
              <a href=" <?php echo site_url("profile/edit_profile"); ?> " id = "edit_profile" class = "btn btn-info"> Edit Profile</a>  
            </div>
            <div class="span4">

            </div>
          </div>
        </div>
        <div class="span4">
          <div class = "line"> 
            Email: <span class = "profile_data_item"> <?php echo $email; ?> (not public) </span>
          </div>  

          <div class = "line"> 
            Age: <span class = "profile_data_item"> <?php echo $age; ?> </span>
          </div>   

          <div class = "line"> 
            Gender: <span class = "profile_data_item"> <?php echo $gender; ?> </span>
          </div>

          <div class = "line"> 
            Major: <span class = "profile_data_item"> <?php echo $major; ?> </span>
          </div>

          <div class = "line"> Team: <?php echo $link_to_team; ?> </div>

          <div id = "player_status" class = "alert-message">
                     <?php 
                      if($status == 'zombie'){
                      #  echo "danger";
                        echo "<br><br><br>
                        <button class=\"btn btn-large btn-warning disabled\" disabled=\"disabled\">";   
                          echo $status;
                        echo "</button>";

                      }else{
                      #  echo "warning";
                        echo " <br><br><br>
                        <button class=\"btn btn-large btn-success disabled\" disabled=\"disabled\">";  
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

