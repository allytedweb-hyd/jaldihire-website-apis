<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type,Authorization');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');
include "../vendor/autoload.php";
include "../../includes/db-connection/db.php";

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
    $userExp = [];
    $userSkill = [];
    $uId = $jwt->user_id;

    $getData = mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE applicant_auto_create_id='" . $uId . "' and status=1");

    $getExp = mysqli_query($con, "SELECT * FROM applicant_experiences WHERE applicant_auto_create_id='" . $uId . "' and status=1");

    $getSkill = mysqli_query($con, "SELECT DISTINCT pri_skill FROM applicant_skill  WHERE applicant_auto_create_id='" . $uId . "' and status=1");

    while ($uData = mysqli_fetch_array($getData)) {
        $userData = [$uData];
    }
    while ($uexp = mysqli_fetch_array($getExp)) {
        $userExp[] = $uexp;
    }
    while ($uskill = mysqli_fetch_array($getSkill)) {
        $userSkill[] = $uskill;
    }
    $data = ['status' => true, 'data' => $userData, "experience" => $userExp, "skill" => $userSkill];
} else {
    $data = ['status' => false, 'error' => 'Token Not Found'];
}
echo json_encode($data);
