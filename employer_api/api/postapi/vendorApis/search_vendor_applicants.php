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

include "../../../includes/db-connection/db.php";
// include "../lib/jwt.php";
$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);



// Get search and pagination parameters
@$experienceFrom = $_GET['experienceFrom'];
@$experienceTo = $_GET['experienceTo'];
@$skill = $_GET['skill'];
@$avaliability = $_GET['avaliability'];
@$location = $_GET['location'];
@$work_mode = $_GET['work_mode'];
@$client_id = $_GET['client_id'];

$limitClause = '';
$whereClause = ' WHERE status=1'; // Initialize with a base condition

// Check if client_id is provided and add it to the WHERE clause
if (!empty($client_id)) {
    // Add the client_id filter with the correct AND operator
    $whereClause .= " AND client_id = '" . mysqli_real_escape_string($con, $client_id) . "'";
}

// Add other search filters
if (!empty($experienceFrom)) {
    $whereClause .= " AND total_expereience >= '" . mysqli_real_escape_string($con, $experienceFrom) . "'";
}
if (!empty($experienceTo)) {
    $whereClause .= " AND total_expereience <= '" . mysqli_real_escape_string($con, $experienceTo) . "'";
}
if (!empty($skill)) {
    $whereClause .= " AND main_skill LIKE '%" . mysqli_real_escape_string($con, $skill) . "%'";
}
if (!empty($work_mode)) {
    $whereClause .= " AND work_mode LIKE '%" . mysqli_real_escape_string($con, $work_mode) . "%'";
}
if (!empty($location)) {
    $whereClause .= " AND preferred_location LIKE '%" . mysqli_real_escape_string($con, $location) . "%'";
}
if (!empty($avaliability)) {
    $whereClause .= " AND notice_period <= '" . mysqli_real_escape_string($con, $avaliability) . "' AND notice_period != '' ";
}

// Handle pagination
@$limit = $_GET['limit'];
@$skip = $_GET['skip'];
if (isset($limit) && isset($skip)) {
    @$offset = $skip * $limit;
    // Add a space before LIMIT to avoid syntax errors
    $limitClause = " LIMIT " . mysqli_real_escape_string($con, $limit) . " OFFSET " . mysqli_real_escape_string($con, $offset);
}

// Main query
$data = [];
// Concatenate the clauses with spaces
$query = "SELECT * FROM tbl_applicant_add" . $whereClause . $limitClause;

// Count query
$countQuery = "SELECT COUNT(*) AS total_applicants FROM tbl_applicant_add" . $whereClause;

$result = mysqli_query($con, $query);
$countResult = mysqli_query($con, $countQuery);

$count = 0;
if ($countResult) {
    $countRow = mysqli_fetch_assoc($countResult);
    $count = $countRow['total_applicants'];
}

while ($fetchapplicants = mysqli_fetch_assoc($result)) {
    $data[] = $fetchapplicants;
}

if (empty($data)) {
    $status = ['status' => false, 'message' => 'No Applicants Found'];
} else {
    $status = ['status' => true, 'data' => $data, 'count' => $count];
}

echo json_encode($status);
