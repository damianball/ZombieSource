

        <div class="page-header">
          <h1>Humans vs Zombies <small> Dead of Winter </small></h1>
        </div>
        <div class="row">
          <div class="span10">
            <h2> New here? You should 
                <button class="btn large success"> Register </button>
            </h2>
              <div class="span12">
               <div id = homepage_data>
                <div id = countdown></div>
                <div>
                  <div id = graph1 class = homepage_graph></div>
                  <div id = graph2 class = homepage_graph></div>
                  <div id = graph3 class = homepage_graph></div>
                </div>
               </div
              </div>          
          </div>
          <div class="span4">
            <h3>Info</h3>
            <div class = infoitem>
            <b> Game Play: </b> <br>
            Feb 6th - Feb 12th
            </div>
            
            <div class = infoitem>
            <b> Registration Deadline:  </b> <br>
            Jan 27th
            </div>

            <div class = infoitem>
            <b> Orientation Dates:  </b> <br>
            Jan 30th - Feb 3rd 
            </div>

            <div class = infoitem>
            UofIHvZ@gmail.com
            </div>

            <div class = infoitem>
            Facebook Group
            </div>

            <div class = infoitem>
            Rules
            </div>
 
          </div>
        </div>

      <script type="text/javascript" language="javascript"> 
      var chart;
$(document).ready(function() {
   chart = new Highcharts.Chart({
      chart: {
         renderTo: 'container',
         plotBackgroundColor: null,
         plotBorderWidth: null,
         plotShadow: false
      },
      title: {
         text: 'Browser market shares at a specific website, 2010'
      },
      tooltip: {
         formatter: function() {
            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
         }
      },
      plotOptions: {
         pie: {
            allowPointSelect: true,
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
            ['Firefox',   45.0],
            ['IE',       26.8],
            {
               name: 'Chrome',    
               y: 12.8,
               sliced: true,
               selected: true
            },
            ['Safari',    8.5],
            ['Opera',     6.2],
            ['Others',   0.7]
         ]
      }]
   });
});
      </script>

