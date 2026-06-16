<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin");
include "../../includes/db-connection/db.php";

if (isset($_GET['dlt']) && !empty($_GET['dlt'])) {
    $deleteId = $_GET['dlt'];
    $get_empdesig_data = mysqli_query($con, "UPDATE tbl_employer_substatus SET status='0' WHERE id ='" . $deleteId . "'");

    if ($get_empdesig_data == true) {
        $status = ['status' => true, 'message' => 'Deleted Successfully', 'data' => $get_empdesig_data];
    } else {
        $status = ['status' => true, 'message' => 'Failed! to Delete'];
    }
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}

echo json_encode($status);
?>