<?php
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type,Authorization');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../includes/db-connection/db.php";
include '../lib/jwt.php';
$headers = apache_request_headers();
date_default_timezone_set("Asia/Calcutta");

// Checking Token Present Or Not
if (!isset($headers['Authorization'])) {
    $status = ['status' => false, 'message' => 'Unauthorized Access'];
    echo json_encode($status);
    exit();
}

@$token = str_replace('Bearer ', '', $headers['Authorization']);

if ($token) {
    // Validating JWT Token
    $reponse = verifyJWT($token);
    if ($reponse['status']) {

        if (isset($_GET['dlt']) && !empty($_GET['dlt'])) {

            $deleteId = $_GET['dlt'];
            $deleteFunction = mysqli_query($con, "UPDATE tbl_function_master SET status='0' WHERE id ='" . $deleteId . "'");
            if ($deleteFunction) {
                $stat = ['status' => true, 'message' => 'Deleted Successfully'];
            } else {
                $stat = ['status' => false, 'message' => 'Failed to Delete'];
            }
        } else {
            $stat = ['status' => false, 'message' => 'Invalid Params'];
        }
    } else {
        $stat = ['status' => false, 'message' => 'User Deactivated or Invalid'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Unauthorized Access'];
}

echo json_encode($stat);

?>