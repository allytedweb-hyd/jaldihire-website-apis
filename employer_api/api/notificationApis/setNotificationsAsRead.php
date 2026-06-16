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

    if (isset($_GET['del_id']) && !empty($_GET['del_id'])) {
        $q = "UPDATE tbl_notification SET status=0 WHERE notification_id='" . $_GET['del_id'] . "'";
        mysqli_query($con, $q);
        exit(json_encode(['status' => true, 'message' => 'Removed Successfully']));
    }

    $q = "UPDATE tbl_notification SET IsRead=1 WHERE employer_id='" . $empId . "' AND notify_to LIKE '%Employer%' AND status=1";

    $update = mysqli_query($con, $q);

    if ($update != null) {
        $stat = ['status' => true, 'message' => 'Updated Successfully'];
    } else {
        $stat = ['status' => false, 'message' => 'No Data Found'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Invalid User'];
}
echo json_encode($stat);
exit();
