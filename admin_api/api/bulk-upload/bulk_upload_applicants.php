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
    die(json_encode(["status" => false, "error" => $_FILES['file']['error']]));
}

$file = $_FILES['file']['tmp_name'];

try {

    if (!file_exists($file)) {
        die(json_encode(["status" => false, "error" => "Uploaded file not found"]));
    }

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    if (empty($rows)) {
        die(json_encode(["status" => false, "error" => "Excel file is empty"]));
    }

    unset($rows[0]); // Remove header row

    $inserted = 0;
    $duplicates = 0;
    $errors = [];

    // Function to generate random 4 uppercase letters
    function generateRandomLetters($length = 4) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    foreach ($rows as $index => $row) {

        $rowNumber = $index + 1;

        /* ===== Excel Mapping ===== */

        $fullname           = mysqli_real_escape_string($con, trim($row[1] ?? ''));
        $email              = mysqli_real_escape_string($con, trim($row[2] ?? ''));
        $mobile             = mysqli_real_escape_string($con, trim($row[3] ?? ''));
        $current_location   = mysqli_real_escape_string($con, trim($row[4] ?? ''));
        $preferred_location = mysqli_real_escape_string($con, trim($row[5] ?? ''));
        $skill              = mysqli_real_escape_string($con, trim($row[6] ?? ''));
        $relevant_exp       = mysqli_real_escape_string($con, trim($row[7] ?? ''));
        $total_exp          = mysqli_real_escape_string($con, trim($row[8] ?? ''));
        $current_company    = mysqli_real_escape_string($con, trim($row[9] ?? ''));
        $notice_period      = mysqli_real_escape_string($con, trim($row[10] ?? ''));
        $current_ctc        = mysqli_real_escape_string($con, trim($row[11] ?? ''));
        $expected_ctc       = mysqli_real_escape_string($con, trim($row[13] ?? ''));
        $resume             = mysqli_real_escape_string($con, trim($row[14] ?? ''));

        if (empty($email) || empty($mobile)) {
            $errors[] = "Row $rowNumber - Email or Mobile empty";
            continue;
        }

        /* ===== Duplicate Check ===== */

        $checkQuery = mysqli_query($con, "
            SELECT applicant_id 
            FROM tbl_applicant_add 
            WHERE e_mail = '$email' 
            OR mobile_number = '$mobile'
        ");

        if (!$checkQuery) {
            $errors[] = "Row $rowNumber - Duplicate check failed: " . mysqli_error($con);
            continue;
        }

        if (mysqli_num_rows($checkQuery) > 0) {
            $duplicates++;
            continue;
        }

        /* ===== Generate JDAPPHJVC146 ID ===== */

        $lastQuery = mysqli_query($con, "
            SELECT applicant_auto_create_id 
            FROM tbl_applicant_add 
            ORDER BY applicant_id DESC 
            LIMIT 1
        ");

        if ($lastQuery && mysqli_num_rows($lastQuery) > 0) {
            $lastRow = mysqli_fetch_assoc($lastQuery);
            preg_match('/(\d+)$/', $lastRow['applicant_auto_create_id'], $matches);
            $lastNumber = isset($matches[1]) ? (int)$matches[1] : 145;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 146; // starting number
        }

        $randomLetters = generateRandomLetters(4);
        $applicant_auto_create_id = "JDAPP" . $randomLetters . $newNumber;

        /* ===== Insert ===== */

        $insert = mysqli_query($con, "
            INSERT INTO tbl_applicant_add (
                applicant_auto_create_id,
                full_name,
                mobile_number,
                e_mail,
                curr_location,
                preferred_location,
                primary_skills,
                relevant_exp,
                total_expereience,
                curr_company,
                notice_period,
                current_package,
                expected_package,
                resume_image,
                status
            ) VALUES (
                '$applicant_auto_create_id',
                '$fullname',
                '$mobile',
                '$email',
                '$current_location',
                '$preferred_location',
                '$skill',
                '$relevant_exp',
                '$total_exp',
                '$current_company',
                '$notice_period',
                '$current_ctc',
                '$expected_ctc',
                '$resume',
                1
            )
        ");

        if ($insert) {
            $inserted++;
        } else {
            $errors[] = "Row $rowNumber - Insert failed: " . mysqli_error($con);
        }
    }

    echo json_encode([
        "status" => true,
        "inserted_count" => $inserted,
        "duplicate_count" => $duplicates,
        "errors" => $errors
    ]);

} catch (Throwable $e) {

    echo json_encode([
        "status" => false,
        "exception" => $e->getMessage(),
        "line" => $e->getLine()
    ]);
}