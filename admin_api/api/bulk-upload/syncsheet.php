<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

/* ===== CORS ===== */
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
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); exit();
}

$SECRET_TOKEN = 'a3f8c2d1e4b5a6f7c8d9e0f1a2b3c4d5';
$authHeader   = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if ($authHeader !== 'Bearer ' . $SECRET_TOKEN) {
    http_response_code(401);
    die(json_encode(["status" => false, "error" => "Unauthorized"]));
}

/* ===== DB ===== */
include "../../includes/db-connection/db.php";
if (!$con) {
    die(json_encode(["status" => false, "error" => "Database connection failed"]));
}

/* ===== Read JSON body ===== */
$rawBody = file_get_contents('php://input');

if (empty($rawBody) && isset($_POST['data'])) {
    $rawBody = $_POST['data'];
}

$input = json_decode($rawBody, true);

if (empty($rawBody)) {
    die(json_encode([
        "status"  => false,
        "error"   => "Empty body",
        "method"  => $_SERVER['REQUEST_METHOD'],
        "content" => $_SERVER['CONTENT_TYPE'] ?? 'none'
    ]));
}

if (!$input) {
    die(json_encode([
        "status" => false,
        "error"  => "Invalid JSON body",
        "raw"    => substr($rawBody, 0, 100)
    ]));
}

/* ===== Helpers ===== */
function generateApplicantId($con, $fullname) {
    $getDbCount = mysqli_query($con, "SELECT applicant_id FROM tbl_applicant_add ORDER BY applicant_id DESC LIMIT 1");
    $lastRow    = mysqli_fetch_assoc($getDbCount);
    $incCount   = ($lastRow['applicant_id'] ?? 145) + 1;
    $cleanName  = preg_replace('/[^A-Za-z0-9]/', '', $fullname);
    $namePart   = strtoupper(substr($cleanName, 0, 4));
    if (empty($namePart)) $namePart = 'APPL';
    return 'JDAPP' . $namePart . $incCount;
}

function resolveSkills($con, $rawSkillString) {
    $tokens = preg_split('/[,\/|;]+/', $rawSkillString);
    $tokens = array_filter(array_map('trim', $tokens));
    $mainSkill = ''; $secondarySkills = [];
    foreach ($tokens as $token) {
        $escaped = mysqli_real_escape_string($GLOBALS['con'], $token);
        $r = mysqli_query($GLOBALS['con'],
            "SELECT id FROM skill_set WHERE LOWER(primary_skill) = LOWER('$escaped') AND status = 1 LIMIT 1");
        if ($r && mysqli_num_rows($r) > 0) {
            if (empty($mainSkill)) { $mainSkill = $token; } else { $secondarySkills[] = $token; }
            continue;
        }
        $secondarySkills[] = $token;
    }
    if (empty($mainSkill) && !empty($secondarySkills)) $mainSkill = array_shift($secondarySkills);
    return ['main_skill' => $mainSkill, 'primary_skills' => implode(', ', $secondarySkills)];
}

/* ===== Auto-assign recruiter by reference_name =====
   - Looks up member_name in tbl_allyted_team
   - Skips if already assigned
   - Returns 'assigned' | 'already_assigned' | 'not_found' | 'skipped'
   ===================================================== */
function autoAssignRecruiter($con, $applicantId, $applicantName, $referenceName) {
    if (empty(trim($referenceName))) {
        return 'skipped'; // no reference name in sheet
    }

    $refEscaped = mysqli_real_escape_string($con, trim($referenceName));

    // Find recruiter by name in allyted_team
    $recruiterQ = mysqli_query($con, "
        SELECT allyted_member_id, member_name, role
        FROM tbl_allyted_team
        WHERE LOWER(TRIM(member_name)) = LOWER('$refEscaped')
          AND status = 1
        LIMIT 1
    ");

    if (!$recruiterQ || mysqli_num_rows($recruiterQ) === 0) {
        return 'not_found'; // reference name doesn't match any recruiter
    }

    $recruiter     = mysqli_fetch_assoc($recruiterQ);
    $recruiterId   = $recruiter['allyted_member_id'];
    $recruiterRole = mysqli_real_escape_string($con, $recruiter['role']);

    // Check if already assigned
    $dupQ = mysqli_query($con, "
        SELECT id FROM applicant_recruiter_assigns
        WHERE applicant_assigned_id = '$applicantId'
          AND recruiter_assigned_id = '$recruiterId'
          AND status = 1
        LIMIT 1
    ");

    if ($dupQ && mysqli_num_rows($dupQ) > 0) {
        return 'already_assigned';
    }

    // Insert assignment
    $insert = mysqli_query($con, "
        INSERT INTO applicant_recruiter_assigns
            (recruiter_assigned_id, applicant_assigned_id, role, role_id, status)
        VALUES
            ('$recruiterId', '$applicantId', '$recruiterRole', '$recruiterId', 1)
    ");

    return $insert ? 'assigned' : 'failed';
}

/* ===== Process rows ===== */
$rows = isset($input['rows']) ? $input['rows'] : [$input['row']];

$inserted         = 0;
$updated          = 0;
$errors           = [];
$recruiter_assigned       = 0;
$recruiter_already        = 0;
$recruiter_not_found      = 0;
$recruiter_skipped        = 0;

foreach ($rows as $i => $data) {
    $fullname           = trim($data['fullname']           ?? '');
    $email              = trim($data['email']              ?? '');
    $mobile             = trim($data['mobile']             ?? '');
    $current_location   = trim($data['current_location']   ?? '');
    $preferred_location = trim($data['preferred_location'] ?? '');
    $skill_raw          = trim($data['skill']              ?? '');
    $relevant_exp       = trim($data['relevant_exp']       ?? '');
    $total_exp          = trim($data['total_exp']          ?? '');
    $current_company    = trim($data['current_company']    ?? '');
    $notice_period      = trim($data['notice_period']      ?? '');
    $current_ctc        = trim($data['current_ctc']        ?? '');
    $expected_ctc       = trim($data['expected_ctc']       ?? '');
    $resume             = trim($data['resume']             ?? '');
    $reference_name     = trim($data['reference_name']     ?? '');

    if (empty($email) || empty($mobile)) {
        $errors[] = "Row $i — email or mobile missing (Name: $fullname)";
        continue;
    }

    $skillResolved  = resolveSkills($con, $skill_raw);
    $main_skill     = $skillResolved['main_skill'];
    $primary_skills = $skillResolved['primary_skills'];

    $e = fn($v) => mysqli_real_escape_string($con, $v);

    $checkQuery = mysqli_query($con,
        "SELECT applicant_id FROM tbl_applicant_add
         WHERE e_mail = '{$e($email)}' OR mobile_number = '{$e($mobile)}' LIMIT 1");

    if (mysqli_num_rows($checkQuery) > 0) {
        // ===== UPDATE existing applicant =====
        $existing_id = mysqli_fetch_assoc($checkQuery)['applicant_id'];
        $upd = mysqli_query($con, "
            UPDATE tbl_applicant_add SET
                full_name='{$e($fullname)}', mobile_number='{$e($mobile)}', e_mail='{$e($email)}',
                curr_location='{$e($current_location)}', preferred_location='{$e($preferred_location)}',
                main_skill='{$e($main_skill)}', primary_skills='{$e($primary_skills)}',
                relevant_exp='{$e($relevant_exp)}', total_expereience='{$e($total_exp)}',
                curr_company='{$e($current_company)}', notice_period='{$e($notice_period)}',
                current_package='{$e($current_ctc)}', expected_package='{$e($expected_ctc)}',
                resume_image='{$e($resume)}', refrence_name='{$e($reference_name)}'
            WHERE applicant_id = '$existing_id'");

        if ($upd) {
            $updated++;
            // Try assigning recruiter for updated applicant too
            $assignResult = autoAssignRecruiter($con, $existing_id, $fullname, $reference_name);
            if ($assignResult === 'assigned')          $recruiter_assigned++;
            elseif ($assignResult === 'already_assigned') $recruiter_already++;
            elseif ($assignResult === 'not_found')     $recruiter_not_found++;
            else                                       $recruiter_skipped++;
        } else {
            $errors[] = "Row $i — update failed: " . mysqli_error($con);
        }

    } else {
        // ===== INSERT new applicant =====
        $auto_id = generateApplicantId($con, $fullname);
        $ins = mysqli_query($con, "
            INSERT INTO tbl_applicant_add (
                applicant_auto_create_id, full_name, mobile_number, e_mail,
                curr_location, preferred_location, main_skill, primary_skills,
                relevant_exp, total_expereience, curr_company, notice_period,
                current_package, expected_package, resume_image, refrence_name,
                added_by_admin, status
            ) VALUES (
                '{$e($auto_id)}', '{$e($fullname)}', '{$e($mobile)}', '{$e($email)}',
                '{$e($current_location)}', '{$e($preferred_location)}',
                '{$e($main_skill)}', '{$e($primary_skills)}',
                '{$e($relevant_exp)}', '{$e($total_exp)}',
                '{$e($current_company)}', '{$e($notice_period)}',
                '{$e($current_ctc)}', '{$e($expected_ctc)}',
                '{$e($resume)}', '{$e($reference_name)}', '1', '1')");

        if ($ins) {
            $new_applicant_id = mysqli_insert_id($con);
            $inserted++;
            // Auto-assign recruiter for newly inserted applicant
            $assignResult = autoAssignRecruiter($con, $new_applicant_id, $fullname, $reference_name);
            if ($assignResult === 'assigned')          $recruiter_assigned++;
            elseif ($assignResult === 'already_assigned') $recruiter_already++;
            elseif ($assignResult === 'not_found')     $recruiter_not_found++;
            else                                       $recruiter_skipped++;
        } else {
            $errors[] = "Row $i — insert failed: " . mysqli_error($con);
        }
    }
}

echo json_encode([
    "status"                   => true,
    "inserted_count"           => $inserted,
    "updated_count"            => $updated,
    "recruiter_assigned"       => $recruiter_assigned,
    "recruiter_already_had"    => $recruiter_already,
    "recruiter_name_not_found" => $recruiter_not_found,
    "recruiter_no_ref_name"    => $recruiter_skipped,
    "errors"                   => $errors
]);