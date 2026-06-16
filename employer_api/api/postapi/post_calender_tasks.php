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

include "../../includes/db-connection/db.php";

$json = file_get_contents('php://input');

if (isset($json) && !empty($json)) {
    $data = json_decode($json);

    $employerId = $data->employer_id;
    $task_date = $data->date;
    $task = $data->task;
    $createdBy = $data->created_by;
    $createdRole = $data->created_role;

    if (isset($data->id) && !empty($data->id)) {
        $id = $data->id;
        $updateTasks = mysqli_query($con, "UPDATE employer_calender_tasks SET task='" . $task . "',created_by='" . $createdBy . "',created_role='" . $createdRole . "' WHERE id='" . $id . "' && employer_id='" . $employerId . "' && task_date='" . $task_date . "' && status=1");

        if ($updateTasks) {
            $status = ['status' => true, 'message' => 'Updated Successfully'];
        } else {
            $status = ['status' => false, 'message' => 'Failed! Please Try Again'];
        }
        echo json_encode($status);
        exit();
    }

    $insertTask = mysqli_query($con, "INSERT INTO employer_calender_tasks SET employer_id='" . $employerId . "',task_date='" . $task_date . "',task='" . $task . "',created_by='" . $createdBy . "',created_role='" . $createdRole . "',status=1");

    if ($insertTask) {
        $status = ['status' => true, 'message' => 'Submitted Successfully'];
    } else {
        $status = ['status' => false, 'message' => 'Failed! Please Try Again'];
    }
} else {
    $status = ['status' => false, 'message' => 'Something Went Wrong! Please Try Again'];
}
echo json_encode($status);
