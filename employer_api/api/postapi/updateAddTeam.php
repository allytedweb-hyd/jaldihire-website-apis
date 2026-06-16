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

$folderPath = "uploads/recruiter-images/";

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($json) && !empty($json)) {

    $data = json_decode($json);
    $id = $data->id;
    $name = $data->name;
    $designation = $data->designation;
    $email = $data->email;
    $mobile = $data->mobile;
    $time_zone = $data->time_zone;

    $RoundsImplode = '';
    if (is_array($data->rounds)) {
        $RoundsImplode = implode(',', $data->rounds);
    } else {
        $RoundsImplode = $data->rounds;
    }
    $slotsImplode = '';

    if (isset($data->time_slots)) {
        if (is_array($data->time_slots)) {
            $slotsImplode = implode(',', $data->time_slots);
        } else {
            $slotsImplode = $data->time_slots;
        }
    }
    $timingsImplode = '';
    if (is_array($data->rounds)) {
        $timingsImplode = implode(',', $data->timings);
    } else {
        $timingsImplode = $data->timings;
    }
    // $interviewRounds = $data->selected_rounds;
    $companyId = $data->companyID;
    $whereClause = '';

    $folderPath = 'uploads/employer-images/';

    $fileProfile = '';
    if (!empty($data->image)) {
        $image_parts = explode(";base64,", $data->hi);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $fileProfile = uniqid() . '.png';
        $file = $folderPath . $fileProfile;
        $move = file_put_contents($file, $image_base64);
        $whereClause = ",image='" . $fileProfile . "'";
    }

    if (!empty($email)) {
        $getEmployer = mysqli_query($con, "SELECT * FROM tbl_add_employer WHERE emp_email='" . $email . "'");
    } else {
        echo json_encode(['status' => false, "message" => "Email Should Not Be Empty"]);
        exit();
    }

    // if (mysqli_num_rows($getEmployer) > 0) {
    //     echo json_encode(['status' => false, "message" => "Email Already Exists in Master employer"]);
    //     exit();
    // }


    if (!empty($email)) {
        $getaddteam = mysqli_query($con, "SELECT * FROM tbl_add_team WHERE email='" . $email . "'");
    } else {
        echo json_encode(['status' => false, "message" => "Email Should Not Be Empty"]);
        exit();
    }

    // if (count($interviewRounds) > 0) {
    //     for ($i = 0; $i < count($interviewRounds); $i++) {
    //         $item = $interviewRounds[$i];
    //         $RoundsImplode .= $item->item_text . ',';
    //     }
    // }

    if (!empty($email)) {
        $update = mysqli_query($con, "UPDATE tbl_add_team SET  name ='" . $name . "',designation='" . $designation . "',email='" . $email . "',mobile='" . $mobile . "',time_slots='" . $slotsImplode . "', time_zone='" . $time_zone . "',timings='" . $timingsImplode . "',interview_round='" . $RoundsImplode . "'$whereClause WHERE id='" . $id . "' && status='1'");

        $stat = ['status' => true, 'message' => 'Updated successfully'];
    } else {
        $stat = ['status' => false, 'message' => 'Failed! please try again'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Something went wrong! please try again'];
}
echo json_encode($stat);
