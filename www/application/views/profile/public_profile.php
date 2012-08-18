<div class="row-fluid">
  <div class="span8">
    <div class="well">
      <div class = "line">
        <span class = "profile_data_item"> <h3><?php echo $username; ?></h3></span>
      </div>
      <div class="row-fluid">
        <div class="span3">

          <div id = "gravatar" class="thumbnail">
            <?php echo $profile_pic_url ?>
          </div>
          <br>
        </div>
        <div class="span9">
          <div class="font-profile">
            <dl class="dl-horizontal">
              <dt>Game</dt>
                <dd><?php echo $game_name ?></dd>
              <dt>Status </dt>
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
              <dt>Age </dt> <span class = "profile_data_item"> <dd><?php echo $age ? $age : "not given"; ?></dd></span>
              <dt>Gender </dt> <span class = "profile_data_item"> <dd><?php echo $gender ? $gender : "not given"; ?></dd> </span>
              <dt>Major </dt><span class = "profile_data_item"> <dd><?php echo $major ? $major : "not given"; ?></dd> </span>
              <dt>Team </dt> <dd><?php echo $link_to_team; ?><dd>
            </dl>
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

