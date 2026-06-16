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
$data = [];
$getprice = mysqli_query($con, 'SELECT * FROM feature_prices WHERE status=1');

while ($fet = mysqli_fetch_assoc($getprice)) {
    $data[] = $fet;
}
$stat = ['status' => true, 'data' => $data,];
echo json_encode($stat);
exit();
