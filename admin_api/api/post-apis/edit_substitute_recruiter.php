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
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}



include "../../includes/db-connection/db.php";
include "../vendor/autoload.php";

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;



$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {

    $data = json_decode($json);

    $present_recruiter = $data->present_recruiter_id;
    $subRecruiterid = $data->subRecruiterid;
    $fromDate = $data->fromDate;
    $toDate = $data->toDate;
    $text = $data->remarks;
    $assignedrole = $data->assignedrole;
    $assigned_id = $data->assigned_id;
    $from_epoch = $data->fromDate_epoch;
    $to_epoch = $data->toDate_epoch;
    $job_id = $data->job_id;

    $update_substitute = mysqli_query($con, "UPDATE job_recruiter_substitute SET substitute_recruiter='" . $subRecruiterid . "',from_date='" . $fromDate . "',to_date='" . $toDate . "',from_epoch='" . $from_epoch . "',to_epoch='" . $to_epoch . "',text='" . $text . "',assigned_byrole='" . $assignedrole . "',assigned_by_id='" . $assigned_id . "',status=1 WHERE job_id='" . $job_id . "' && present_recruiter='" . $present_recruiter . "'");

    if ($update_substitute == true) {
        $stat = ['message' => 'Substitute Updated!', 'status' => true];
    } else {
        $stat = ['message' => 'Failed to Update!', 'status' => false];
    }
} else {
    $stat = ['status' => true, 'message' => 'Something went wrong! Please Try Again'];
}

echo json_encode($stat);
