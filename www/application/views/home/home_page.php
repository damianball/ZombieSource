<div class="well">
   <div class="row">
      <div class="span8">
      <h1>Humans vs Zombies <small><?php echo $game_name ?></small></h1>

         <div id = "title">
            <?php echo $home_banner ?>
         </div>
      </div>

      <div class="span2 pull-right">
        <?php echo $home_content ?>
      </div>
   </div>


</div>
<div class="row-fluid">
    <div class="span8">
        <div class="well">

            <div class="main">

                  <div class="row-fluid">
                  <div id = "graphbox">
                     <div class="info_box">
                        <div id = "label">
                          <div class="alert alert-blue"><h2> Player Count: </h2>
                          <span style = "font-size: 50px;"><?php echo $count; ?> </span>
                          </div>
                          <div id = "homepage_data">
                            </div>
                        </div>
                     </div>


                  </div>
            <div >
            <a href="<?php echo 'http://' . $tumblr_username . '.tumblr.com'; ?>">
                <h2>Announcements</h2>
            </a>
                <div id="tumblr-badge"></div>
            </div>
               </div>
            </div>
         </div>
      </div>
    <div class="span4">
        <div class="well">
            <div class="sidebar">
                <?php $this->load->view("layouts/gameinfo"); ?>
            </div>
      </div>
      <div>
<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'search',
  search: <?php echo $twitter_search?>,
  interval: 30000,
  title: 'Tweet with <?php echo $twitter_hashtag?> to join in!',
  width: 'auto',
  height: 350,
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
}).render().start();
</script>

      </div>
   </div>
</div>
<script type="text/javascript">
	var tumblrSettings = {
        userName : "<?php echo $tumblr_username; ?>", // Your Tumblr user name
        itemsToShow : <?php echo $tumblr_num_posts; ?>, // Number of Tumblr posts to retrieve
		itemToAddBadgeTo : "tumblr-badge", // Id of HTML element to put badge code into
		imageSize : 100, // Values can be 75, 100, 250, 400 or 500
		shortPublishDate : true, // Whether the publishing date should be cut shorter
		timeToWait : 2000 // Milliseconds, 1000 = 1 second
	};
</script>
  <script type="text/javascript" defer="defer" src="<?php echo base_url();?>js/tumblrBadge-1.1.js"/>

