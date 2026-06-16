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

// @$uId = $_GET['id'];
// $userExp = [];
// $uskill = [];

// if (!empty($uId)) {
//     $getData = mysqli_query($con, "select * , NULL as password from tbl_applicant_add where applicant_auto_create_id='" . $uId . "' and status=0");
//     $getExp = mysqli_query($con, "select * from applicant_experiences where applicant_auto_create_id='" . $uId . "' and status=0");
//     $getSkill = mysqli_query($con, "select * from applicant_skill  where applicant_auto_create_id='" . $uId . "' and status=0");
//     while ($uData = mysqli_fetch_assoc($getData)) {
//         $userData = $uData;
//     }
//     while ($uexp = mysqli_fetch_assoc($getExp)) {
//         $userExp[] = $uexp;
//     }
//     while ($uskill = mysqli_fetch_assoc($getSkill)) {
//         $userSkill[] = $uskill;
//     }

//     $userData['experience'] = $userExp;
//     $userData['skill'] = $userSkill;
//     $data = ['status' => true, 'data' => $userData];
//     // echo json_encode($data);
//     // exit();
// }

// else{
//     $data = ['status' => false,'error' => 'No Data Found'];
// }
// echo json_encode($data);
@$experienceFrom = $_GET['experienceFrom'];
@$experienceTo = $_GET['experienceTo'];
@$skill = $_GET['skill'];
@$avaliability = $_GET['avaliability'];
@$location = $_GET['location'];
$limitClause = '';
$whereClause = '';

if (!empty($experienceFrom)) {
    $whereClause .= "&&total_expereience >= '" . $experienceFrom . "'";
}
if (!empty($experienceTo)) {
    $whereClause .= "&&total_expereience <= '" . $experienceTo . "'";
}
if (!empty($skill)) {
    $whereClause .= "&&main_skill LIKE '%" . $skill . "%'";
}
if (!empty($location)) {
    $whereClause .= "&&preferred_location LIKE '%" . $location . "%'";
}
if (!empty($avaliability)) {
    $whereClause .= "&&notice_period <= '" . $avaliability . "' AND notice_period != '' ";
}

@$limit = $_GET['limit'];
@$skip = $_GET['skip'];
@$offset = $skip * $limit;
@$limitClause = "LIMIT $limit OFFSET $offset";
@$uId = $_GET['id'];

if (!empty($uId)) {
    $getData = mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE applicant_auto_create_id='" . $uId . "' and status=1 $limitClause");

    $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE applicant_auto_create_id='" . $uId . "' and status=1"));

    $getExp = mysqli_query($con, "SELECT * FROM applicant_experiences WHERE applicant_auto_create_id='" . $uId . "' and status=1");

    $getSkill = mysqli_query($con, "SELECT DISTINCT pri_skill FROM applicant_skill  WHERE applicant_auto_create_id='" . $uId . "' and status=1 ");

    while ($uData = mysqli_fetch_array($getData)) {
        $userData = $uData;
    }
    while ($uexp = mysqli_fetch_array($getExp)) {
        $userExp[] = $uexp;
    }
    while ($uskill = mysqli_fetch_array($getSkill)) {
        $userSkill[] = $uskill;
    }
    $data = ['status' => true, 'data' => $userData, "experience" => $userExp, "skill" => $userSkill, 'count' => $count];
    echo json_encode($data);
    exit();
}

if (isset($_GET['recruiter_id']) && !empty($_GET['recruiter_id'])) {
    @$recruiterId = $_GET['recruiter_id'];
    $data = [];
    $recApplicants = mysqli_query($con, "SELECT *,T3.member_name as RecruiterName FROM applicant_recruiter_assigns T1, tbl_applicant_add T2, tbl_allyted_team T3 WHERE T1.recruiter_assigned_id='" . $recruiterId . "' AND T2.applicant_id = T1.applicant_assigned_id AND T3.allyted_member_id ='" . $recruiterId . "' AND T1.status=1 AND T2.status=1 AND T3.status=1 $whereClause $limitClause");

    $count = mysqli_num_rows(mysqli_query($con, "SELECT *,T3.member_name as RecruiterName FROM applicant_recruiter_assigns T1, tbl_applicant_add T2, tbl_allyted_team T3 WHERE T1.recruiter_assigned_id='" . $recruiterId . "' AND T2.applicant_id = T1.applicant_assigned_id AND T3.allyted_member_id ='" . $recruiterId . "' AND T1.status=1 AND T2.status=1 AND T3.status=1 $whereClause"));

    while ($fet = mysqli_fetch_assoc($recApplicants)) {
        $data[] = $fet;
    }

    if ($data == null) {
        $status = ['status' => false, 'message' => 'No Applicants Found'];
    } else {
        $status = ['status' => true, 'data' => $data, 'count' => $count];
    }

    echo json_encode($status);
    exit();
}

if (isset($_GET['unAssigned']) && !empty($_GET['unAssigned'])) {
    $id = $_GET['unAssigned'];
    if ($id = 1) {
        $data = [];

        $query = "SELECT * FROM tbl_applicant_add WHERE applicant_id NOT IN(SELECT applicant_assigned_id FROM applicant_recruiter_assigns WHERE status=1) AND status=1 $whereClause $limitClause";

        $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE applicant_id NOT IN(SELECT applicant_assigned_id FROM applicant_recruiter_assigns WHERE status=1) AND status=1 $whereClause"));

        $getUnAssigned = mysqli_query($con, $query);

        while ($fet = mysqli_fetch_assoc($getUnAssigned)) {
            $data[] = $fet;
        }

        if ($data == null) {
            $status = ['status' => false, 'message' => 'No Applicants Found'];
        } else {
            $status = ['status' => true, 'data' => $data, 'count' => $count];
        }

        exit(json_encode($status));
    }
}

$data = [];
$applicantIds = [];

$query = "SELECT * FROM tbl_applicant_add WHERE applicant_id IN(SELECT applicant_assigned_id FROM applicant_recruiter_assigns WHERE status=1) AND status=1 $whereClause $limitClause";

$count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_applicant_add WHERE applicant_id IN(SELECT applicant_assigned_id FROM applicant_recruiter_assigns WHERE status=1) AND status=1 $whereClause"));

$get_applicants = mysqli_query($con, $query);

while ($fetchapplicants = mysqli_fetch_array($get_applicants)) {
    $data[] = $fetchapplicants;
    $applicantIds[] = $fetchapplicants['applicant_id'];
}

foreach ($applicantIds as $key => $value) {
    $getRecName = mysqli_query($con, "SELECT *,T2.member_name as RecruiterName FROM applicant_recruiter_assigns T1, tbl_allyted_team T2 WHERE T1.applicant_assigned_id='" . $value . "' AND T2.allyted_member_id = T1.recruiter_assigned_id AND T1.status=1 AND T2.status=1");

    while ($fet = mysqli_fetch_assoc($getRecName)) {
        $data[$key] += $fet;
    }
}

$status = ['status' => true, 'data' => $data, 'count' => $count];

echo json_encode($status);
