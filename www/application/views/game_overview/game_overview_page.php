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

		    	<div class="span3">
		    		<a href="<?php echo base_url() . 'game/' . $game["game_slug"]?>">
		    			<img class="thumbnail" src="<?php echo $game["game_photo_url"]?>">
		    		</a>
          </div>
        
		    	<div class="span3 ">
		    	</div>
        
          <div class="span3 ">
          </div>
        
          <div class="span3 ">
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
				<div class="span3">
			        <div class="alert alert-blue"> Players: <?php echo $game["count"]; ?> </div>
			     </div>

				<div class="span3">
			        <div class="alert alert-green"> Humans: <?php echo $game["human_count"]; ?> </div>
			     </div>

				<div class="span3">
			        <div class="alert alert-yellow"> Zombies: <?php echo $game["zombie_count"]; ?> </div>
			     </div>

				<div class="span3">
			        <div class="alert alert-red"> Starved Zombies: <?php echo $game["starved_zombie_count"]; ?> </div>
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

    $(document).on("click", ".join_game_modal", function(event){
      gameid = $(event.target).data("gameid");
      $('#' + gameid).find('#join').modal('show');
    });


    $(document).on("click",".join_game", function(event){
      if( $('#' + gameid).find('.join_game_sign_waiver') ){
        waiversigned = $('#' + gameid).find('[name]="waiversigned"').is(':checked')
      }

      if( $('#' + gameid).find('.join_game_edit_profile') ){
        age    = $('#' + gameid).find('[name="age"]').val()
        major  = $('#' + gameid).find('[name="major"]').val()
        gender = $('#' + gameid).find('[name="gender"]').val()
      }

      original_zombie = $('#' + gameid).find('[name="originalzombiepool"]').is(':checked')
    
      if(waiversigned){
        gameid = $(event.target).data("gameid");
        params = {};
        params["gameid"] = gameid;
        params["waiversigned"] == true;
        if(age){ params["age"] = age };
        if(major){ params["major"] = major };
        if(gender){ params["gender"] = gender };
        if(original_zombie){ params["originalzombiepool"] = original_zombie };

        $.ajax({
          url: "overview/join_game",
          type: "POST",
          data: params,
          success: function(data){
            response = JSON.parse(data)
            $('#'+gameid + '.game_options').html($(response.replacementView))
          }
        });
      }else{
        //TODO something if they don't check the box
      }
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

