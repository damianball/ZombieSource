<?php if(!$is_player_in_game){ ?>
<div class="alert fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>You aren't in a game!</strong>
         You can't play until you join a game. Go to the
         <a href="<?php echo site_url('overview')?>">Game Overview</a> page to join a game.
    </div>
    <?php } ?>

<h1> <?php echo $game_name; ?>
 <!-- Check if game is closed and style accordingly  -->
<?php
  if($is_closed){
    echo "<small> (Closed)</small></h1>";
  }else{
    echo "</h1>";
  }
?>

<hr>
<h2> Newsfeed </h2>
<br>
<div class="row-fluid">
  <?php
    $data["slug"] = $url_slug;
    $this->load->view("layouts/game_sidebar", $data);
  ?>
  <div class="span10">
    <table id="newsfeed" class="table table-striped" border="0" cellpadding="4" cellspacing="0"></table>
      </div>
</div>
<!-- <script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script> -->
<script>
 //var twtr_search = <?php echo $twitter_search ?>;
newsfeed_url = "<?php echo $newsfeed_url?>";
</script>


<script type="text/javascript" src="<?php echo base_url();?>js/newsfeed.js"/>
