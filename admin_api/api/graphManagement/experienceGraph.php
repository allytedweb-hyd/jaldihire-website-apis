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

        @$applicantId = $_GET['applicantId'];


        // Checking the Inputs

        if (empty($applicantId)) {
            $status = ["status" => false, "message" => "Invalid Params"];
            exit(json_encode($status));
        }

        $get_all_experinces = "SELECT * FROM applicant_experiences WHERE applicant_id='{$applicantId}'";

        $result = mysqli_query($con, $get_all_experinces);

        $experiences = [];

        while ($each = mysqli_fetch_assoc($result)) {
            $experiences[] = $each;
        }

        $gradualIncrese = [];
        $companyChange = [];
        $companyNames = [];

        foreach ($experiences as $key => $value) {
            $obj = [
                "x" => $value['starting_date'],
                "y" => $value['starting_salary'],
                "name" => $value['exp_company'],
                "role" => $value['experience_role']
            ];
            array_push($gradualIncrese, $obj);
            if (!in_array($value['exp_company'], $companyNames)) {
                array_push($companyNames, $value['exp_company']);
                $obj2 = [
                    "x" => $value['starting_date'],
                    "y" => $value['starting_salary'],
                    "name" => $value['exp_company'],
                    "role" => $value['experience_role']
                ];
                array_push($companyChange, $obj2);
                $prev_sal = $obj2;
            } else {
                $prev_sal['x'] = $value['starting_date'];
                $prev_sal['role'] = $value['experience_role'];
                array_push($companyChange, $prev_sal);
            }
        }

        $status = [
            "status" => true,
            "gradualIncrese" => $gradualIncrese,
            "companyChange" => $companyChange,
        ];
        echo json_encode($status);


        // $status = ["status" => true, "experiences" => $experiences];
    } else {
        $status = ["status" => false, "message" => "Invalid User or Un Authorized"];
        echo json_encode($status);
    }
} else {
    $status = ["status" => false, "message" => "Unauthorized Access"];
    echo json_encode($status);
}
