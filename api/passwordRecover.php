<?php 

include '../components/config.php';

$response = array(
    "errors" => 0,
    "error_code" => "",
    "error_message" => ""
);

try{

    $usernameq = $db->quote($_POST['username']);
    $check_user = $db->query("SELECT uid,name,surname FROM users WHERE username = $usernameq");

    if($check_user->rowCount() == 1){
        $password_code = generateRandomString(10);
        $user = $check_user->fetch();
        $now = new DateTime();

        $sql = "UPDATE users SET password_code = ?, password_code_date = ? WHERE uid = ?";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            $password_code,
            $now->format('Y-m-d H:i:s'),
            $user['uid']
        ));

        $mail_corpo = "
            <p>È stato richiesto il recupero della password sul sito del congresso ASSODPO, se non è stato lei a richiederlo è pregato di ignorare la seguente mail</p>
            <p>Per poter impostare una nuova password è necessario accedere al seguente link:</p>
            <p>
                <a href=\"https://eventilive.assodpo.it/recupera-password.php?pid=$password_code\">https://eventilive.assodpo.it/recupera-password.php?pid=$password_code</a>
            </p>

            <p>Il link ha la durata di un'ora, se la password non verrà sostituita durante questo periodo sarà necessario effettuare nuovamente la richiesta.</p>
        ";

        sendMail( $_POST['username'], "{$user['name']} {$user['surname']}","Recupero password Assodpo", $mail_corpo);

        echo json_encode($response);
    }else{
        $response['errors'] = 1;
        $response['error_code'] = "notfound";
        $response['error_message'] = "Nessun utente trovato";
        echo json_encode($response);

        exit();
    }

}catch(Exception $e){

    $response['errors'] = 1;
    $response['error_code'] = "generic";
    $response['error_message'] = $e->getMessage();
    echo json_encode($response);

}