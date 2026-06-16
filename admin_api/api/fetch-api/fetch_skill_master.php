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

// TokenVerify($token);

// if (!isset($headers['Authorization'])) {
//         $status = ['status' => false, 'message' => 'Unauthorized Access'];
//         echo json_encode($status);
//         exit();
// }

// @$token = str_replace('Bearer ', '', $headers['Authorization']);

// if ($token) {
//         Validating JWT Token
//         $reponse = verifyJWT($token);
//         if ($reponse['status']) {

if (isset($_GET['technologyId']) && !empty($_GET['technologyId'])) {
        $techId = $_GET['technologyId'];

        if (isset($_GET['skill_id']) && !empty($_GET['skill_id'])) {
                $skill_id = $_GET['skill_id'];
                $get_skill = mysqli_query($con, "SELECT *,id as skill_id FROM tbl_skill WHERE  technology_id='" . $techId . "' && id!='" . $skill_id . "' && status=1 ORDER BY skill_name ASC");
                $data = [];
                while ($fetchskill = mysqli_fetch_array($get_skill)) {
                        $data[] = $fetchskill;
                }
                echo json_encode($data);
                exit;
        }
        $get_skill = mysqli_query($con, "SELECT *,id as skill_id FROM tbl_skill WHERE  technology_id='" . $techId . "' && status=1 ORDER BY skill_name ASC");
        $data = [];
        while ($fetchskill = mysqli_fetch_array($get_skill)) {
                $data[] = $fetchskill;
        }
        echo json_encode($data);
        exit;
}

$get_skill = mysqli_query($con, "SELECT *,T1.id as skill_id FROM tbl_skill T1, tbl_technology T2 WHERE T2.id=T1.technology_id && T1.status=1 && T2.status=1 ORDER BY skill_name ASC");
$data = [];
while ($fetchskill = mysqli_fetch_array($get_skill)) {
        $data[] = $fetchskill;
}
echo json_encode($data);
exit;
//         } else {
//                 $status = ['status' => false, 'message' => 'User Deactivated or Invalid'];
//         }
// } else {
//         $status = ['status' => false, 'message' => 'Unauthorized Access'];
// }
// echo json_encode($status);