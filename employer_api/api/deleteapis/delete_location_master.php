<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin");
include "../../includes/db-connection/db.php";

if (isset($_GET['dlt']) && !empty($_GET['dlt'])) {
    $deleteId = $_GET['dlt'];

    $delete = mysqli_query($con, "UPDATE tbl_employer_location_master SET status='0' WHERE location_id ='" . $deleteId . "'");
    if ($delete) {
        $stat = ['status' => true, 'message' => 'Deleted Successfully'];
    } else {
        $stat = ['status' => true, 'message' => 'Failed to Delete'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($stat);
?>