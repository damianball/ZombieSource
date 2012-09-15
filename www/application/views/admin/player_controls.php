

<!-- <div id = "left_control_items">
    <div class = "control_item">
        <button id = "left_manage" class = "btn danger">Ban Hammer</button>
        <button id = "left_manage" class = "disabled btn danger">Lift Ban Hammer</button>

    </div>
-->
<!--
    <div class = "control_item">
        <button id = "left_manage" class = "btn info"> Make OZ</button>
        <button id = "left_manage" class = "disabled btn info"> Revoke OZ status</button>

    </div>
</div> -->
<div class="well">

  <h3> Player: <?php echo $username; ?> </h3>
  <div class="row-fluid">
  <div class="row-fluid">
<div class="span12">
    <p> <b>Player Status:</b>
                <?php
                  if($status == 'zombie'){
                  #  echo "danger";
                    echo "
                    <span class=\"label label-warning\">";
                      echo $status;
                    echo "</span>";

                  }else if($status == 'starved zombie'){
                  #  echo "warning";
                    echo "
                    <span class=\"label label-important\">";
                      echo $status;
                    echo "</span>";
                  }else{
                    echo "
                    <span class=\"label label-success\">";
                      echo $status;
                    echo "</span>";
                  }

                echo "</p>";

                if($human_code){
                    echo "<p><b>Human code:</b> $human_code</p>";
                }
                ?>
</div>
  </div>
    <div class="span10">
      <div id = "right_control_items">

        <div class="row-fluid">
          <div class="span4">
            <div class="control_item" class="change_active_div">
                <button id = "change_active" class = "btn btn-margin btn-long" value="<?php echo $playerid;?>">
                <?php echo $active_button_text;?></button>
            </div>
          </div>
        </div>

        <div class="row-fluid">
          <div class="span4">
            <div class="control_item" class="make_mod_div">
                <button id = "make_mod" class = "btn btn-margin btn-long" value="<?php echo $playerid;?>">
                <?php echo $moderator_button_text;?></button>
            </div>
          </div>
        </div>

          <div class="row-fluid">
            <div class="span4">
              <div id = "free_feed_div" class = "control_item">
                  <button id = "free_feed" value = <?php echo $playerid; ?> class = "<?php echo $feed_disabled; ?> btn btn-margin btn-long" <?php echo $feed_disabled; ?> > Grant Free Zombie Feed</button>
              </div>
            </div>
            <div class="span8">
              <?php if($feed_message){ ?>
                <div class="alert alert-inline fade in">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <?php echo $feed_message; ?>
                </div>
              <?php } ?>
          </div>
          </div>

          <div class="row-fluid">
            <div class="span4">
              <div id = "undo_tag_div" class = "control_item">
                  <button id = "undo_tag" value = <?php echo $playerid; ?> class = "<?php echo $undo_tag_disabled; ?> btn btn-margin btn-long" <?php echo $undo_tag_disabled; ?> > Undo Tag </button>
              </div>
            </div>
            <div class="span8">
              <?php if($undo_tag_message){ ?>
                <div class="alert alert-inline fade in">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <?php echo $undo_tag_message; ?>
                </div>
              <?php } ?>
            </div>
          </div>

      </div>
    </div>
  </div>

<script type="text/javascript">
$(document).ready(function(){
  $("#make_mod").click(function(){
      $.post('admin/make_mod',
            {player:$('#make_mod').val()},
            function(data){$('#make_mod').html(data)}
      );
  });
});

$(document).ready(function(){
  $("#change_active").click(function(){
      $.post('admin/change_active',
            {player:$('#change_active').val()},
            function(data){$('#change_active').html(data)}
      );
  });
});

$(document).ready(function(){
  $("#free_feed").click(function(){
    $("#free_feed_div").load('admin/free_feed',{player:$('#free_feed').val()});
  });
});

$(document).ready(function(){
  $("#undo_tag").click(function(){
    $("#right_control_items").load('admin/undo_tag',{player:$('#undo_tag').val()});
  });
});
</script>

<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-alert.js"></script>
