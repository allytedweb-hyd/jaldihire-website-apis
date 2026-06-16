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
include "../../includes/db-connection/db.php";

$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {
    $data = json_decode($json);
    $employer_id = $data->employer_id;
    $projectName = $data->project_name;
    $validToDate = $data->validToDate;
    $notes = $data->notes;
    $interviewers = $data->interviewers;
    $interviewersImplode = '';

    if (count($interviewers) > 0) {
        for ($i = 0; $i < count($interviewers); $i++) {
            $item = $interviewers[$i];
            if (intval(count($interviewers) - 1) == $i) {
                $interviewersImplode .= $item->item_text;
            } else {
                $interviewersImplode .= $item->item_text . ',';
            }
        }
    }

    $insert = mysqli_query($con, "INSERT INTO tbl_employer_project_name SET employer_id='{$employer_id}',project_name='{$projectName}',valid_to_date='{$validToDate}',notes='{$notes}',interviewers='{$interviewersImplode}',status=1");

    if ($insert) {
        $status = ['status' => true, 'message' => 'Posted Successfully'];
    } else {
        $status = ['status' => false, 'message' => 'Failed! Please Try Again'];
    }
} else {
    $status = ['status' => false, 'message' => 'Something went wrong! Please Try Again'];
}
echo json_encode($status);
