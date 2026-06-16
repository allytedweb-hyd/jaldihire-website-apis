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
// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);

TokenVerify($token);


$data = json_decode(file_get_contents('php://input'));

if (isset($data) && !empty($data)) {
    $planId = $data->plan_id;
    $featureName = $data->featureName;
    $status = $data->status;
    $label = $data->label;

    $q = "UPDATE tbl_plan_features SET " . $featureName . "='" . $status . "' WHERE plan_id='" . $planId . "'";

    $updateNotify = $con->prepare($q);
    $updateNotify->execute();

    $message = $status === 1 ? 'Enabled' :( $status === 0 ? 'Disabled' : 'Updated');

    $stat = ['status' => true, 'message' =>  $label . ' ' . 'has' . ' ' . $message];

    echo json_encode($stat);
    exit();
}
