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
require dirname(__DIR__, 1) . '/vendor/autoload.php';

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

try {

    if (!file_exists($file)) {
        die(json_encode(["status" => false, "error" => "Uploaded file not found on server"]));
    }

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    if (empty($rows)) {
        die(json_encode(["status" => false, "error" => "Excel file is empty"]));
    }

    unset($rows[0]); // Remove header row (Primary Skill / Secondary Skill)

    $inserted   = 0;
    $duplicates = 0;
    $skipped    = 0;
    $errors     = [];

    $timestamp = time(); // created_on value for all rows in this batch

    foreach ($rows as $index => $row) {

        $rowNumber = $index + 1;

        /* ===== Excel Mapping ===== */
        // Column A (index 0) = Primary Skill
        // Column B (index 1) = Secondary Skill

        $primary_skill   = trim($row[0] ?? '');
        $secondary_skill = trim($row[1] ?? '');

        // Skip completely empty rows
        if (empty($primary_skill) && empty($secondary_skill)) {
            $skipped++;
            continue;
        }

        // Primary skill is required
        if (empty($primary_skill)) {
            $errors[] = "Row $rowNumber - Primary skill is empty";
            continue;
        }

        $primary_skill_escaped   = mysqli_real_escape_string($con, $primary_skill);
        $secondary_skill_escaped = mysqli_real_escape_string($con, $secondary_skill);

        /* ===== Duplicate Check (same primary + secondary combo) ===== */

        $checkQuery = mysqli_query($con, "
            SELECT id 
            FROM skill_set 
            WHERE primary_skill = '$primary_skill_escaped'
            AND secondary_skill = '$secondary_skill_escaped'
            LIMIT 1
        ");

        if (!$checkQuery) {
            $errors[] = "Row $rowNumber - Duplicate check failed: " . mysqli_error($con);
            continue;
        }

        if (mysqli_num_rows($checkQuery) > 0) {
            $duplicates++;
            continue;
        }

        /* ===== Insert ===== */

        $insert = mysqli_query($con, "
            INSERT INTO skill_set (
                primary_skill,
                secondary_skill,
                created_on,
                status
            ) VALUES (
                '$primary_skill_escaped',
                '$secondary_skill_escaped',
                '$timestamp',
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
        "status"          => true,
        "inserted_count"  => $inserted,
        "duplicate_count" => $duplicates,
        "skipped_count"   => $skipped,
        "errors"          => $errors
    ]);

} catch (Throwable $e) {

    echo json_encode([
        "status"    => false,
        "exception" => $e->getMessage(),
        "line"      => $e->getLine()
    ]);
}