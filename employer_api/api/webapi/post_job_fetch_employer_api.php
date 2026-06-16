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

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

include "../../includes/db-connection/db.php";

$conditionClause = '';

@$id = $_GET['id'];
@$jobTitle = $_GET['jobTitle'];
@$location = $_GET['location'];
@$experience = $_GET['experience'];
// @$workMode = $_GET['workMode'];
@$skill = $_GET['skill'];
@$companyId = $_GET['employer_id'];
@$jobRole = $_GET['jobRole'];
@$package = $_GET['package'];
@$jobduration = $_GET['jobDuration'];
@$jobType = $_GET['jobType'];
@$applicant_exp = $_GET['app_experience'];
@$applicant_package = $_GET['app_package'];
@$job_status = $_GET['job_status'];


if (isset($id)) {
    $conditionClause .= "auto_create_jobid LIKE '%" . $id . "%' && ";
}
if (isset($jobTitle)) {
    $conditionClause .= "job_title LIKE '%" . $jobTitle . "%' && ";
}
if (isset($location)) {
    $conditionClause .= "job_location LIKE '%" . $location . "%' && ";
}
if (isset($experience)) {
    $conditionClause .= "experience LIKE '%" . $experience . "%' && ";
}
if (isset($skill)) {
    $conditionClause .= "primary_skill LIKE '%" . $skill . "%' && ";
}
if (isset($companyId)) {
    $conditionClause .= "company_Id = '" . $companyId . "' && ";
}
if (isset($jobRole)) {
    $conditionClause .= "role LIKE '%" . $jobRole . "%' && ";
}
if (isset($package)) {
    $price = explode("-", $package);
    $conditionClause .= "price BETWEEN " . $price[0] . " AND " . $price[1] . " && ";
}
if (isset($jobduration)) {
    $conditionClause .= "work_mode LIKE '%" . $jobduration . "%' && ";
}
if (isset($jobType)) {
    $conditionClause .= "job_type LIKE '%" . $jobType . "%' &&";
}
if (isset($applicant_exp)) {
    $exp = applicantExp($applicant_exp);
    $conditionClause .= "experience LIKE '%" . $exp . "%' && ";
}
if (isset($applicant_package)) {
    $conditionClause .= "price LIKE '%" . $applicant_package . "%' && ";
}
if (isset($job_status)) {
    $conditionClause .= "job_status LIKE '%" . $job_status . "%' && ";
}

if (isset($_GET['job_id'])) {
    $get_job_data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE id='" . $_GET['job_id'] . "' && status=1"));

    $getAgent = mysqli_fetch_assoc(mysqli_query($con, "SELECT agent_id FROM employer_agent_relation where auto_create_jobid='" . $get_job_data['auto_create_jobid'] . "' && status=1"));
    if ($getAgent != null) {
        $get_job_data += $getAgent;
    }

    $getAssignAppliCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as Applicantscount FROM assigned_jobs_list WHERE assigned_job_id='" . $get_job_data['auto_create_jobid'] . "' AND status=1"));

    $get_job_data += $getAssignAppliCount;

    if ($get_job_data != null) {
        $data = ["status" => true, "data" => $get_job_data];
    } else {
        $data = ["status" => false, "message" => "No Data Found"];
    }
    echo json_encode($data);
    exit();
}

if (isset($_GET['auto_create_id']) && !empty($_GET['auto_create_id'])) {
    $jobs = [];
    $getTeamJobs = mysqli_query($con, "SELECT * FROM applicant_scheduled T1,tbl_add_team T2,     tbl_post_a_job T3, tbl_applicant_add T4 WHERE T1.interviewer_id = '" . $_GET['auto_create_id'] . "' AND T2.auto_create_id = T1.interviewer_id AND BINARY T3.auto_create_jobid = T1.job_id AND T4.applicant_id = T1.applicant_id");

    while ($fetch = mysqli_fetch_assoc($getTeamJobs)) {
        $jobs[] = $fetch;
    }

    if ($jobs == null) {
        $status = ['status' => false, 'Message' => 'No Data Found'];
    } else {
        $status = ['status' => true, 'Message' => 'Fetched Succesfully', 'data' => $jobs];
    }

    echo json_encode($status);
    exit();
}

$limitClause = '';

if (isset($_GET['limit']) && !empty($_GET['limit'])) {
    $limit = 0;
    $limit = intval($_GET['limit']);
}
if (isset($_GET['skip']) && !empty($_GET['skip'])) {
    $skip = 0;
    $skip = intval($_GET['skip']);
}
@$offset = $limit * $skip;

if (isset($_GET['limit']) && isset($_GET['skip'])) {
    $limitClause = "LIMIT $limit OFFSET $offset";
}
$data = [];
$jobIds = [];
$jobAutoCreateIds = [];

$q = "SELECT * FROM tbl_post_a_job WHERE $conditionClause status=1 ORDER BY id DESC $limitClause";

// echo $q;
// exit();
$get_job_data = mysqli_query($con, $q);
$jobs_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_post_a_job WHERE $conditionClause status=1 ORDER BY id DESC"));

while ($fetch_job_data = mysqli_fetch_array($get_job_data)) {
    $data[] = $fetch_job_data;
    $jobIds[] = $fetch_job_data['id'];
    $jobAutoCreateIds[] = $fetch_job_data['auto_create_jobid'];
}


if (isset($companyId) && !empty($companyId)) {
    foreach ($jobAutoCreateIds as $key => $id) {
        $hiredCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE job_id='" . $id . "' && status=6"));

        $holdCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE job_id='" . $id . "' && status=3"));

        $rejCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM applicant_scheduled WHERE job_id='" . $id . "' && status=4"));

        // $counts = (object) [
        //     'hired' => $hiredCount,
        //     'hold' => $holdCount,
        //     'rejected' => $rejCount
        // ];
        $counts = [
            'hired' => $hiredCount,
            'hold' => $holdCount,
            'rejected' => $rejCount
        ];
        $data[$key] += $counts;
    }
}

// exit();
if (isset($_GET['appli_id']) && !empty($_GET['appli_id'])) {
    foreach ($jobIds as $key => $id) {
        $getSavedJobs = mysqli_fetch_assoc(mysqli_query($con, "SELECT status as Saved FROM tbl_saved_jobs WHERE job_id='" . $id . "' AND applicant_id='" . $_GET['appli_id'] . "' AND status=1"));

        if ($getSavedJobs != null) {
            $getSavedJobs['Saved'] = 1;
            $data[$key] += $getSavedJobs;
        } else {
            $getSavedJobs['Saved'] = 0;
            $data[$key] += $getSavedJobs;
        }
    }
}

if ($data == null) {
    $status = ['status' => false, 'Message' => 'No Data Found'];
} else {
    $status = ['status' => true, 'Message' => 'Fetched Succesfully', 'data' => $data, 'count' => $jobs_count];
}
echo json_encode($status);

function applicantExp($experience)
{
    if ($experience < 1) {
        return '<1';
    } elseif ($experience >= 1 && $experience <= 3) {
        return '1-3';
    } elseif ($experience >= 3 && $experience <= 5) {
        return '3-5';
    } elseif ($experience >= 5 && $experience <= 8) {
        return '5-8';
    } elseif ($experience >= 8 && $experience <= 11) {
        return '8-11';
    } elseif ($experience >= 11 && $experience <= 13) {
        return '11-13';
    } else {
        return '13+';
    }
}
