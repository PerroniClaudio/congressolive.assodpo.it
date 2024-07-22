<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../components/config.php';

$response = array(
    "errors" => 0,
    "error_code" => "",
    "error_message" => ""
);


try {
    
    $db->beginTransaction();

    $usernameq = $db->quote($_POST['username']);
    $check_email = $db->query("SELECT * FROM users WHERE username = $usernameq");

    
    if($check_email->rowCount() == 0){


        $acconsento_assodpo = 0;
        


        $sql = "INSERT INTO users 
                (username,password,type,ipreg,name,surname,prof,disc,profid,discid,assodpo,acconsento_assodpo) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            $_POST['username'],
            $_POST['password'],
            'std',
            $ip,
            $_POST['name'],
            $_POST['surname'],
            'OPERATORE NON SOGGETTO A ECM',
            'Operatore non soggetto a ecm',
            31,
            118,
            $_POST['assodpo'],
            $acconsento_assodpo
        ));

        $uid = $db->lastInsertId();
        $now = new DateTime();
        $ymd = $now->format('Y-m-d');
        $year = $now->format('Y');

        $sql = "INSERT INTO courses_usr (uid,crsno,note,validfrom,validtill,status) VALUES (?,?,?,?,?,?)";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            $uid,
            $event_code,
            "Iscritto da eventilive.assodpo.it",
            $ymd,
            "2030-01-01",
            "Confermato"
        ));

        $crsnoq = $db->quote($event_code);
        $live = $db->query("SELECT * FROM courses,live_streams,courses_live WHERE courses_live.crsno = $crsnoq AND courses.crsno = $crsnoq AND live_streams.id = courses_live.live_id")->fetch();


        $sql_live = "SELECT * FROM courses,live_streams,courses_live WHERE courses_live.crsno = :crsno AND courses.crsno = :crsno AND live_streams.id = courses_live.live_id";
        $statement_live = $db->prepare($sql_live);
        $statement_live->execute(array(
            "crsno" => $event_code
        ));

        $live = $statement_live->fetch();

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

        //sendMail( $_POST['username'], "{$_POST['name']} {$_POST['surname']}","Registrazione Congresso ASSO DPO $year", $mail_corpo);

        echo json_encode($response);
    }else{
        $response['errors'] = 1;
        $response['error_code'] = "email";
        $response['error_message'] = "Esiste già un utente con questo indirizzo email.";
        echo json_encode($response);
        exit();
    }

    $db->commit();


} catch (Exception $e) {

    $db->rollBack();

    $response['errors'] = 1;
    $response['error_code'] = "generic";
    $response['error_message'] = $e->getMessage();
    echo json_encode($response);
}


