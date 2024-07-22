<?php 

include '../components/config.php';

try {

    $sql = "UPDATE system_sessions SET connected = ? WHERE sesscode = ?";
    $statement = $db->prepare($sql);
    $statement->execute(array(
        0,
        $_SESSION['sesscode']
    ));
    session_unset();
    echo "success";

} catch (Exception $e) {
    echo $e->getMessage();
}

