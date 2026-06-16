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

include '../../includes/db-connection/db.php';
include "../vendor/autoload.php";
require '../vendor/phpmailer/PHPMailerAutoload.php';

set_time_limit(10000);

$query = 'SELECT login_id,validity,notification_sent FROM tbl_login WHERE login_status=1 LIMIT 20';
$queryVal = mysqli_query($con, $query);

while ($each = mysqli_fetch_assoc($queryVal)) {
    $AdminId = $each['login_id'];
    $validity = $each['validity'];
    $notificationSent = $each['notification_sent'];
    $validityDate = new DateTime($validity);
    $currentDate = new DateTime();

    $validityDateMinusOne = clone $validityDate;
    $validityDateMinusOne->modify('-1 day');

    if ($currentDate->format('Y-m-d') === $validityDateMinusOne->format('Y-m-d') && $notificationSent == 0) {
        $insertnoti = mysqli_fetch_assoc(mysqli_query($con, 'SELECT * FROM tbl_login WHERE login_id="' . $AdminId . '" AND login_status=1'));

        if ($insertnoti) {
            $email = $insertnoti['login_mail'];
            $text = "Your validity will expire on <$validity>!";
            $InsertadminNotification = mysqli_query($con, "INSERT INTO tbl_notification SET notify_to='Admin',notification_text='" . $text . "',activity='Masters',notification_type='info',admin_id='" . $AdminId . "'");

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jaldihireteam@mmworkspace.com';
            $mail->Password = 'Jaldihire@321$#';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('jaldihireteam@mmworkspace.com', 'JALDHI HIRE');
            $mail->addAddress($email);
            $mail->addReplyTo('jaldihireteam@mmworkspace.com', 'JALDHI HIRE');

            $mail->isHTML(true);
            $mail->Subject = 'Validity Expiry Notice';
            $mail->Body    = "Dear Agent, <br><br> Please note that your validity will expire on <b>" . $validity . "</b>.";

            if ($mail->send()) {
                mysqli_query($con, "UPDATE tbl_login SET notification_sent = 1 WHERE login_id='" . $AdminId . "'");
            }
        }
    } elseif ($currentDate->format('Y-m-d') >= $validityDate->format('Y-m-d') && $notificationSent == 1) {
        $updateQuery = "UPDATE tbl_login SET login_status = 0 WHERE login_id='" . $AdminId . "'";
        if (mysqli_query($con, $updateQuery)) {
            echo "Status updated to 0 for login_id: $AdminId";
        }
    }
}