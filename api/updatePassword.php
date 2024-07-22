<?php 

include '../components/config.php';

$response = array(
    "errors" => 0,
    "error_code" => "",
    "error_message" => ""
);

try{

    $password_code = $db->quote($_POST['password_code']);

    $user_check = $db->query("SELECT uid,password_code_date FROM users WHERE password_code = $password_code");

    if($user_check->rowCount() == 1){

        $user = $user_check->fetch();

        $pw_code_date = DateTime::createFromFormat("Y-m-d H:i:s",$user['password_code_date']);
        $now = new DateTime();
        $diff = $now->getTimestamp() - $pw_code_date->getTimestamp();

        if($diff > 3600){
            $response['errors'] = 1;
            $response['error_code'] = "expired";
            $response['error_message'] = "Link scaduto";
            echo json_encode($response);
            exit();
        }

        $sql = "UPDATE users SET password = ? WHERE uid = ?";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            $_POST['password'],
            $user['uid']
        ));

        echo json_encode($response);


    }else{
        $response['errors'] = 1;
        $response['error_code'] = "notfound";
        $response['error_message'] = "Codice non valido";
        echo json_encode($response);

        exit();
    }



}catch(Exception $e){

    $response['errors'] = 1;
    $response['error_code'] = "generic";
    $response['error_message'] = $e->getMessage();
    echo json_encode($response);

}