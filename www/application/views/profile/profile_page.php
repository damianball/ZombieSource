<div class="row-fluid">
  <div class="span8">
    <div class="well-profile">
      <div class = "line">
        <span class = "profile_data_item"> <h3><?php echo $username; ?></h3></span>
      </div>
      <div class="row-fluid">
        <div class="span3">

          <div id = "gravatar" class="thumbnail">
            <?php echo $profile_pic_url ?>
          </div>
          <br>
          <div class="row-fluid">
              <a href=" <?php echo site_url("profile/edit_profile"); ?> " id = "edit_profile">
                <div class="btn btn-info btn-mid">
                    Edit Profile
                </div>
              </a>

          </div>

              <?php
                if($phone){
                  echo'
                    <a href="';
                    echo site_url("profile/sms_settings");
                    echo '" id = "sms_settings">
                <div class="btn btn-light-blue btn-margin btn-mid">
                   Edit SMS Settings
                </div>
              </a>'
                  ;
                }
                ?>



        </div>
        <div class="span9">
          <div class="font-profile">
            <dl class="dl-horizontal">
              <dt>Game</dt>
                <dd><?php echo $game_name ?></dd>
              <dt>Status </dt>
              <div id = "player_status" class = "alert-message">
                <?php
                  if($status == 'zombie'){
                  #  echo "danger";
                    echo "
                    <dd><span class=\"label label-warning\">";
                      echo $status;
                    echo "</span></dd>";

                  }else if($status == 'starved zombie'){
                  #  echo "warning";
                    echo "
                    <dd><span class=\"label label-important\">";
                      echo $status;
                    echo "</span></dd>";
                  }else{
                    echo "
                    <dd><span class=\"label label-success\">";
                      echo $status;
                    echo "</span></dd>";
                  }

                ?>
              </div>
              <dt>Email </dt><span class = "profile_data_item"> <dd><?php echo $email; ?> <gray-font>(not public)</gray-font></dd></span>
              <dt>Age </dt> <span class = "profile_data_item"> <dd><?php echo $age ? $age : "not given"; ?></dd></span>
              <dt>Gender </dt> <span class = "profile_data_item"> <dd><?php echo $gender ? $gender : "not given"; ?></dd> </span>
              <dt>Major </dt><span class = "profile_data_item"> <dd><?php echo $major ? $major : "not given"; ?></dd> </span>
              <dt>Team </dt> <dd><?php echo $link_to_team ? $link_to_team : "No Team"; ?><dd>
              <dt>SMS </dt><span class = "profile_data_item"> <dd><?php echo $phone ? $phone : "not given"; ?> <gray-font>(not public)</gray-font></dd></span>

            </dl>
          </div>
        <?php if($human_code){ ?>
        <div id="human_code" class="pull-right">
            <h4 style="text-align: center;">Human Code</h4>
            <br>
            <a href="<?php echo site_url('profile/print_human_code'); ?>">
            <div class="btn btn-large btn-yellow">
                <h1><big><?php echo $human_code ?></big></h1>
            </div>
            </a>
        </div>
        <?php } ?>
        </div>
      </div>
    </div>
        <?php
                if(count($achievements) > 0){
                    echo "<div class=\"well\">";
                    echo '<h3>Achievements</h3>';
                    foreach($achievements as $ach){
                        $img = $ach['image_url'];
                        $desc = $ach['description'];
                        $name = $ach['name'];
                        echo "<div class=\"achievement team_member\" rel=\"tooltip\" title=\"$desc\">";
                        echo "<img src=\"$img\" class=\"twtr-pic\">";
                        echo "<p>$name</p>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
        ?>

  </div>

    <div class="span4">
        <div class="well">
        <?php $this->load->view("layouts/gameinfo"); ?>
      </div>
    </div>


</div>

              <?php
                if(!$phone){
                  echo'
                    <div class="row-fluid sms_settings_box well">
                    <h3>Do you have Texting?</h3>
                    <dd> Setup an <strong>SMS Number</strong> to: </dd>
                    <li> Tag via text </li>
                    <li> Recieve optional game updates </li>
                    <li> More! </li>';
                    echo '<a href="';
                    echo site_url("profile/sms_settings");
                    echo' " id = "sms_settings">
                <div class="btn btn-light-blue btn-margin">
                    SMS Setup/FAQ
                </div>
              </a>';
                }
                ?>
                </div>
          </div>

<script>
    $('.achievement').tooltip();
</script>
