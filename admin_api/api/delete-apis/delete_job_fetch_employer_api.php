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


$deleteId = $_GET['dlt'];

// $get_job_data = mysqli_query($con,"UPDATE tbl_post_a_job SET status='0' WHERE id='" . $deleteId . "'");
// if($get_job_data == true)
// {
//   $stat = ['successstatus' => 'Success!'];
// }
// else{

//   $stat = ['failurestatus' => 'Failed!'];

// }
// echo json_encode($stat);

$get_job_data = mysqli_query($con, "UPDATE tbl_post_a_job SET status='0' WHERE id ='" . $deleteId . "'");
if ($get_job_data == true) {
  $stat = ['status' => true, 'message' => 'Job Deleted Successfully', 'data' => $get_job_data];
} else {

  $stat = ['status' => true, 'message' => 'Failed to Delete'];

}
echo json_encode($stat);

?>