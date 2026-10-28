<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [
    'https://jaldihire.com',
    'https://admin.jaldihire.com',
    'https://employer.jaldihire.com',
    'https://applicant.jaldihire.com'
];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

include "../../includes/db-connection/db.php";
require '../vendor/phpmailer/PHPMailerAutoload.php';



$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {

    $data = json_decode($json);

    $mail = $data->mail;
    $date = date_default_timezone_set('Asia/kolkata');
    $dateFormat = date('Y-m-d H:i:s');

    $query = mysqli_query($con, "INSERT INTO user_gamil SET gmail='" . $mail . "',created_id='" . $dateFormat . "',updated_id='0',status=1");
    if ($query == true) {

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtpout.secureserver.net';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@allyted.com';
        $mail->Password = 'Allyted@2026';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('info@allyted.com', 'JALDHI HIRE');
        $mail->addAddress('info@example.com');
        // $mail->addAddress('anilcm016@gmail.com');
        $mail->addReplyTo('info@allyted.com', 'JALDHI HIRE');

        $mail->isHTML(true);
        $mail->Subject = 'Validity Expiry Notice';
        $mail->Body    = "thank you";

        if ($mail->send()) {
            $stat = ['status' => true, 'message' => 'Mail sent Successfully'];
        }
    } else {
        $stat('Error', 'Blog Added Failed', 'error');
        // echo "<script>alert('Failed')</script>";

    }
}
echo json_encode($stat);
