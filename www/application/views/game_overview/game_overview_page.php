<h2> All Games </h2>
<hr>
<!-- START GAMES LOOP -->
<?php foreach($game_data as $game){?>
	<div class="row-fluid">
	  <div class="span12">
	    <div class="well">
	    	<!-- game name -->
	    	<a href = "<?php echo base_url() . 'game/' . $game["game_slug"]?>"> <h3><?php echo $game["game_name"]?> </h3> </a>
	    	<div class="row-fluid">
		    	<div class="span3 ">
		    		<img src="<?php echo $game["game_photo_url"]?>">
		    		<!-- PICTURE -->


		    	</div>
				<div class="span7">
	         <?php echo $game["game_description"]?>
				</div>
				<div class=" span2 game_options" id="<?php echo $game["gameid"]?>">
          <?php echo $game["game_options"]?>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span2"></div>
				<div class="span2">
			        <div class="alert alert-blue"> Players: <?php echo $game["count"]; ?> </div>
			     </div>
			</div>
		</div>
	  </div>
	</div>
<?php } ?>

<!-- END GAMES LOOP -->

 <script type="text/javascript">
  $(document).ready(function(){

    //choose the right modal by game id
    //TODO dynmaically add gameid to modal button
    //instead of setting it in active_game_options.php

    $(document).on("click", ".leave_game_modal", function(event){
      gameid = $(event.target).data("gameid");
      $('#' + gameid).find('#leave').modal('show');
    });


    $(document).on("click",".join_game", function(event){
      gameid = $(event.target).data("gameid");
      $.ajax({
        url: "overview/join_game",
        type: "POST",
        data: {gameid : gameid},
        success: function(data){
          response = JSON.parse(data)
          $('#'+gameid + '.game_options').html($(response.replacementView))
        }
      });
    });

    $(document).on("click",".leave_game",  function(event){
      gameid = $(event.target).data("gameid");
      $.ajax({
        url: "overview/leave_game",
        type: "POST",
        data: {gameid : gameid},
        success: function(data){
          response = JSON.parse(data)
          $('#'+gameid + '.game_options').html($(response.replacementView))
        }
      });
    });

  });
 </script>
 <script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-modal.js"></script>

