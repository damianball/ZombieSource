 <script type="text/javascript" src="js/tablesort.js"></script>

 <!-- <link rel="stylesheet" href="css/table_style.css">
-->
  <!--<div id = "sortable_tables" >
   <!-- <div class = "hvz_table" > -->

<h1> <?php echo $game_name; ?> </h1>
<hr>

<div class="row-fluid">
  <?php
    $data["slug"] = $url_slug;
    $this->load->view("layouts/game_sidebar", $data); 
  ?>
  <div class="span10">
      <?php echo $game_table; ?>
  </div>
</div>


      

