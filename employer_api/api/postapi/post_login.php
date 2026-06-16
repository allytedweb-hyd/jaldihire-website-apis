<?php

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

// ─────────────────────────────────────────────
// ERROR REPORTING
// ─────────────────────────────────────────────

error_reporting(E_ALL);
ini_set('display_errors', 0);

// ─────────────────────────────────────────────
// INCLUDE FILES
// ─────────────────────────────────────────────

include "../../includes/db-connection/db.php";
include "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// ─────────────────────────────────────────────
// GET INPUT DATA
// ─────────────────────────────────────────────

$postdata = file_get_contents("php://input");
$data = json_decode($postdata);

if (!$data || empty($data->e_mail) || empty($data->pass_w)) {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid request data'
    ]);
    exit();
}

// ─────────────────────────────────────────────
// VARIABLES
// ─────────────────────────────────────────────

$email    = trim($data->e_mail);
$password = trim($data->pass_w);

// ─────────────────────────────────────────────
// CHECK USER
// ─────────────────────────────────────────────

$stmt = mysqli_prepare(
    $con,
    "SELECT * FROM tbl_login WHERE login_mail = ? AND login_status = 1"
);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$count  = mysqli_num_rows($result);

// ─────────────────────────────────────────────
// LOGIN SUCCESS
// ─────────────────────────────────────────────

if ($count == 1) {

    $fetchUser = mysqli_fetch_assoc($result);
    $userId    = $fetchUser['login_id'];
    $hash      = $fetchUser['pass_word'];

    if (!password_verify($password, $hash)) {
        echo json_encode([
            'status' => false,
            'message' => 'Incorrect Password'
        ]);
        exit();
    }

    $key      = "J@ldiHire@321";
    $issuedAt = new DateTimeImmutable();
    $expire   = $issuedAt->modify('+1440 minutes')->getTimestamp();

    $payload = [
        "user_id" => $userId,
        "role"    => $fetchUser['user_role'],
        "iat"     => $issuedAt->getTimestamp(),
        "nbf"     => $issuedAt->getTimestamp(),
        "exp"     => $expire
    ];

    $jwt = JWT::encode($payload, $key, 'HS256');

    echo json_encode([
        'status'   => true,
        'message'  => 'User logged Successfully',
        'JwtToken' => $jwt
    ]);

} else {

    echo json_encode([
        'status'  => false,
        'message' => 'User not found. Please check your credentials.'
    ]);
}