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
<h2> Zombie Family Tree</h2>
<br>
<div class="row">
    <?php
      $data["slug"] = $url_slug;
      $this->load->view("layouts/game_sidebar", $data);
    ?>
<div class="span10">
<div id="chart"></div>
</div>
</div>



<script src="//cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.min.js"></script>
      <script>json_path = "<?php echo base_url()?>json/<?php echo $url_slug;?>.json";</script>
<script type="text/javascript" src="<?php echo base_url()?>js/zombietree.js"></script>
<style>
.tooltip {
    margin-left: 15px;
}

path.link {
  fill: none;
  stroke: #ccc;
  stroke-width: 1.5px;
}
</style>

