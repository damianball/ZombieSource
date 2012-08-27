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
    echo "<script type=\"text/javascript\">";
    echo "$(\".container\")[1].style.opacity = 0.5;";
    echo "</script>";
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
<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'search',
  search: <?php echo $twitter_search?>,
  interval: 20000,
  title: 'Tweet with <?php echo $twitter_hashtag?> to join in!',
  width: 'auto',
  height: 755,
  theme: {
    shell: {
      background: '#DD4814',
      color: '#ffffff'
    },
    tweets: {
      background: '#ffffff',
      color: '#444444',
      links: '#DD4814'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: true,
    behavior: 'all'
  },
  ready: function(){
      console.log('READY');
  }
}).render().start();
</script>
  </div>
</div>


