<div class = "row" >
  <div class="main">
      <?php
        echo $team_profile_buttons;
        echo $team_edit_button;
      ?>
      <?php echo $profile_pic_url ?>

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
    <div class = "line">
      Name: <span class = "profile_data_item"> <?php echo $team_name; ?> </span>
    </div>
    <div class = "line">
      description: <span class = "profile_data_item"> <?php echo $description; ?> </span>
    </div>
   </div>

   <div class="sidebar">
   <div class="well">
    <?php $this->load->view("layouts/gameinfo"); ?>
   </div>
   </div>
</div>
