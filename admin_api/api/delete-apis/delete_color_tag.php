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

if (isset($_GET['dlt']) && !empty($_GET['dlt'])) {

    $deleteId = $_GET['dlt'];

    $getcolor = mysqli_query($con, "UPDATE colour_tags SET status=0 WHERE id ='" . $deleteId . "'");
    if ($getcolor) {
        $stat = ['status' => true, 'message' => 'Admin Deleted Successfully'];
    } else {

        $stat = ['status' => false, 'message' => 'Failed to Delete'];
    }
} else {

    $stat = ['status' => false, 'message' => 'Invalid Perams'];
}
echo json_encode($stat);
