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

$file = $_FILES['file']['tmp_name'];

try {

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    if (empty($rows)) {
        die(json_encode(["status" => false, "error" => "Excel file empty"]));
    }

    unset($rows[0]); // Remove header

    $inserted = 0;
    $duplicates = 0;
    $errors = [];
    $skippedSAP = 0;

    foreach ($rows as $index => $row) {

        $rowNumber = $index + 1;

        $technology = mysqli_real_escape_string($con, trim($row[0] ?? ''));
        $skill = mysqli_real_escape_string($con, trim($row[1] ?? ''));

        if (empty($technology) || empty($skill)) {
            $errors[] = "Row $rowNumber - Technology or Skill empty";
            continue;
        }

        /* ===== EXCLUDE SAP ===== */

        if (stripos($technology, 'sap') !== false) {
            $skippedSAP++;
            continue;
        }

        /* ===== GET TECHNOLOGY ID ===== */

        $techQuery = mysqli_query($con,"
            SELECT id 
            FROM tbl_technology
            WHERE LOWER(technology) = LOWER('$technology')
            LIMIT 1
        ");

        if (!$techQuery || mysqli_num_rows($techQuery) == 0) {
            $errors[] = "Row $rowNumber - Technology not found ($technology)";
            continue;
        }

        $techData = mysqli_fetch_assoc($techQuery);
        $technology_id = $techData['id'];

        /* ===== CHECK DUPLICATE SKILL ===== */

        $checkSkill = mysqli_query($con,"
            SELECT id
            FROM tbl_skill
            WHERE LOWER(skill_name) = LOWER('$skill')
            AND technology_id = '$technology_id'
        ");

        if (mysqli_num_rows($checkSkill) > 0) {
            $duplicates++;
            continue;
        }

        /* ===== INSERT SKILL ===== */

        $insert = mysqli_query($con,"
            INSERT INTO tbl_skill
            (
                technology_id,
                skill_name,
                created_date,
                updated_date,
                status
            )
            VALUES
            (
                '$technology_id',
                '$skill',
                NOW(),
                NOW(),
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
        "sap_skipped" => $skippedSAP,
        "errors" => $errors
    ]);

} catch (Throwable $e) {

    echo json_encode([
        "status" => false,
        "exception" => $e->getMessage(),
        "line" => $e->getLine()
    ]);
}