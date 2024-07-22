<?php 

include '../components/config.php';

try {
    $db->beginTransaction();

    $now = new DateTime();
    $timestamp = explode(" ",$now->format("Y-m-d H:i:s"));
    $date = $db->quote($timestamp[0]);
    $time = $db->quote($timestamp[1]);
    $uid = $_SESSION['uid'];

    if($uid != ""){
        $live_query = $db->query("SELECT id FROM live_streams WHERE appointment_day = $date AND appointment_end_at > $time");

        if($live_query->rowCount() > 0){
            $live = $live_query->fetch();
            $tracker_table = "live_stream_tracker_".$live['id'];
    
            $check_lsu = $db->query("SELECT user_id FROM live_stream_users WHERE live_stream_id = {$live['id']} AND user_id = $uid");
    
            if($check_lsu->rowCount() == 0){
                //Insert dentro live_stream_users
                $insert_lsu_query = "INSERT INTO live_stream_users (user_id,live_stream_id) VALUES(?,?)";
                $statement = $db->prepare($insert_lsu_query);
                $statement->execute(array($uid,$live['id']));
            }else{
                //update di 15 secondi al record esistente
                $update_lsu_query = "UPDATE live_stream_users SET live_time = (live_time + 20) WHERE user_id = ? AND live_stream_id = ?";
                $statement = $db->prepare($update_lsu_query);
                $statement->execute(array($uid,$live['id']));
            }
    
            $check_tracker_table = $db->query("SELECT user_id FROM $tracker_table WHERE user_id = $uid AND DATE_ADD(created_at, INTERVAL 1 MINUTE) > CURRENT_TIMESTAMP");
            if($check_tracker_table->rowCount() == 0){
                //Se non c'è un record nell'ultimo minuto lo inserisce.
                $insert_tracker = "INSERT INTO $tracker_table (user_id) VALUES(?)";
                $statement = $db->prepare($insert_tracker);
                $statement->execute(array($uid));
            }
    
        }
    }

    $db->commit();
} catch (Exception $e) {
    echo $e->getmessage();
}