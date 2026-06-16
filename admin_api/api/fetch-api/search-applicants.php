<?php
// DEBUG MODE - Remove after fixing
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// ============================================
// STEP 1: TEST DB CONNECTION
// ============================================
try {
    include "../../includes/db-connection/db.php";
} catch (Exception $e) {
    echo json_encode([
        'status'  => false,
        'step'    => 'DB Include Failed',
        'message' => $e->getMessage()
    ]);
    exit();
}

if (!isset($con) || !$con) {
    echo json_encode([
        'status'  => false,
        'step'    => 'DB Connection Failed',
        'message' => 'Database connection variable $con not found'
    ]);
    exit();
}

// ============================================
// STEP 2: TEST JWT INCLUDE
// ============================================
try {
    include "../lib/jwt.php";
} catch (Exception $e) {
    echo json_encode([
        'status'  => false,
        'step'    => 'JWT Include Failed',
        'message' => $e->getMessage()
    ]);
    exit();
}

// ============================================
// STEP 3: TOKEN VERIFY
// ============================================
$headers = apache_request_headers();
$token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');
if (!empty($token)) {
    TokenVerify($token);
}

// ============================================
// STEP 4: GET & VALIDATE PARAMS
// ============================================
$searchTerm  = isset($_GET['search'])       ? trim($_GET['search'])       : '';
$recruiterId = isset($_GET['recruiter_id']) ? trim($_GET['recruiter_id']) : '';
$unAssigned  = isset($_GET['unAssigned'])   ? trim($_GET['unAssigned'])   : '';
$limit       = isset($_GET['limit'])        ? (int)$_GET['limit']        : 7;
$skip        = isset($_GET['skip'])         ? (int)$_GET['skip']         : 0;
$offset      = $skip * $limit;

// Debug - show received params
$debugParams = [
    'received_search'      => $searchTerm,
    'received_recruiterId' => $recruiterId,
    'received_unAssigned'  => $unAssigned,
    'received_limit'       => $limit,
    'received_skip'        => $skip,
    'received_offset'      => $offset,
];

if (empty($searchTerm)) {
    echo json_encode([
        'status'  => false,
        'step'    => 'Validation Failed',
        'message' => 'Search term is required',
        'debug'   => $debugParams
    ]);
    exit();
}

// Sanitize
$searchTerm  = mysqli_real_escape_string($con, $searchTerm);
$limitClause = "LIMIT $limit OFFSET $offset";

// ============================================
// STEP 5: BUILD SEARCH CLAUSE
// ============================================
$searchClause = "(
    T1.full_name                LIKE '%$searchTerm%' OR
    T1.applicant_auto_create_id LIKE '%$searchTerm%' OR
    T1.main_skill               LIKE '%$searchTerm%' OR
    T1.preferred_location       LIKE '%$searchTerm%' OR
    T1.mobile_number            LIKE '%$searchTerm%'
)";

// ============================================
// STEP 6: BUILD QUERIES BASED ON ROLE
// ============================================
if (!empty($recruiterId)) {
    $recruiterId = mysqli_real_escape_string($con, $recruiterId);
    $caseUsed = 'recruiter';

    $finalCountQuery = "
        SELECT COUNT(*) as total
        FROM tbl_applicant_add T1
        INNER JOIN applicant_recruiter_assigns T2
            ON T1.applicant_id = T2.applicant_assigned_id AND T2.status = 1
        INNER JOIN tbl_allyted_team T3
            ON T2.recruiter_assigned_id = T3.allyted_member_id AND T3.status = 1
        WHERE T2.recruiter_assigned_id = '$recruiterId'
        AND T1.status = 1
        AND $searchClause
    ";

    $mainQuery = "
        SELECT T1.*, T3.member_name AS RecruiterName
        FROM tbl_applicant_add T1
        INNER JOIN applicant_recruiter_assigns T2
            ON T1.applicant_id = T2.applicant_assigned_id AND T2.status = 1
        INNER JOIN tbl_allyted_team T3
            ON T2.recruiter_assigned_id = T3.allyted_member_id AND T3.status = 1
        WHERE T2.recruiter_assigned_id = '$recruiterId'
        AND T1.status = 1
        AND $searchClause
        ORDER BY T1.applicant_id DESC
        $limitClause
    ";

} elseif (!empty($unAssigned) && $unAssigned == 1) {
    $caseUsed = 'unassigned';

    $finalCountQuery = "
        SELECT COUNT(*) as total
        FROM tbl_applicant_add T1
        WHERE T1.applicant_id NOT IN (
            SELECT applicant_assigned_id
            FROM applicant_recruiter_assigns
            WHERE status = 1
        )
        AND T1.status = 1
        AND $searchClause
    ";

    $mainQuery = "
        SELECT T1.*
        FROM tbl_applicant_add T1
        WHERE T1.applicant_id NOT IN (
            SELECT applicant_assigned_id
            FROM applicant_recruiter_assigns
            WHERE status = 1
        )
        AND T1.status = 1
        AND $searchClause
        ORDER BY T1.applicant_id DESC
        $limitClause
    ";

} else {
    $caseUsed = 'admin_all';

    $finalCountQuery = "
        SELECT COUNT(*) as total
        FROM tbl_applicant_add T1
        LEFT JOIN applicant_recruiter_assigns T2
            ON T1.applicant_id = T2.applicant_assigned_id AND T2.status = 1
        LEFT JOIN tbl_allyted_team T3
            ON T2.recruiter_assigned_id = T3.allyted_member_id AND T3.status = 1
        WHERE T1.status = 1
        AND $searchClause
    ";

    $mainQuery = "
        SELECT T1.*, T3.member_name AS RecruiterName
        FROM tbl_applicant_add T1
        LEFT JOIN applicant_recruiter_assigns T2
            ON T1.applicant_id = T2.applicant_assigned_id AND T2.status = 1
        LEFT JOIN tbl_allyted_team T3
            ON T2.recruiter_assigned_id = T3.allyted_member_id AND T3.status = 1
        WHERE T1.status = 1
        AND $searchClause
        ORDER BY T1.applicant_id DESC
        $limitClause
    ";
}

// ============================================
// STEP 7: RUN COUNT QUERY
// ============================================
$countResult = mysqli_query($con, $finalCountQuery);
if (!$countResult) {
    echo json_encode([
        'status'  => false,
        'step'    => 'Count Query Failed',
        'message' => mysqli_error($con),
        'query'   => $finalCountQuery,
        'debug'   => $debugParams
    ]);
    exit();
}
$countRow = mysqli_fetch_assoc($countResult);
$count    = (int)$countRow['total'];

// ============================================
// STEP 8: RUN MAIN QUERY
// ============================================
$mainResult = mysqli_query($con, $mainQuery);
if (!$mainResult) {
    echo json_encode([
        'status'  => false,
        'step'    => 'Main Query Failed',
        'message' => mysqli_error($con),
        'query'   => $mainQuery,
        'debug'   => $debugParams
    ]);
    exit();
}

$data = [];
while ($row = mysqli_fetch_assoc($mainResult)) {
    $data[] = $row;
}

// ============================================
// STEP 9: RESPONSE WITH DEBUG INFO
// ============================================
if (empty($data)) {
    echo json_encode([
        'status'     => false,
        'data'       => [],
        'count'      => 0,
        'message'    => 'No Applicants Found',
        'debug'      => $debugParams,
        'case_used'  => $caseUsed,
        'count_query'=> $finalCountQuery,
        'main_query' => $mainQuery
    ]);
} else {
    echo json_encode([
        'status'     => true,
        'data'       => $data,
        'count'      => $count,
        'debug'      => $debugParams,
        'case_used'  => $caseUsed,
        'count_query'=> $finalCountQuery,
        'main_query' => $mainQuery
    ]);
}
exit();
?>