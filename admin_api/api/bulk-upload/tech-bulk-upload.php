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

/* ================= HELPER: Get or Create Technology ================= */
function getOrCreateTechnology($con, $technology) {
    $technology = mysqli_real_escape_string($con, trim($technology));

    if (empty($technology)) return null;

    // Check if technology already exists (case-insensitive)
    $checkQuery = mysqli_query($con, "
        SELECT id FROM tbl_technology
        WHERE LOWER(technology) = LOWER('$technology')
        LIMIT 1
    ");

    if ($checkQuery && mysqli_num_rows($checkQuery) > 0) {
        $row = mysqli_fetch_assoc($checkQuery);
        return ['id' => $row['id'], 'is_new' => false];
    }

    // Insert new technology
    $insert = mysqli_query($con, "
        INSERT INTO tbl_technology (technology, created_on, updated_on, status)
        VALUES ('$technology', NOW(), NOW(), 1)
    ");

    if ($insert) {
        return ['id' => mysqli_insert_id($con), 'is_new' => true];
    }

    return null;
}

/* ================= HELPER: Insert Skill (if not duplicate) ================= */
function insertSkill($con, $technologyId, $skillName) {
    $skillName = mysqli_real_escape_string($con, trim($skillName));

    if (empty($skillName)) return 'empty';

    // Check duplicate skill for same technology
    $checkQuery = mysqli_query($con, "
        SELECT id FROM tbl_skill
        WHERE technology_id = '$technologyId'
        AND LOWER(skill_name) = LOWER('$skillName')
        LIMIT 1
    ");

    if ($checkQuery && mysqli_num_rows($checkQuery) > 0) {
        return 'duplicate';
    }

    $insert = mysqli_query($con, "
        INSERT INTO tbl_skill (technology_id, skill_name, created_date, updated_date, status)
        VALUES ('$technologyId', '$skillName', NOW(), NOW(), 1)
    ");

    return $insert ? 'inserted' : 'error';
}

/* ================= FILE CHECK ================= */
if (!isset($_FILES['file'])) {
    die(json_encode(["status" => false, "error" => "No file uploaded"]));
}

$file = $_FILES['file']['tmp_name'];

try {
    $spreadsheet = IOFactory::load($file);
    $sheet       = $spreadsheet->getActiveSheet();
    $rows        = $sheet->toArray();

    if (empty($rows)) {
        die(json_encode(["status" => false, "error" => "Excel file is empty"]));
    }

    unset($rows[0]); // Remove header row

    /* ===== Counters ===== */
    $techInserted   = 0;
    $techDuplicates = 0;
    $skillInserted  = 0;
    $skillDuplicates = 0;
    $errors         = [];

    foreach ($rows as $index => $row) {
        $rowNumber = $index + 1;

        /* ===== Read Columns ===== */
        $technology    = trim($row[0] ?? '');   // Column A - Domain/Technology
        $primarySkill  = trim($row[1] ?? '');   // Column B - Primary Skill
        $secondarySkill = trim($row[2] ?? '');  // Column C - Secondary Skill (comma-separated)

        if (empty($technology)) {
            $errors[] = "Row $rowNumber - Technology/Domain is empty, skipping row";
            continue;
        }

        /* ===== Step 1: Get or Create Technology ===== */
        $techResult = getOrCreateTechnology($con, $technology);

        if (!$techResult) {
            $errors[] = "Row $rowNumber - Failed to get/create technology '$technology'";
            continue;
        }

        $technologyId = $techResult['id'];

        if ($techResult['is_new']) {
            $techInserted++;
        } else {
            $techDuplicates++;
        }

        /* ===== Step 2: Insert Primary Skill ===== */
        if (!empty($primarySkill)) {
            $result = insertSkill($con, $technologyId, $primarySkill);

            if ($result === 'inserted')   $skillInserted++;
            elseif ($result === 'duplicate') $skillDuplicates++;
            elseif ($result === 'error')  $errors[] = "Row $rowNumber - Primary skill insert failed: " . mysqli_error($con);
        }

        /* ===== Step 3: Insert Secondary Skills (split by comma) ===== */
        if (!empty($secondarySkill)) {
            $secondarySkills = explode(',', $secondarySkill);

            foreach ($secondarySkills as $sSkill) {
                $sSkill = trim($sSkill);

                if (empty($sSkill)) continue;

                $result = insertSkill($con, $technologyId, $sSkill);

                if ($result === 'inserted')      $skillInserted++;
                elseif ($result === 'duplicate') $skillDuplicates++;
                elseif ($result === 'error')     $errors[] = "Row $rowNumber - Secondary skill '$sSkill' insert failed: " . mysqli_error($con);
            }
        }
    }

    /* ===== Response ===== */
    echo json_encode([
        "status"                  => true,
        "technology_inserted"     => $techInserted,
        "technology_duplicates"   => $techDuplicates,
        "skill_inserted"          => $skillInserted,
        "skill_duplicates"        => $skillDuplicates,
        "errors"                  => $errors
    ]);

} catch (Throwable $e) {
    echo json_encode([
        "status"    => false,
        "exception" => $e->getMessage(),
        "line"      => $e->getLine()
    ]);
}