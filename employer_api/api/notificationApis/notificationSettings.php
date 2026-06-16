<?php
header("Content-Type: application/json; charset=UTF-8");
include "../../includes/db-connection/db.php";
include '../libs/jwt.php';

// Accesing the Headers
$headers = apache_request_headers();
// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);
@$response = TokenVerify($token);

@$empId = @$response['userId'];

$data = json_decode(file_get_contents('php://input'));

if (isset($data) && !empty($data)) {
    $alertType = $data->alertType;
    $alertName = $data->alertName;
    $value = $data->value;

    $q = "UPDATE allowed_notifications SET " . $alertType . "='" . $value . "' WHERE user_id='" . $empId . "'";

    $updateNotify = $con->prepare($q);
    $updateNotify->execute();

    $message = $value == 1 ? 'Enabled' : 'Disabled';

    $stat = ['status' => true, 'message' => $alertName . ' ' . 'has' . ' ' . $message];

    echo json_encode($stat);
    exit();
}
