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

if (isset($_GET['job_id']) && !empty($_GET['job_id'])) {
    $jobId = $_GET['job_id'];
    $data = [];
    $fetJobDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT primary_skill,job_location,experience FROM tbl_post_a_job WHERE id='" . $jobId . "'"));
    $jobSkill = $fetJobDetails['primary_skill'];
    $jobLoc = $fetJobDetails['job_location'];
    $jobExp = $fetJobDetails['experience'];

    $getRelatedJobs = mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE id !='" . $jobId . "' AND primary_skill LIKE '%$jobSkill%' AND job_location LIKE '%$jobLoc%' AND experience LIKE '%$jobExp%' AND status=1 LIMIT 3");

    while ($fet = $getRelatedJobs->fetch_assoc()) {
        $data[] = $fet;
    }
    if ($data == null) {
        $status = ['status' => false, 'message' => 'No Jobs Found'];
    } else {
        $status = ['status' => true, 'data' => $data];
    }
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($status);
