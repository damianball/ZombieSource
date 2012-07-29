
<h2> Player: <?php echo $username; ?> </h2>
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

<div id = "right_control_items">
    <div class = "control_item" id = "make_mod_div">
        <button id = "make_mod" class = "btn primary" value="<?php echo $playerid;?>">
        <?php echo $moderator_button_text;?></button>
    </div>

    <div id = "free_feed_div" class = "control_item">
        <button id = "free_feed" value = <?php echo $playerid; ?> class = "right_manage <?php echo $feed_disabled; ?> btn darkgreen" <?php echo $feed_disabled; ?> > Grant Free Zombie Feed</button> <?php echo $feed_message; ?>
    </div>

    <div id = "undo_tag_div" class = "control_item">
        <button id = "undo_tag" value = <?php echo $playerid; ?> class = "right_manage <?php echo $undo_tag_disabled; ?> btn darkgreen" <?php echo $undo_tag_disabled; ?> > Undo Tag </button> <?php echo $undo_tag_message; ?>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
  $("#make_mod").click(function(){
      $("#make_mod_div").load('admin/make_mod',{player:$('#make_mod').val(), make_mod:<?php echo !$is_mod;?>});
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
