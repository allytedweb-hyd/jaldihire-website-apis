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

if (isset($_GET['job_id']) && !empty($_GET['job_id'])) {
    $jobId = $_GET['job_id'];
    @$roundClause = '';
    $data = [];
    $Assignedrounds = [];
    // ? to get project Name of job and fetch interviewers from that job
    $getProjectName = mysqli_fetch_assoc(mysqli_query($con, "SELECT project_name FROM tbl_post_a_job WHERE auto_create_jobid='" . $jobId . "'"));

    // ? to get employerId From project Name
    $fetchDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_employer_project_name WHERE project_name='" . @$getProjectName['project_name'] . "' AND status=1"));


    // ?  clause to get interviewers by round 
    // if (isset($_GET['roundName']) && !empty($_GET['roundName'])) {
    // $roundClause = "interview_round LIKE'%" . $_GET['roundName'] . "%' &&";
    // $roundClause = "CONTAINS(interview_round, '" . $_GET['roundName'] . "') &&";
    //     $roundClause = "interview_round IN ('" . $_GET['roundName'] . "') &&";
    // }
    if (isset($_GET['roundName']) && !empty($_GET['roundName'])) {
        $roundClause = "FIND_IN_SET('" . mysqli_real_escape_string($con, $_GET['roundName']) . "', interview_round) > 0 &&";
    }


    // ? final query to get interviewers data by emp id and roundClause
    // $q = "SELECT *,NULL as pasword FROM tbl_add_team WHERE $roundClause employer_id='" . @$fetchDetails['employer_id'] . "' && status=1 $limitClause";
    $q = "SELECT *, NULL as pasword FROM tbl_add_team WHERE $roundClause employer_id='" . mysqli_real_escape_string($con, @$fetchDetails['employer_id']) . "' && status=1 $limitClause";


    // echo $q;
    // exit();

    // $getInterviewers = mysqli_query($con, $q);

    // $count = mysqli_num_rows(mysqli_query($con, "SELECT *,NULL as pasword FROM tbl_add_team WHERE $roundClause employer_id='" . @$fetchDetails['employer_id'] . "' && status=1"));
    // $data[] = $getInterviewers;
    // $stat = ['status' => true, 'data' => $data, 'count' => $count];
    // echo json_encode($stat);
    // exit();
    $getInterviewers = mysqli_query($con, $q);

    $count = mysqli_num_rows(mysqli_query($con, "SELECT *, NULL as pasword FROM tbl_add_team WHERE $roundClause employer_id='" . mysqli_real_escape_string($con, @$fetchDetails['employer_id']) . "' && status=1"));
    // $data[] = $getInterviewers;
    // $stat = ['status' => true, 'data' => $data, 'count' => $count];
    // echo json_encode($stat);
    // exit();
    while ($fet = mysqli_fetch_assoc($getInterviewers)) {

        $roundNames = [];
        $Assignedrounds = explode(',', $fet['interview_round']);

        foreach ($Assignedrounds as $round) {
            $q = "SELECT round_title FROM tbl_interview_rounds WHERE id='" . $round . "' AND status=1";
            @$round = mysqli_fetch_assoc(mysqli_query($con, $q));
            if ($round != null) {
                $roundNames[] = @$round['round_title'];
            }
        }
        $fet['roundName'] = implode(',', $roundNames);
        $data[] = $fet;
    }

    ($data != null) ? $stat = ['status' => true, 'data' => $data, 'count' => $count] :
        $stat = ['status' => false, 'message' => 'No Interviewers Found'];
} else {
    $stat = ['status' => false, 'message' => 'Invalid Params'];
}

echo json_encode($stat);
