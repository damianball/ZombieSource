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
      <h3>Info</h3>
      <div class = "infoitem">
         <b> Game Play:</b> <br>
         Feb 6th - Feb 12th
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <b> Registration Deadline:</b><br>
         Jan 27th
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <b> Orientation Dates:</b><br>
         Jan 30th - Feb 3rd 
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <b> Contact:</b><br>
         <a href = "mailto:UofIHvZ@gmail.com"> UofIHvZ@gmail.com </a> <br>
         <a href = "http://www.facebook.com/groups/194292097284119/"> Facebook Group </a>
      </div>
      <div class = "tinyline"></div>
      <div class = "infoitem">
         <a href = "http://www.facebook.com/groups/194292097284119/"> Rules </a>
      </div>
   </div>
</div>
