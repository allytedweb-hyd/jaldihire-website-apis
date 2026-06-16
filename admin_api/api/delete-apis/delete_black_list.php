<?php
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type,Authorization');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');
include "../../includes/db-connection/db.php";
include "../lib/jwt.php";

// Accesing the Headers
$headers = apache_request_headers();
// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);

TokenVerify($token);

$deleteId = $_GET['dlt'];

$delete_blacklist = mysqli_query($con, "UPDATE tbl_blacklist_master SET status='0' WHERE id ='" . $deleteId . "'");
if ($delete_blacklist == true) {
    $stat = ['status' => true, 'message' => 'Deleted Successfully'];
} else {
    $stat = ['status' => true, 'message' => 'Failed to Delete'];
}
echo json_encode($stat);
