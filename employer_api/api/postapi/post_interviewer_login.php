<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

include "../../includes/db-connection/db.php";

include "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$postdata = file_get_contents("php://input");
$jsondecode_data = json_decode($postdata);

$email = $jsondecode_data->e_mail;
$password = $jsondecode_data->pass_w;
$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


TokenVerify($token);


$selUser = mysqli_query($con, "select * from interviewers_login where mail='" . $email . "' and status=1");
$count = mysqli_num_rows($selUser);
if ($count == 1) {
    $fetchUser = mysqli_fetch_array($selUser);
    // echo json_encode($fetchUser['applicant_auto_create_id']);
    // exit();
    $userId = $fetchUser['auto_create_id'];
    $hash = $fetchUser['password'];
    if (!password_verify($password, $hash)) {
        $stat = ['status' => true, 'message' => 'Incorrect Password'];
        echo json_encode($stat);
        exit();
    }
    $key = "J@ldiHire@321";
    $issuedAt = new DateTimeImmutable();
    $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();
    $payload = array(

        "user_id" => $userId,
        'iat' => $issuedAt->getTimestamp(),
        // Issued at: time when the token was generated
        'nbf' => $issuedAt->getTimestamp(),
        // Not before
        'exp' => $expire, // Expire Time
    );
    $jwt = JWT::encode($payload, $key, 'HS256');
    $stat = ['status' => true, 'message' => 'User Logged Successfully', 'JwtToken' => $jwt];
} else {
    $stat = ['status' => false, 'message' => 'User Not Found..!Plese try again with correct credentials'];
}
echo json_encode($stat);
