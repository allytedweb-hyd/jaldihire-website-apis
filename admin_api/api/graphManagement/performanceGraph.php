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


include "../../includes/db-connection/db.php";

include "../lib/jwt.php";

// Accesing the Headers

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);


if ($token) {

    // Validate the Token

    $response = verifyJWT($token);

    if ($response['status']) {

        // Accessing Params

        @$empoyerId = $_GET['empoyerId'];
        @$technology = $_GET['technology'];
        @$skill = $_GET['skill'];
        @$dateType = $_GET['dateType'];


        // Checking the Inputs

        if (empty($empoyerId) || empty($technology) || empty($skill) || empty($dateType)) {
            $status = ["status" => false, "message" => "Invalid Params"];
            exit(json_encode($status));
        }

        $today = date('Y-m-d');
        $count = 10;
        $format = [];
        while ($count >= 0) {
            if ($dateType == 'day') {
                array_push($format, [
                    'date' => date('Y-m-d', strtotime(" -$count $dateType ")),
                    'dateFrom' => date('Y-m-d', strtotime(" -$count $dateType ")) . " 00:00:00",
                    'dateTo' => date('Y-m-d', strtotime(" -$count $dateType ")) . " 23:59:59"
                ]);
            } else if ($dateType == 'month') {
                array_push($format, [
                    'date' => date('Y-m-d', strtotime(" -$count $dateType ")),
                    'dateFrom' => date('Y-m-01', strtotime(" -$count $dateType ")) . " 00:00:00",
                    'dateTo' => date('Y-m-31', strtotime(" -$count $dateType ")) . " 23:59:59"
                ]);
            } else if ($dateType == 'year') {
                array_push($format, [
                    'date' => date('Y-m-d', strtotime(" -$count $dateType ")),
                    'dateFrom' => date('Y-01-01', strtotime(" -$count $dateType ")) . " 00:00:00",
                    'dateTo' => date('Y-12-31', strtotime(" -$count $dateType ")) . " 23:59:59"
                ]);
            }
            $count--;
        }

        // echo json_encode($format);
        // exit;

        $selected = [];
        $rejected = [];
        $total = [];
        $datesVal = [];
        foreach ($format as $index => $dates) {
            $totalJobs = "SELECT SUM(no_of_positions) as total FROM tbl_post_a_job WHERE created_date BETWEEN '{$dates['dateFrom']}' AND '{$dates['dateTo']}' AND employer_id = '{$empoyerId}'AND technology = '{$technology}' AND primary_skill = '{$skill}'";

            // echo $totalJobs;
            // exit;

            $otherStatus = "SELECT SUM(TABLE1.status=6) as selected,SUM(TABLE1.status=4) as rejected  FROM applicant_scheduled TABLE1, tbl_post_a_job TABLE2 WHERE TABLE1.job_id = TABLE2.auto_create_jobid AND TABLE2.employer_id = '{$empoyerId}'AND TABLE2.technology = '{$technology}' AND TABLE2.primary_skill = '{$skill}' AND TABLE1.updated_on BETWEEN '{$dates['dateFrom']}' AND '{$dates['dateTo']}'";


            $totaljobsQuery = mysqli_fetch_assoc(mysqli_query($con, $totalJobs));
            $otherjobsQuery = mysqli_fetch_assoc(mysqli_query($con, $otherStatus));
            array_push($total, intval($totaljobsQuery['total']));
            array_push($datesVal, $dates['date']);
            array_push($rejected, $otherjobsQuery['rejected'] == null ? 0 : intval($otherjobsQuery['rejected']));
            array_push($selected, $otherjobsQuery['selected'] == null ? 0 : intval($otherjobsQuery['selected']));
            // echo json_encode($totaljobsQuery);
            // echo json_encode($otherjobsQuery);
        }

        $status = [
            "status" => true,
            "selected" => $selected,
            "rejected" => $rejected,
            "total" => $total,
            "dates" => $datesVal,
        ];
        echo json_encode($status);
    } else {
        $status = ["status" => false, "message" => "Invalid User or Un Authorized"];
        echo json_encode($status);
    }
} else {
    $status = ["status" => false, "message" => "Unauthorized Access"];
    echo json_encode($status);
}
