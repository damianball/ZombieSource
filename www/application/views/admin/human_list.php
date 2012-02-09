<?php
    // @TODO: Gosh this sucks. Need to pull from the database.
    date_default_timezone_set('America/Los_Angeles');
?>
<h1> ALL Humans: as of <?php echo date("F j, Y, g:i a"); ?> </h2>

<?php
    foreach($human_names as $name){
        echo " $name </br>";
        echo "</br>";
    }
?>