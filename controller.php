<?php 

include './components/config.php';

$now = new DateTime();
$timestamp = explode(" ",$now->format("Y-m-d H:i:s"));
$date = $db->quote($timestamp[0]);
$time = $db->quote($timestamp[1]);

$live_query = $db->query("SELECT id FROM live_streams WHERE appointment_day = $date AND appointment_end_at > $time AND id = 112");

if($live_query->rowCount() > 0){

    //Se c'è una live attiva al momento lo manda al player
?>
    <script>
        window.location = './player.php'
    </script>
<?php

}else{

    //Se non c'è una live attiva lo manda a una pagina locandina
?>
    <script>
        window.location = './locandina.php'
    </script>
<?php

}

?>