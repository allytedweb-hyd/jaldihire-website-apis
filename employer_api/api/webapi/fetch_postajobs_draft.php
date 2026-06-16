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

$idClause = '';

if (isset($_GET['agent_id']) && !empty($_GET['agent_id'])) {
        $agent_id = $_GET['agent_id'];
        $idClause = "created_by_id='" . $agent_id . "' && created_by_role='Agent' &&";
} elseif (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {
        $employerId = $_GET['employer_id'];
        $idClause = "created_by_id='" . $employerId . "' && created_by_role='RM' &&";
} elseif (isset($_GET['teamId']) && !empty($_GET['teamId'])) {
        $teamId = $_GET['teamId'];
        $idClause = "created_by_id='" . $teamId . "' && created_by_role='Team' &&";
} else {
        $status = ['status' => false, 'message' => 'Invalid Params'];
        exit(json_encode($status));
}

$get_postajob_data = mysqli_query($con, "SELECT * FROM tbl_post_a_job_draft WHERE $idClause status=0 ORDER BY id DESC");
$data = [];
while ($fetch_postajob_data = mysqli_fetch_array($get_postajob_data)) {
        $data[] = $fetch_postajob_data;
}
if ($data != null) {
        $status = ['status' => true, 'message' => 'fetched successfully', 'data' => $data];
} else {
        $status = ['status' => false, 'message' => 'No Data Found'];
}
echo json_encode($status);
