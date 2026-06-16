<?php
header("Content-Type: application/json; charset=UTF-8");
include "../../includes/db-connection/db.php";
include '../libs/jwt.php';

// Accesing the Headers
$headers = apache_request_headers();
// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);
@$response = TokenVerify($token);
@$clause = '';
@$empId = @$response['userId'];
$data = [];

if (isset($empId) && !empty($empId)) {

    if (!empty($_GET['isRead']) || $_GET['isRead'] == 0) {
        $clause = "IsRead='{$_GET['isRead']}' AND";
    }

    $q = "SELECT * FROM tbl_notification WHERE $clause employer_id='" . $empId . "' AND notify_to LIKE '%Employer%' AND status=1";

    $execute = mysqli_query($con, $q);

    while ($fet = mysqli_fetch_assoc($execute)) {
        $data[] = $fet;
    }

    $getAllQ = "SELECT * FROM tbl_notification WHERE $clause notify_to='All'";

    $getAllnotification = mysqli_query($con, $getAllQ);
    while ($fet = mysqli_fetch_assoc($getAllnotification)) {
        $data[] = $fet;
    }
    if ($data != null) {
        $stat = ['status' => true, 'data' => $data];
    } else {
        $stat = ['status' => false, 'message' => 'No Data Found'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Invalid User'];
}

echo json_encode($stat);
exit();
