<?php
// Set response headers
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

// Include database connection
include "../../includes/db-connection/db.php";

if (isset($_GET['emp_Id']) && !empty($_GET['emp_Id'])) {
    $emp_Id = $_GET['emp_Id'];

    // Query to count employer records
    $EmployerCountQuery = mysqli_query($con, "SELECT * FROM profile_view_bycount WHERE employer_id='" . $emp_Id . "'");
    $EmployerCount = mysqli_num_rows($EmployerCountQuery);

    $RmCount = 0; // Default RM count

    // If both emp_Id and RmId are provided
    if (isset($_GET['RmId']) && !empty($_GET['RmId'])) {
        $RmId = $_GET['RmId'];

        // Query to count employer and RM records
        $RmCountQuery = mysqli_query($con, "SELECT * FROM profile_view_bycount WHERE employer_id='" . $emp_Id . "' AND Rm_id='" . $RmId . "'");
        $RmCount = mysqli_num_rows($RmCountQuery);
    }

    // Prepare success response
    $stat = [
        'status' => true,
        'RMcount' => $RmCount,
        'EMPcount' => $EmployerCount
    ];
} else {
    // Prepare error response
    $stat = [
        'status' => false,
        'message' => 'Invalid Params'
    ];
}

// Output JSON response
echo json_encode($stat);
