<div class="row-fluid">
    <div class="span8">
        <div class="well">
            <h1>Humans vs Zombies <small> Dead of Winter </small></h1>
            <div class="main">
               <div id = "title">
               <?php echo $home_banner ?></div>
                  <div class="row-fluid">
                  <div id = "graphbox">
                     <div class="info_box">
                        <div id = "label">
                        <h2> Player Count: </h2>
                        </div>
                        <div id = "homepage_data">
                           <div id = "graph2" class = "homepage_graph">
                              <span style = "font-size: 50px;"><?php echo $count; ?> </span>
                           </div>
                           <div id = "graph1" class = "homepage_graph"></div>
                        </div>
                     </div>
                     <div class="info_box">
                     <?php echo $home_content ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      </div>
    <div class="span4">
        <div class="well sidebar-nav">
            <ul class="nav nav-list">

            <div class="sidebar">
               <h3>Info</h3>
               <div class = "infoitem">
                  <b> Game Play:</b> <br>
                  Feb 6th - Feb 12th
               </div>
               <div class = "tinyline"></div>
               <div class = "infoitem">
                  <b> Registration Deadline:</b><br>
                  Jan 27th
               </div>
               <div class = "tinyline"></div>
               <div class = "infoitem">
                  <b> Orientation Dates:</b><br>
                  Jan 30th  and Feb 3rd<br>
                  Ag Sci 106, 6:00 pm
               </div>
               <div class = "tinyline"></div>
               <div class = "infoitem">
                  <b> Contact:</b><br>
                  <a href = "mailto:UofIHvZ@gmail.com"> UofIHvZ@gmail.com </a> <br>
                  <a href = "http://www.facebook.com/groups/194292097284119/"> Facebook Group </a>
               </div>
               <div class = "tinyline"></div>
               <div class = "infoitem">
                  <a href = "https://docs.google.com/open?id=1vYy1nVvFoE3HOjKs7olWDFl-rgW2eXIfp4Ms7_nyVqetArbXm6x8OD5MQh2l"> Rules </a>
               </div>
            </div>
         </ul>
      </div>
   </div>
</div>


  <script type="text/javascript">
   var chart;
   $(document).ready(function() {
      chart = new Highcharts.Chart({
         chart: {
            renderTo: 'graph1',
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
                  name: 'Male',    
                  y: <?php echo $male; ?>,
                  color: "#39869B"

                },
               {
                  name: 'Female',    
                  y: <?php echo $female; ?>,
                  color: "#46A1B9"

               },
               {
                  name: 'Other',    
                  y: <?php echo $other; ?>,
                  color: "#7CBBCF"
               },
               {
                  name: 'No Response',    
                  y: <?php echo $noresponse; ?>,
                  color: "#B5D4E0"

               }

            ]
         }]
      });
   });
  </script>


