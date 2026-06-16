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
$data = [];

$limitClause = '';

if (isset($_GET['limit']) && !empty($_GET['limit'])) {
    $limit = 0;
    $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
    $skip = 0;
    $skip = intval($_GET['skip']);
}
@$offset = $limit * $skip;

if (isset($_GET['limit']) && isset($_GET['skip'])) {
    $limitClause = "LIMIT $limit OFFSET $offset";
}

if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {
    $empId = $_GET['employer_id'];
    $getHistory = mysqli_query($con, "SELECT * FROM employer_purchase_history WHERE employer_id='" . $empId . "' AND status=1 ORDER BY purchase_date DESC $limitClause");
    $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM employer_purchase_history WHERE employer_id='" . $empId . "' AND status=1 ORDER BY purchase_date DESC"));
    while ($f = mysqli_fetch_assoc($getHistory)) {
        $data[] = $f;
    }
    if ($data == null) {
        $stat = ['status' => false, 'message' => 'No Data Found'];
    } else {
        $stat = ['status' => true, 'data' => $data, 'count' => $count];
    }
} else {
    $stat = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($stat);
