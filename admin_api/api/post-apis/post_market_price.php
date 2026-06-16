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

date_default_timezone_set("Asia/Calcutta");
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

            // @$employerId = $data->employer;
            @$technology = $data->technology;
            @$skill = $data->skill;
            @$experience = $data->experience;
            @$price = $data->price;
            @$id = $data->id;
            // @$agent_id = $data->agent_id;

            if (isset($id) && !empty($id)) {
                try {
                    $update = mysqli_query($con, "UPDATE tbl_market_price SET technology='" . $technology . "',skill='" . $skill . "',experience='" . $experience . "',price='" . $price . "' WHERE id='" . $id . "' AND status=1 ");
                } catch (Exception $e) {
                    $stat = ['status' => false, "error" => $e];
                }

                $update ? $stat = ['status' => true, 'message' => 'updated successfully'] : $stat = ['status' => false, 'error' => $e];
                exit(json_encode($stat));
            }

            try {
                $postMarketPrice = mysqli_query($con, "INSERT INTO tbl_market_price SET technology='" . $technology . "',skill='" . $skill . "',experience='" . $experience . "',price='" . $price . "',status=1");

                if ($postMarketPrice) {
                    $stat = ['status' => true, "message" => 'Market Price Added Successfully'];
                } else {
                    $stat = ['status' => false, "message" => 'Failed Please Try Again!'];
                }
            } catch (Exception $e) {
                $stat = ["status" => false, "error" => $e];
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
