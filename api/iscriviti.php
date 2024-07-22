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
        $code = $_SESSION['sesscode'];

        $user = $check_login->fetch();

        $now = new DateTime();
        $timestamp = $now->format('Y-m-d H:i:s');
        $ymd = $now->format('Y-m-d');
        $year = $now->format('Y');

        $sql = "UPDATE system_sessions SET sessdate = ?, connected = ?, uid = ? WHERE sesscode = ?";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            $timestamp,
            1,
            $user['uid'],
            $code
        ));

        $_SESSION['uid'] = $user['uid'];

        $now = new DateTime();

        $sql = "INSERT INTO courses_usr (uid,crsno,note,validfrom,validtill,status) VALUES (?,?,?,?,?,?)";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            $user['uid'],
            $event_code,
            "Iscritto da eventilive.assodpo.it",
            $ymd,
            "2030-01-01",
            "Confermato"
        ));

        $crsnoq = $db->quote($event_code);
        $live = $db->query("SELECT * FROM courses,live_streams,courses_live WHERE courses_live.crsno = $crsnoq AND courses.crsno = $crsnoq AND live_streams.id = courses_live.live_id")->fetch();
        $appoday = DateTime::createFromFormat('Y-m-d',$live['appointment_day']);
        $giorno = $appoday->format('d/m/Y');
        $start_at = substr($live['appointment_start_at'],0,-3);
        $end_at = substr($live['appointment_end_at'],0,-3);

        $mail_corpo = "
            <p>
                Grazie per esserti iscritto all'evento {$live['crsname']}
            </p>

            <p>
                L'evento si terrà il giorno $giorno dalle ore $start_at alle ore $end_at
            </p>
        ";

        sendMail( $user['username'], "{$user['name']} {$user['surname']}","Registrazione Congresso ASSO DPO $year", $mail_corpo);

        echo json_encode($response);

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