<?php echo $current_game?><br><br>

<h2> All Games </h2>
<hr>

<div class="row-fluid">
  <div class="span12">
    <div class="well">
    	<!-- game name -->
    	<h3> FALL </h3> 
    	<div class="row-fluid">
	    	<div class="span3">
	    		
	    		PICTURE


	    	</div>
			<div class="span7">
				This is a super awesome description! aksdjflkasdlksfasld
				alkdfjladfa;lsdjfalkdsfja;sldjfalsjfaldska;lsdjfalsdkj;asldf
				aldsjflksjdf;aldskjfa;slfa;lsfka;sldjf;aldkfa;lkfa;sdk
				a;sldkja;lsja;lkjfa;ljsa;lkfd.
			</div>
			<div class=" span2">
        <button class="btn btn-info join_game" data-gameid="b6d6f14a-d9c4-11e1-a3a8-5d69f9a5509e">Join Game</button>
        <button class="btn btn leave_game" data-gameid="b6d6f14a-d9c4-11e1-a3a8-5d69f9a5509e">Leave Game</button>

			</div>
		</div>
		<br>
		<div class="row-fluid">
			<div class="span2"></div>
			<div class="span2">
		        <div class="alert alert-blue"> Players: <?php echo $count; ?> </div>
		     </div>
		     <div class="span2">
		        <div class="alert alert-green"> Humans: <?php echo $human_count; ?> </div>
		     </div>
		     <div class="span2">
		        <div class="alert alert-yellow"> Zombies: <?php echo $zombie_count; ?> </div>
		     </div>
		     <div class="span3">
		        <div class="alert alert-red"> Starved Zombies: <?php echo $zombie_count; ?> </div>
		     </div>
		  </div>
	 </div>	   
  </div>
</div>

<div class="row-fluid">
  <div class="span12">
    <div class="well">
      <!-- game name -->
      <h3> Dead of Winter </h3> 
      <div class="row-fluid">
        <div class="span3">
          
          PICTURE


        </div>
      <div class="span7">
        This is a super awesome description! aksdjflkasdlksfasld
        alkdfjladfa;lsdjfalkdsfja;sldjfalsjfaldska;lsdjfalsdkj;asldf
        aldsjflksjdf;aldskjfa;slfa;lsfka;sldjf;aldkfa;lkfa;sdk
        a;sldkja;lsja;lkjfa;ljsa;lkfd.
      </div>
      <div class=" span2">
        <button class="btn btn-info join_game" data-gameid="9a051bbc-3ebc-11e1-b778-000c295b88cf">Join Game</button>
        <button class="btn leave_game" data-gameid="9a051bbc-3ebc-11e1-b778-000c295b88cf">Leave Game</button>
      </div>
    </div>
    <br>
    <div class="row-fluid">
      <div class="span2"></div>
      <div class="span2">
            <div class="alert alert-blue"> Players: <?php echo $count; ?> </div>
         </div>
         <div class="span2">
            <div class="alert alert-green"> Humans: <?php echo $human_count; ?> </div>
         </div>
         <div class="span2">
            <div class="alert alert-yellow"> Zombies: <?php echo $zombie_count; ?> </div>
         </div>
         <div class="span3">
            <div class="alert alert-red"> Starved Zombies: <?php echo $zombie_count; ?> </div>
         </div>
      </div>
   </div>    
  </div>
</div>


 <script type="text/javascript">
   var chart;
  $(document).ready(function(){
    $(".join_game").click(function(event){
      debugger;
      alert("cool");
    });
  });

  $(document).ready(function(){
    $(".leave_game").click(function(event){
      alert("leave");
    });
  });


   $(document).ready(function() {
      chart = new Highcharts.Chart({
         chart: {
            renderTo: 'chart1',
            plotBackgroundColor: "transparent",
         },
         title: {
            text: ""
         },
         tooltip: {
            formatter: function() {
               return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
            }
         },
         plotOptions: {
            pie: {
               allowPointSelect: false,
               cursor: 'pointer',
               dataLabels: {
                  enabled: false
               },
               showInLegend: true
            }
         },
          series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                {
                  name: 'Human',    
                  y: <?php echo $human_count; ?>,
                  color: "#04819e"

                },
               {
                  name: 'Zombie',    
                  y: <?php echo $zombie_count; ?>,
                  color: "#FF4500"

               },
               {
                  name: 'Starved Zombie',    
                  y: <?php echo $starved_zombie_count; ?>,
                  color: "#000 "
               }
            ]
         }]
      });
   });
  </script>