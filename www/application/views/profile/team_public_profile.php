<div class = "row-fluid" >
  <?php $this->load->view("layouts/game_sidebar"); ?>
  <div class="span6">
    <div class="well">
      <div class="main">
        <div class="row-fluid">
          <h2><span class="profile_data_item"> <?php echo $team_name; ?> </span></h2>
          </div>
          <div class="row-fluid">
            <div class="span6">
              <?php echo $profile_pic_url ?>
            </div>
          </div>
          <br> 
          <div class="row-fluid">
              <?php
                if ($team_edit_button) {
                   echo "<div class=\"span3\">";
                    echo $team_edit_button;
                    echo "</div>";
                 } 
                ?>    
            
            <div class="span3">
                  <?php echo $team_profile_buttons;?>
                  
            </div>
          </div>
          
          <br>
          <div class = "line">
            <h4> Description </h4>
            <span class = "profile_data_item"> <?php echo $description; ?> </span>
          </div>
          <hr>
          <h4>Members</h4>
          <br>
          <div id = "teamlist">
            <?php
            foreach($members_list as $member){
              $team_member_photo = getGravatarHTML($member->getData('gravatar_email'), $member->getUser()->getEmail(), 50);
              $team_member_name = getHTMLLinkToProfile($member);
              echo "<div class = \"team_member\">
                        <div id = \"team_member_photo\" class = \"team_member_attribute\">
                            $team_member_photo
                        </div>
                        <div id = \"team_member_name\" class = \"team_member_attribute\">
                            $team_member_name
                        </div>
                      </div>
                    ";
              }
            ?>
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
