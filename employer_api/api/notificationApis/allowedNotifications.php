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

if (isset($empId) && !empty($empId)) {

    $getAllowedNotifications = mysqli_fetch_assoc(mysqli_query($con, "SELECT employer_alerts,advisor_alerts,recruiter_alerts,admin_alerts,interview_alerts,job_alerts FROM allowed_notifications WHERE user_id='" . $empId . "'"));

    $stat = ['status' => true, 'data' => $getAllowedNotifications];
} else {
    $stat = ['status' => false, 'message' => 'Invalid User'];
}
echo json_encode($stat);
exit();
