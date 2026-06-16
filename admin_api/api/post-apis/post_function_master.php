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

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (!$authHeader) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized Access']);
    exit();
}

@$token = str_replace('Bearer ', '', $authHeader);



if ($token) {
    // Validating JWT Token
    $reponse = verifyJWT($token);
    if ($reponse['status']) {

        $json = file_get_contents('php://input');
        $data = json_decode($json);

        if (isset($json) && !empty($json)) {

            $data = json_decode($json);

            $functionName = $data->functionName;

            $fetchDesignation = mysqli_query($con, "SELECT * FROM tbl_function_master WHERE function_name='" . $functionName . "' && status=1");

            if (mysqli_num_rows($fetchDesignation) < 1) {
                $insert_function = mysqli_query($con, "INSERT INTO tbl_function_master SET function_name='" . $functionName . "',status=1");
                if ($insert_function == true) {
                    $stat = ['message' => 'Function Added Successfully ', 'status' => true];
                } else {
                    $stat = ['status' => false, 'message' => 'Failed!'];
                }
            } else {
                $stat = ['status' => false, 'message' => 'Function Already Exists!'];
            }
        } else {
            $stat = ['status' => false, 'message' => 'Something went wrong please try again'];
        }
    } else {
        $stat = ['status' => false, 'message' => 'User Deactivated or Invalid'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Unauthorized Access'];
}


echo json_encode($stat);
