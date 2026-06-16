<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [
    'https://jaldihire.com',
    'https://admin.jaldihire.com',
    'https://employer.jaldihire.com',
    'https://applicant.jaldihire.com'
];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
error_reporting(E_ALL);
ini_set('display_errors', 0);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [
    'https://jaldihire.com',
    'https://admin.jaldihire.com',
    'https://employer.jaldihire.com',
    'https://applicant.jaldihire.com'
];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../../includes/db-connection/db.php';

// $datetime = date("Y-m-d H:i:s");
// $epochTimer = intval(strtotime($datetime) . '000');

date_default_timezone_set('asia/kolkata');
$minutes_to_add = 300;
$datetime = date("Y-m-d H:i:s");

// $time = new DateTime($datetime);
// $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
// $stamp = $time->format('Y-m-d H:i:s');
// $epochTimer = intval(strtotime($datetime) . '000');


if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {

    $emp_id = $_GET['employer_id'];
    $data = [];
    // SELECT * FROM applicant_scheduled WHERE updated_on <= DATE_SUB(NOW(), INTERVAL 6 HOUR); 
    // $time = mysqli_fetch_assoc(mysqli_query($con, "SELECT DATE_SUB('" . $datetime . "', INTERVAL 6 HOUR) as date"));
    // exit(json_encode($time));

    // DATE_SUB is used to substract time from current time and to compare updated time 

    $query = "SELECT * FROM applicant_scheduled T1, tbl_applicant_add T2, tbl_post_a_job T3 WHERE T1.employer_id='" . $emp_id . "'&& T1.updated_on <= DATE_SUB(DATE_SUB('" . $datetime . "', INTERVAL 1 HOUR), INTERVAL 30 MINUTE) && T1.status=2 && T2.applicant_id = T1.applicant_id && T2.status=1 && T3.auto_create_jobid = T1.job_id";

    $getPendingRequests = mysqli_query($con, $query);

    $data = [];
    while ($fetchRequests = mysqli_fetch_assoc($getPendingRequests)) {
        $data[] = $fetchRequests;
    }

    if ($data != null) {
        $status = ['status' => true, 'data' => $data];
    } else {
        $status = ['status' => false, 'message' => 'No Data Found'];
    }
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($status);

// function to add time value to the epoch 

// function convertEpochToDate($epoch)
// {
//     echo $epoch;
//     $date = date("Y-m-d H:i:s", substr(intval($epoch), 0, 10));
//     $minutes_to_add = 300;
//     $time = new DateTime($date);
//     $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

//     $stamp = $time->format('Y-m-d H:i:s');
//     $epochTime = intval(strtotime($stamp) . '000');
//     return $epochTime;
// }


// fetch pending actions query without date add function

// if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {
//     $emp_id = $_GET['employer_id'];

//     $getPendingRequests = mysqli_query($con, "SELECT * FROM applicant_scheduled T1, tbl_applicant_add T2, tbl_post_a_job T3 WHERE T1.employer_id='" . $emp_id . "'&& T1.epoch_date <= '" . $epochTimer . "' && T1.status=2 && T2.applicant_id = T1.applicant_id && T2.status=1 && T3.auto_create_jobid = T1.job_id");

//     $data = [];
//     while ($fetchRequests = mysqli_fetch_assoc($getPendingRequests)) {
//         $data[] = $fetchRequests;
//     }

//     if ($data != null) {
//         $status = ['status' => true, 'data' => $data];
//     } else {
//         $status = ['status' => false, 'message' => 'No Data Found'];
//     }

// } else {
//     $status = ['status' => false, 'message' => 'Invalid Params'];
// }
// echo json_encode($status);
