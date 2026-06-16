<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

/* ================= DB ================= */

include "../../includes/db-connection/db.php";
require dirname(__DIR__, 2) . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!$con) {
    die(json_encode(["status" => false, "error" => "Database connection failed"]));
}

/* ================= FILE CHECK ================= */

if (!isset($_FILES['file'])) {
    die(json_encode(["status" => false, "error" => "No file uploaded"]));
}

if ($_FILES['file']['error'] !== 0) {
    die(json_encode(["status" => false, "error" => "File upload error code: " . $_FILES['file']['error']]));
}

$file = $_FILES['file']['tmp_name'];

/* ===============================================================
   HELPER: Generate applicant auto ID  e.g. JDAPPHJVC146
   Same logic as the existing single-add API
   =============================================================== */

function generateApplicantId($con, $fullname) {
    $getDbCount = mysqli_query($con, "SELECT applicant_id FROM tbl_applicant_add ORDER BY applicant_id DESC LIMIT 1");
    $lastRow    = mysqli_fetch_assoc($getDbCount);
    $incCount   = ($lastRow['applicant_id'] ?? 145) + 1;

    // Strip non-ASCII characters first, then take 4 chars safely
    $cleanName = preg_replace('/[^A-Za-z0-9]/', '', $fullname); // remove non-alphanumeric
    $namePart  = strtoupper(substr($cleanName, 0, 4));

    // Fallback if name is empty after stripping
    if (empty($namePart)) {
        $namePart = 'APPL';
    }

    return 'JDAPP' . $namePart . $incCount;
}

/* ===============================================================
   HELPER: Resolve primary skill vs secondary skills
   Logic:
     1. Split the Excel "Skill" cell by common delimiters (, / |)
     2. For each token, check skill_set table:
        - If found as primary_skill  → candidate for main_skill
        - If found as secondary_skill → goes to primary_skills (secondary bucket)
        - Not found anywhere         → goes to primary_skills bucket
     3. First primary_skill match becomes main_skill.
        All others go to primary_skills (comma-separated).
   =============================================================== */
function resolveSkills($con, $rawSkillString) {
    // Split on comma, slash, pipe, semicolon
    $tokens = preg_split('/[,\/|;]+/', $rawSkillString);
    $tokens = array_map('trim', $tokens);
    $tokens = array_filter($tokens); // remove empty

    $mainSkill       = '';   // saved to main_skill column
    $secondarySkills = [];   // saved to primary_skills column

    foreach ($tokens as $token) {
        $escaped = mysqli_real_escape_string($GLOBALS['con'], $token);

        // Check if this token exists as a primary_skill in skill_set
        $checkPrimary = mysqli_query(
            $GLOBALS['con'],
            "SELECT id FROM skill_set
             WHERE LOWER(primary_skill) = LOWER('$escaped')
             AND status = 1
             LIMIT 1"
        );

        if ($checkPrimary && mysqli_num_rows($checkPrimary) > 0) {
            // It is a primary skill
            if (empty($mainSkill)) {
                $mainSkill = $token; // First primary match → main_skill
            } else {
                $secondarySkills[] = $token; // Extra primary skills → secondary bucket
            }
            continue;
        }

        // Check if it exists as a secondary_skill in skill_set
        $checkSecondary = mysqli_query(
            $GLOBALS['con'],
            "SELECT id FROM skill_set
             WHERE LOWER(secondary_skill) LIKE LOWER('%$escaped%')
             AND status = 1
             LIMIT 1"
        );

        if ($checkSecondary && mysqli_num_rows($checkSecondary) > 0) {
            $secondarySkills[] = $token;
            continue;
        }

        // Not found in skill_set at all → treat as secondary
        $secondarySkills[] = $token;
    }

    // If no primary was identified at all, promote the first token
    if (empty($mainSkill) && !empty($secondarySkills)) {
        $mainSkill = array_shift($secondarySkills);
    }

    return [
        'main_skill'     => $mainSkill,
        'primary_skills' => implode(', ', $secondarySkills)
    ];
}

/* ================= EXCEL COLUMN MAP =================
   Index | Excel Header
   0     | Timestamp
   1     | Name
   2     | Email
   3     | Contact Number
   4     | Current Location
   5     | Preferred Location
   6     | Skill
   7     | Relevant Experience (Years)
   8     | Total Experience (Years)
   9     | Current Company
   10    | Notice Period (Days)
   11    | Current CTC
   12    | Current Offer
   13    | Expected CTC
   14    | Upload your resume
   15    | Reference ID (OFFICIAL USE)
   ===================================================== */

try {

    if (!file_exists($file)) {
        die(json_encode(["status" => false, "error" => "Uploaded file not found on server"]));
    }

    $spreadsheet = IOFactory::load($file);
    $sheet       = $spreadsheet->getActiveSheet();
    $rows        = $sheet->toArray();

    if (empty($rows)) {
        die(json_encode(["status" => false, "error" => "Excel file is empty"]));
    }

    unset($rows[0]); // Remove header row

    $inserted   = 0;
    $updated    = 0;
    $skipped    = 0;
    $errors     = [];

    foreach ($rows as $index => $row) {

        $rowNumber = $index + 1;

        /* ===== Read & sanitize each column ===== */
        $fullname           = trim($row[1]  ?? '');
        $email              = trim($row[2]  ?? '');
        $mobile             = trim($row[3]  ?? '');
        $current_location   = trim($row[4]  ?? '');
        $preferred_location = trim($row[5]  ?? '');
        $skill_raw          = trim($row[6]  ?? '');
        $relevant_exp       = trim($row[7]  ?? '');
        $total_exp          = trim($row[8]  ?? '');
        $current_company    = trim($row[9]  ?? '');
        $notice_period      = trim($row[10] ?? '');
        $current_ctc        = trim($row[11] ?? '');
        // index 12 = Current Offer (not in DB columns — skip or store if needed)
        $expected_ctc       = trim($row[13] ?? '');
        $resume             = trim($row[14] ?? '');
        $reference_name     = trim($row[15] ?? '');

        // Skip completely empty rows
        if (empty($fullname) && empty($email) && empty($mobile)) {
            $skipped++;
            continue;
        }

        // Email & mobile are required
        if (empty($email) || empty($mobile)) {
            $errors[] = "Row $rowNumber - Email or Mobile is empty (Name: $fullname)";
            continue;
        }

        /* ===== Resolve skills via skill_set table ===== */
        $skillResolved  = resolveSkills($con, $skill_raw);
        $main_skill     = $skillResolved['main_skill'];
        $primary_skills = $skillResolved['primary_skills'];

        /* ===== Escape all values ===== */
        $fullname_e           = mysqli_real_escape_string($con, $fullname);
        $email_e              = mysqli_real_escape_string($con, $email);
        $mobile_e             = mysqli_real_escape_string($con, $mobile);
        $current_location_e   = mysqli_real_escape_string($con, $current_location);
        $preferred_location_e = mysqli_real_escape_string($con, $preferred_location);
        $main_skill_e         = mysqli_real_escape_string($con, $main_skill);
        $primary_skills_e     = mysqli_real_escape_string($con, $primary_skills);
        $relevant_exp_e       = mysqli_real_escape_string($con, $relevant_exp);
        $total_exp_e          = mysqli_real_escape_string($con, $total_exp);
        $current_company_e    = mysqli_real_escape_string($con, $current_company);
        $notice_period_e      = mysqli_real_escape_string($con, $notice_period);
        $current_ctc_e        = mysqli_real_escape_string($con, $current_ctc);
        $expected_ctc_e       = mysqli_real_escape_string($con, $expected_ctc);
        $resume_e             = mysqli_real_escape_string($con, $resume);
        $reference_name_e     = mysqli_real_escape_string($con, $reference_name);

        /* ===== Duplicate Check (email OR mobile) ===== */
        $checkQuery = mysqli_query($con, "
            SELECT applicant_id, applicant_auto_create_id
            FROM tbl_applicant_add
            WHERE e_mail = '$email_e'
            OR mobile_number = '$mobile_e'
            LIMIT 1
        ");

        if (!$checkQuery) {
            $errors[] = "Row $rowNumber - Duplicate check failed: " . mysqli_error($con);
            continue;
        }

        if (mysqli_num_rows($checkQuery) > 0) {

            /* ===== DUPLICATE FOUND → UPDATE the existing row ===== */
            $existingRow = mysqli_fetch_assoc($checkQuery);
            $existing_id = $existingRow['applicant_id'];

            $updateQuery = mysqli_query($con, "
                UPDATE tbl_applicant_add SET
                    full_name           = '$fullname_e',
                    mobile_number       = '$mobile_e',
                    e_mail              = '$email_e',
                    curr_location       = '$current_location_e',
                    preferred_location  = '$preferred_location_e',
                    main_skill          = '$main_skill_e',
                    primary_skills      = '$primary_skills_e',
                    relevant_exp        = '$relevant_exp_e',
                    total_expereience   = '$total_exp_e',
                    curr_company        = '$current_company_e',
                    notice_period       = '$notice_period_e',
                    current_package     = '$current_ctc_e',
                    expected_package    = '$expected_ctc_e',
                    resume_image        = '$resume_e',
                    refrence_name       = '$reference_name_e'
                WHERE applicant_id = '$existing_id'
            ");

            if ($updateQuery) {
                $updated++;
            } else {
                $errors[] = "Row $rowNumber - Update failed: " . mysqli_error($con);
            }

            continue; // Move to next row
        }

        /* ===== NEW APPLICANT → Generate ID & Insert ===== */
        $applicant_auto_create_id = generateApplicantId($con, $fullname);
        $applicant_auto_create_id_e = mysqli_real_escape_string($con, $applicant_auto_create_id);

        $insertQuery = mysqli_query($con, "
            INSERT INTO tbl_applicant_add (
                applicant_auto_create_id,
                full_name,
                mobile_number,
                e_mail,
                curr_location,
                preferred_location,
                main_skill,
                primary_skills,
                relevant_exp,
                total_expereience,
                curr_company,
                notice_period,
                current_package,
                expected_package,
                resume_image,
                refrence_name,
                added_by_admin,
                status
            ) VALUES (
                '$applicant_auto_create_id_e',
                '$fullname_e',
                '$mobile_e',
                '$email_e',
                '$current_location_e',
                '$preferred_location_e',
                '$main_skill_e',
                '$primary_skills_e',
                '$relevant_exp_e',
                '$total_exp_e',
                '$current_company_e',
                '$notice_period_e',
                '$current_ctc_e',
                '$expected_ctc_e',
                '$resume_e',
                '$reference_name_e',
                '1',
                '1'
            )
        ");

        if ($insertQuery) {
            $inserted++;
        } else {
            $errors[] = "Row $rowNumber - Insert failed: " . mysqli_error($con);
        }
    }

    echo json_encode([
        "status"         => true,
        "inserted_count" => $inserted,
        "updated_count"  => $updated,
        "skipped_count"  => $skipped,
        "errors"         => $errors
    ]);

} catch (Throwable $e) {

    echo json_encode([
        "status"    => false,
        "exception" => $e->getMessage(),
        "line"      => $e->getLine()
    ]);
}