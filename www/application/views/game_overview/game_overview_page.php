
<h2> All Games </h2>
<hr>
<!-- START GAMES LOOP -->
<?php foreach($game_data as $game){?>
	<div class="row-fluid">
	  <div class="span12">
	    <div class="well">
	    	<!-- game name -->
	    	<h3> <?php echo $game["game_name"]?> </h3> 
	    	<div class="row-fluid">
		    	<div class="span3">
		    		<img src="http://fineartamerica.com/images-medium/winter-tree-silhouette-john-stephens.jpg">
		    		<!-- PICTURE -->


		    	</div>
				<div class="span7">
					This is a super awesome description! aksdjflkasdlksfasld
					alkdfjladfa;lsdjfalkdsfja;sldjfalsjfaldska;lsdjfalsdkj;asldf
					aldsjflksjdf;aldskjfa;slfa;lsfka;sldjf;aldkfa;lkfa;sdk
					a;sldkja;lsja;lkjfa;ljsa;lkfd.
				</div>
				<div class=" span2">
					<button class="btn btn-info">Join Game</button>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span2"></div>
				<div class="span2">
			        <div class="alert alert-blue"> Players: <?php echo $game["count"]; ?> </div>
			     </div>
			     <div class="span2">
			        <div class="alert alert-green"> Humans: <?php echo $game["human_count"]; ?> </div>
			     </div>
			     <div class="span2">
			        <div class="alert alert-yellow"> Zombies: <?php echo $game["zombie_count"]; ?> </div>
			     </div>
			     <div class="span3">
			        <div class="alert alert-red"> Starved Zombies: <?php echo $game["zombie_count"]; ?> </div>
			     </div>
			</div>
		</div>
	  </div>
	</div>
<?php } ?>

<!-- END GAMES LOOP -->

