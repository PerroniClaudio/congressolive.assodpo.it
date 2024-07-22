<?php 
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
/**
 *  Connessione al db
 * 
 */

 $DB_HOST = "35.240.126.126";
 $DB_USER = "admin_labormed";
 $DB_PASS = "eDfqc0IhxLIKdrb99";
 $DB_NAME = "labormedicaladmin";

$timeZone = 'Europe/Rome';
date_default_timezone_set($timeZone);

try {
    $db = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS, array(PDO::ATTR_PERSISTENT => true));
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
$db->exec("SET time_zone='Europe/Rome';");

/**
 * Parametri per la diretta
 * 
 */

$live_source = "https://livelm.dgvery.com/";
$live_server = "dgvery-livestream-lm";
$live_server_link = "rtmp://livelm.dgvery.com";
$live_code = "stream01";

//Corso Congresso ASSODPO
$event_code = "CRS-1367";


/**
 * getRealIpAddr()
 * Ottiene l'indirizzo ip di chi effetua la chiamata
 */

function getRealIpAddr(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];   //check ip from share internet
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];   //to check ip is pass from proxy
    else $ip = $_SERVER['REMOTE_ADDR'];
    return $ip;
}
  
$ip = getRealIpAddr();

/**
 * generateRandomString()
 * Genera una stringa lunga tot caratteri
 * @length : Int - Stablisce quanto lunga deve essere.
 * 
 */


function generateRandomString($length = 10){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * sendMail()
 * Utilizza mailjet per inviare una mail, serve in registrazione e per il recupero password.
 * @emailTo: String - La mail del destinatario
 * @nameTo: String - Nome e cognome del destinatario
 * @oggetto: String - Oggetto della mail
 * @mail_corpo: String - Corpo della mail in html
 */


function sendMail($emailTo, $nameTo, $oggetto, $mail_corpo) {

    $emailFrom = "info@assodpo.it";
    $nameFrom = "Assodpo";

    $Mail_data = array(
        'Messages' => array(
            0 => array(
                'From' => array( 'Email' => $emailFrom, 'Name' => $nameFrom),
                'To' => array(0 => array( 'Email' =>  $emailTo, 'Name' => $nameTo)),
                'Subject' => $oggetto,
                'TextPart' => $mail_corpo,
                'HTMLPart' => $mail_corpo,
            )
        )
    );

    $data_string = json_encode($Mail_data);

    $ch = curl_init('https://api.mailjet.com/v3.1/send');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "a557eaf719c26c3896c2ce7dc3985409:e9be99582337d724acb8a138408bbdce");
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        )
    );

    $result = curl_exec($ch);
}