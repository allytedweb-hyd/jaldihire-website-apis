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
// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);
TokenVerify($token);

if (!empty($_GET['role']) && !empty($_GET['designation'])) {
    $role = $_GET['role'];
    $designation = $_GET['designation'];
    $data = [];
    $clause = '';
    $roleClause = '';

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $clause = "allyted_member_id != '" . $_GET['id'] . "' AND";
    }

    if ($_GET['role'] === 'Agent') {
        $roleClause = "role='Agent' AND";
    }

    $q = "SELECT * FROM tbl_allyted_team WHERE $roleClause designation='" . $designation . "' AND $clause status=1";

    $getReporting = mysqli_query($con, $q);

    while ($fet = $getReporting->fetch_assoc()) {
        $data[] = $fet;
    }

    if ($_GET['role'] === 'Agent') {
        $getAdmins = mysqli_query($con, "SELECT *,NULL as password, user_role as role FROM tbl_login WHERE user_role != 'Super Admin' ORDER BY login_id DESC");

        while ($fetch = mysqli_fetch_array($getAdmins)) {
            $data[] = $fetch;
        }
    }

    $data != null ? $status = ['status' => true, 'data' => $data] :
        $status = ['status' => false, 'message' => 'No Details Found'];
} else {
    $status = ['status' => false, 'message' => 'Invalid Params'];
}
echo json_encode($status);
