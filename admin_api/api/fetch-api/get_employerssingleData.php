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
include "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$headers = apache_request_headers();

if (isset($headers['Authorization'])) {
    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $key = "J@ldiHire@321";
    try {
        $jwt = JWT::decode($token, new Key($key, 'HS256'));
    } catch (\Exception $e) {
        echo json_encode(["status" => false, "error" => "Token has Expired Please Login Again"]);
        exit();
    }
    $data = [];
    $userData = [];
    $uId = $jwt->user_id;
    $role = $jwt->role;

    if ($role == 'team') {

        $query = "SELECT *, NULL as password FROM tbl_add_team T1, tbl_add_employer T2 WHERE T1.auto_create_id='" . $uId . "' AND T2.employer_auto_create_id = T1.employer_id AND T1.status=1 AND T2.status=1";

        $getData = mysqli_query($con, $query);
    } else if ($role == 'RM') {

        $query = "SELECT *, NULL as password FROM tbl_resource_managers T1, tbl_add_employer T2 WHERE T1.RM_auto_create_id='" . $uId . "' AND T2.employer_auto_create_id = T1.employer_auto_create_id AND T1.status=1 AND T2.status=1";

        $getData = mysqli_query($con, $query);
    } else if ($role == 'RM') {

        $query = "SELECT *, NULL as password FROM tbl_resource_managers T1, tbl_add_employer T2 WHERE T1.RM_auto_create_id='" . $uId . "' AND T2.employer_auto_create_id = T1.employer_auto_create_id AND T1.status=1 AND T2.status=1";

        $getData = mysqli_query($con, $query);
    } else {
        $getData = mysqli_query($con, "SELECT *, null as password FROM tbl_add_employer  WHERE employer_auto_create_id='" . $uId . "' AND status=1");
    }

    while ($uData = mysqli_fetch_array($getData)) {
        $userData = [$uData];
    }
    if ($userData != null) {

        $planDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_plan_features  WHERE plan_id='" . $userData[0]['plan_id'] . "'"));

        if ($planDetails != null) {
            $userData[0] += $planDetails;
        }
    }

    echo json_encode(['status' => true, 'data' => $userData]);
} else {
    $data = ['status' => false, 'error' => 'Token Not Found'];
    echo json_encode($data);
}
