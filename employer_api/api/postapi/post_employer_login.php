<?php
// ─────────────────────────────────────────────
// ERROR REPORTING
// ─────────────────────────────────────────────
error_reporting(E_ALL);
ini_set('display_errors', 0);

// ─────────────────────────────────────────────
// CORS HEADERS
// ─────────────────────────────────────────────
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

$allowed_origins = [
    'https://jaldihire.com',
    'https://www.jaldihire.com',
    'https://admin.jaldihire.com',
    'https://employer.jaldihire.com',
    'https://applicant.jaldihire.com',
    'http://localhost:4200',
    'https://localhost:4200'
];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    header("Access-Control-Allow-Origin: https://www.jaldihire.com");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// HANDLE PREFLIGHT REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

session_start();
include "../../includes/db-connection/db.php";
include "../vendor/autoload.php";

date_default_timezone_set('asia/kolkata');
$dateTime = date("Y-m-d H:i:s");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$postdata = file_get_contents("php://input");
$jsondecode_data = json_decode($postdata);

if (!isset($jsondecode_data) && empty($jsondecode_data)) {
    exit(json_encode(['status' => false, 'message' => 'Please Enter Login Credentials']));
}

$email = $jsondecode_data->e_mail;
$password = $jsondecode_data->pass_w;

$query = "SELECT * FROM tbl_add_employer WHERE emp_email = '" . $email . "' AND status=1";
$stmt = $con->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $hash = $user['password'];
    if (!password_verify($password, $hash)) {
        $stat = ['status' => true, 'message' => 'Incorrect Password'];
        echo json_encode($stat);
        exit();
    }
    // if ($user['is_logged_in']) {
    // * User is already logged in
    // exit(json_encode(['status' => false, 'message' => 'User is already logged in']));
    // } 
    else {
        //? storing user id in session
        $_SESSION['user_id'] = $user['employer_auto_create_id'];

        // * User is not logged in, proceed with login
        $userId = $user['employer_auto_create_id'];
        $key = "J@ldiHire@321";
        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();
        $payload = array(
            "user_id" => $userId,
            "role" => "admin",
            'iat' => $issuedAt->getTimestamp(), // ? time when the token was generated
            'nbf' => $issuedAt->getTimestamp(), // ? Not before
            'exp' => $expire, // ? Expire Time
        );
        $jwt = JWT::encode($payload, $key, 'HS256');

        // ? Update the user's status to logged in
        $updateQuery = "UPDATE tbl_add_employer SET is_logged_in = 1,login_time='" . $dateTime . "' WHERE employer_auto_create_id = '" . $userId . "'";

        $updateStmt = $con->prepare($updateQuery);
        $updateStmt->execute();

        $stat = ['status' => true, 'message' => 'User Logged Successfully', 'JwtToken' => $jwt];

        echo json_encode($stat);
        exit();
    }
} else {

    $query3 = "SELECT * FROM tbl_resource_managers WHERE rm_email='" . $email . "' AND status=1";
    $stmt = $con->prepare($query3);
    $stmt->execute();
    $result3 = $stmt->get_result();
    $user3 = $result3->fetch_assoc();

    if ($user3) {
        $hash = $user3['rm_password'];
        if (!password_verify($password, $hash)) {
            exit(json_encode(['status' => false, 'Incorrect Password']));
        }

        // if ($user3['is_logged_in']) {
        // * user is already logged in
        //     exit(json_encode(['status' => false, 'message' => 'User is already logged in']));
        // } 
        else {

            //? storing user id in session
            $_SESSION['user_id'] = $user3['RM_auto_create_id'];

            $userId3 = $user3['RM_auto_create_id'];
            $key = "J@ldiHire@321";
            $issuedAt = new DateTimeImmutable();
            $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();
            $payload = array(
                "user_id" => $userId3,
                "role" => "RM",
                'iat' => $issuedAt->getTimestamp(), // ? time when the token was generated
                'nbf' => $issuedAt->getTimestamp(), // ? Not before
                'exp' => $expire, // ? Expire Time
            );

            $jwt = JWT::encode($payload, $key, 'HS256');

            // ? Update the user's status to logged in
            $updateQuery = "UPDATE tbl_resource_managers SET is_logged_in = 1,login_time='" . $dateTime . "' WHERE RM_auto_create_id = '" . $userId3 . "'";
            $updateStmt = $con->prepare($updateQuery);
            $updateStmt->execute();

            $stat = ['status' => true, 'JwtToken' => $jwt];
        }
    } else {

        $query2 = "SELECT * FROM tbl_add_team WHERE email = '" . $email . "' AND status=1";
        $stmt = $con->prepare($query2);
        $stmt->execute();
        $result2 = $stmt->get_result();
        $user2 = $result2->fetch_assoc();

        if ($user2) {

            $hash = $user2['pasword'];
            if (!password_verify($password, $hash)) {
                $stat = ['status' => true, 'message' => 'Incorrect Password'];
                echo json_encode($stat);
                exit();
            }

            // if ($user2['is_logged_in']) {
            // * User is already logged in
            //     exit(json_encode(['status' => false, 'message' => 'User is already logged in']));
            // }
            else {

                //? storing user id in session
                $_SESSION['user_id'] = $user2['auto_create_id'];

                $userId2 = $user2['auto_create_id'];
                $key = "J@ldiHire@321";
                $issuedAt = new DateTimeImmutable();
                $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();
                $payload = array(
                    "user_id" => $userId2,
                    "role" => "team",
                    'iat' => $issuedAt->getTimestamp(), // ? time when the token was generated
                    'nbf' => $issuedAt->getTimestamp(), // ? Not before
                    'exp' => $expire, // ? Expire Time
                );

                $jwt = JWT::encode($payload, $key, 'HS256');

                // ? Update the user's status to logged in
                $updateQuery = "UPDATE tbl_add_team SET is_logged_in = 1,login_time='" . $dateTime . "' WHERE auto_create_id = '" . $userId2 . "'";
                $updateStmt = $con->prepare($updateQuery);
                $updateStmt->execute();

                $stat = ['status' => true, 'JwtToken' => $jwt];
            }
        } else {
            $stat = ['status' => false, 'message' => 'User not found..! please try again with correct credentials'];
        }
    }
}

echo json_encode($stat);
exit();
