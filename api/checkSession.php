<?php 

    //Da mettere solo nell'index. Se venisse inserito nel player causerebbe redirect infiniti.

    if(!empty($_SESSION['sesscode'])){

        $sesscode = $db->quote($_SESSION['sesscode']);
        $check_code = $db->query("SELECT * FROM system_sessions WHERE sesscode = $sesscode")->fetch();

        if($check_code['connected'] == 1){
           echo "<script> window.location.href = './controller.php'</script>";
        }

    }else{

        $code = generateRandomString(64);
        $now = new DateTime();
        $timestamp = $now->format('Y-m-d H:i:s');

        $sql = "INSERT INTO system_sessions 
        (uid,sessdate,connected,sessip,sesscode) 
        VALUES (?,?,?,?,?)";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            0,
            $timestamp,
            0,
            $ip,
            $code
        ));

        $_SESSION['sesscode'] = $code;
    }


?>
