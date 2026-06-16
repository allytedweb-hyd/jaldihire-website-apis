<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type,authorization');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');
include "../../includes/db-connection/db.php";
include "../vendor/autoload.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$postdata = file_get_contents("php://input");
$jsondecode_data = json_decode($postdata);
$email =    $jsondecode_data->e_mail;
$password = $jsondecode_data->pass_w;
$headers = apache_request_headers();

$selUser = mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE e_mail='" . $email . "' and status=1");
$count = mysqli_num_rows($selUser);
if ($count == 1) {
    $fetchUser = mysqli_fetch_array($selUser);
    // echo json_encode($fetchUser['applicant_auto_create_id']);
    // exit();
    $userId = $fetchUser['applicant_auto_create_id'];
    $hash = $fetchUser['password'];
    if (!password_verify($password, $hash)) {
        $stat = ['status' => false, 'message' => 'Incorrect Password'];
        echo json_encode($stat);
        exit();
    }

    $key = "J@ldiHire@321";
    $issuedAt = new DateTimeImmutable();
    $expire = $issuedAt->modify('+1440 minutes')->getTimestamp();
    $payload = array(
        "user_id" => $userId,
        'iat'  => $issuedAt->getTimestamp(), // Issued at: time when the token was generated
        'nbf'  => $issuedAt->getTimestamp(), // Not before
        'exp'  => $expire, // Expire Time
    );
    $jwt = JWT::encode($payload, $key, 'HS256');
    $stat = ["status" => true, 'message' => "Login successfully", "token" => $jwt];
} else {
    $stat = ['failed' => 'User Not Found..!Plese try again with correct credentials'];
}
echo json_encode($stat);
