<h2> All Games </h2>
<hr>

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
				<button class="btn btn-info">Join Game</button>
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