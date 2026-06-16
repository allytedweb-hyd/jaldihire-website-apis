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

        @$id = $_GET['applicant_id'];
        @$skills = $_GET['primary_skills'];
        @$location = $_GET['preffered_location'];

        // Checking the Inputs

        if (empty($id) || empty($skills) || empty($location)) {
            $status = ["status" => false, "message" => "Invalid Params"];
            exit(json_encode($status));
        }

        $skilsArr = explode(",", $skills);
        $skillsQueryArr = [];

        foreach ($skilsArr as $skill) {

            $skil = 'primary_skills ' . 'LIKE ' . "'%$skill%'";
            array_push($skillsQueryArr, $skil);
        }

        $finalSkilsQuery = implode(" OR ", $skillsQueryArr);

        $query = "SELECT * FROM `tbl_applicant_add` WHERE ($finalSkilsQuery) AND applicant_id != $id AND preferred_location LIKE '%{$location}%' AND status=1 ORDER by expected_package DESC LIMIT 5";

        $get_applicants = mysqli_query($con, $query);


        $data = [];

        while ($fetchapplskills = mysqli_fetch_assoc($get_applicants)) {
            $data[] = $fetchapplskills;
        }
        $status = ["status" => true, "message" => "Success", "data" => $data];
        echo json_encode($status);
    } else {
        $status = ["status" => false, "message" => "Invalid User or Un Authorized"];
        echo json_encode($status);
    }
} else {
    $status = ["status" => false, "message" => "Unauthorized Access"];
    echo json_encode($status);
}
