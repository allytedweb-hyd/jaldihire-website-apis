<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Global exception handler — catches fatal errors too
set_exception_handler(function (Throwable $e) {
    echo json_encode([
        'status'  => false,
        'error'   => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
        'trace'   => $e->getTraceAsString()
    ]);
    exit();
});

register_shutdown_function(function () {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo json_encode([
            'status'  => false,
            'fatal'   => $err['message'],
            'file'    => $err['file'],
            'line'    => $err['line']
        ]);
    }
});

/* ================= CORS ================= */
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
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

/* ================= DB & AUTH ================= */
include "../../includes/db-connection/db.php";
include "../vendor/autoload.php";
include "../lib/jwt.php";

$headers     = apache_request_headers();
$authHeader  = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

$token = str_replace('Bearer ', '', $authHeader);
TokenVerify($token);

/* ================= INPUT ================= */
// Accepts optional applicant_id to process a single applicant.
// If omitted, processes ALL applicants that:
//   - have a non-empty refrence_name
//   - are NOT yet assigned in applicant_recruiter_assigns
$json = file_get_contents('php://input');
$data = json_decode($json);

$singleApplicantId = isset($data->applicant_id) ? intval($data->applicant_id) : null;

/* ================= FETCH APPLICANTS ================= */
if ($singleApplicantId) {
    // Single applicant mode
    $applicantQuery = mysqli_query($con, "
        SELECT applicant_id, applicant_auto_create_id, full_name, e_mail, refrence_name
        FROM tbl_applicant_add
        WHERE applicant_id = '$singleApplicantId'
          AND refrence_name != ''
          AND status = 1
        LIMIT 1
    ");
} else {
    // Bulk mode — only applicants not yet assigned
    $applicantQuery = mysqli_query($con, "
        SELECT a.applicant_id, a.applicant_auto_create_id, a.full_name, a.e_mail, a.refrence_name
        FROM tbl_applicant_add a
        LEFT JOIN applicant_recruiter_assigns r
               ON r.applicant_assigned_id = a.applicant_id
              AND r.status = 1
        WHERE a.refrence_name != ''
          AND a.status = 1
          AND r.id IS NULL
    ");
}

if (!$applicantQuery) {
    echo json_encode(['status' => false, 'message' => 'DB error fetching applicants: ' . mysqli_error($con)]);
    exit();
}

if (mysqli_num_rows($applicantQuery) === 0) {
    echo json_encode([
        'status'  => true,
        'message' => $singleApplicantId
            ? 'Applicant not found, already assigned, or has no reference name'
            : 'No unassigned applicants with a reference name found'
    ]);
    exit();
}

/* ================= PROCESS EACH APPLICANT ================= */
$assigned = 0;
$skipped  = 0;
$errors   = [];

while ($applicant = mysqli_fetch_assoc($applicantQuery)) {

    $applicantId   = $applicant['applicant_id'];
    $applicantName = $applicant['full_name'];
    $applicantMail = $applicant['e_mail'];
    $referenceName = trim($applicant['refrence_name']);

    if (empty($referenceName)) {
        $skipped++;
        continue;
    }

    /* ----- Look up recruiter by member_name (case-insensitive) ----- */
    $refEscaped    = mysqli_real_escape_string($con, $referenceName);
    $recruiterQuery = mysqli_query($con, "
        SELECT allyted_member_id, member_name, member_email, role
        FROM tbl_allyted_team
        WHERE LOWER(TRIM(member_name)) = LOWER('$refEscaped')
          AND status = 1
        LIMIT 1
    ");

    if (!$recruiterQuery || mysqli_num_rows($recruiterQuery) === 0) {
        $errors[] = "Applicant ID $applicantId ($applicantName) — recruiter not found for reference name: '$referenceName'";
        $skipped++;
        continue;
    }

    $recruiter        = mysqli_fetch_assoc($recruiterQuery);
    $recruiterId      = $recruiter['allyted_member_id'];
    $recruiterName    = $recruiter['member_name'];
    $recruiterRole    = $recruiter['role'];

    /* ----- Skip if already assigned to this specific recruiter ----- */
    $dupCheck = mysqli_query($con, "
        SELECT id FROM applicant_recruiter_assigns
        WHERE applicant_assigned_id = '$applicantId'
          AND recruiter_assigned_id = '$recruiterId'
          AND status = 1
        LIMIT 1
    ");

    if ($dupCheck && mysqli_num_rows($dupCheck) > 0) {
        $skipped++;
        continue;
    }

    /* ----- Insert assignment ----- */
    $insertAssign = mysqli_query($con, "
        INSERT INTO applicant_recruiter_assigns
            (recruiter_assigned_id, applicant_assigned_id, role, role_id, status)
        VALUES
            ('$recruiterId', '$applicantId', '$recruiterRole', '$recruiterId', 1)
    ");

    if (!$insertAssign) {
        $errors[] = "Applicant ID $applicantId ($applicantName) — insert failed: " . mysqli_error($con);
        continue;
    }

    $assigned++;
}

/* ================= RESPONSE ================= */
echo json_encode([
    'status'         => true,
    'assigned_count' => $assigned,
    'skipped_count'  => $skipped,
    'errors'         => $errors
]);