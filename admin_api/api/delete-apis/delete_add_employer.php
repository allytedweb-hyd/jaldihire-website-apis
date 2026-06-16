<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type,Authorization');
include "../../includes/db-connection/db.php";
include "../lib/jwt.php";
// Accesing the Headers
$headers = apache_request_headers();
// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);
TokenVerify($token);

@$deleteId = $_GET['dlt'];
@$status = $_GET['status'];

if (!empty($deleteId) && !empty($deleteId)) {
  $deleteEmployer = mysqli_query($con, "UPDATE tbl_add_employer SET status='" . $status . "' WHERE employer_id ='" . $deleteId . "'");

  if ($deleteEmployer) {
    $stat = ['status' => true, 'message' => 'Deleted Successfully'];
  } else {
    $stat = ['status' => true, 'message' => 'Failed to Delete'];
  }
} else {
  $stat = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($stat);