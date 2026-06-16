<?php
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type,Authorization');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../includes/db-connection/db.php";
include "../lib/jwt.php";

// Accesing the Headers

$headers = apache_request_headers();


@$token = str_replace('Bearer ', '', $headers['Authorization']);
@$response = TokenVerify($token);
@$loginId = $response['userId'];
@$role = $response['role'];
$Roleclause = '';
$data = [];

if ($role == 'Agent') {
    $Roleclause = 'agent_id="' . $loginId . '" AND';
}
if ($role == 'Recruiter') {
    $Roleclause = 'recruiter_id="' . $loginId . '" AND';
}
if ($role == 'Admin') {
    $Roleclause = 'admin_id="' . $loginId . '" AND';
}

if (!empty($loginId)) {
    $agentId = mysqli_query($con, "SELECT * FROM tbl_notification WHERE $Roleclause notify_to='" . $role . "' && status=1");

    while ($fetch = mysqli_fetch_assoc($agentId)) {
        $data[] = $fetch;
    }
    if ($role != 'Admin') {
        $getAllnotification = mysqli_query($con, 'SELECT * FROM tbl_notification WHERE notify_to="All"');
        while ($fet = mysqli_fetch_assoc($getAllnotification)) {
            $data[] = $fet;
        }
    }
    if ($data != null) {
        $stat = ['status' => true, 'data' => $data];
    } else {
        $stat = ['status' => true, 'message' => 'data is not found'];
    }
}
echo json_encode($stat);
exit();
