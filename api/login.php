<?php 

include '../components/config.php';

$response = array(
    "errors" => 0,
    "error_code" => "",
    "error_message" => ""
);


try {

    $usernameq = $db->quote($_POST['username']);
    $passwordq = $db->quote($_POST['password']);
    $check_login = $db->query("SELECT * FROM users WHERE username = $usernameq AND password = $passwordq");

    if($check_login->rowCount() == 1){
        $user = $check_login->fetch();
        
        $crsnoq = $db->quote($event_code);

        $check_iscrizione = $db->query("SELECT * FROM courses_usr WHERE uid = {$user['uid']} AND crsno = $crsnoq");

        if($check_iscrizione->rowCount() > 0) {
            $code = $_SESSION['sesscode'];

            $now = new DateTime();
            $timestamp = $now->format('Y-m-d H:i:s');
    
            $sql = "UPDATE system_sessions SET sessdate = ?, connected = ?, uid = ? WHERE sesscode = ?";
            $statement = $db->prepare($sql);
            $statement->execute(array(
                $timestamp,
                1,
                $user['uid'],
                $code
            ));
    
            $_SESSION['uid'] = $user['uid'];
    
            echo json_encode($response);
        }else{
            $response['errors'] = 1;
            $response['error_code'] = "notsubbed";
            $response['error_message'] = "Utente non iscritto";
            echo json_encode($response);
        }



    }else{
        $response['errors'] = 1;
        $response['error_code'] = "notfound";
        $response['error_message'] = "Mail e password errati";
        echo json_encode($response);
    }



} catch (Exception $e) {
    $response['errors'] = 1;
    $response['error_code'] = "generic";
    $response['error_message'] = $e->getMessage();
    echo json_encode($response);
}