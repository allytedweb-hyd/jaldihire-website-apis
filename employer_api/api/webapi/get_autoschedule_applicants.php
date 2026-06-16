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

if (isset($_GET['employer_id']) && !empty($_GET['employer_id'])) {
    $checkfirstround = $_GET['round'];
    $checkroundnumber = mysqli_fetch_assoc(mysqli_query($con, 'SELECT * FROM tbl_interview_rounds WHERE id="' . $checkfirstround . '" AND status=1'));
    $roundnumber = $checkroundnumber['round_number'];
    if ($roundnumber != 1) {
        if (isset($_GET['time_slot']) && !empty($_GET['time_slot'])) {
            $timeslot = $_GET['time_slot']; // Example: "06:00-06:30"
            $checkfirstround = $_GET['round'];
            if ((isset($_GET['time_slot']) && !empty($_GET['time_slot']))) {
                $project_name = $_GET['project'];
                $data = [];

                // Fetch employer details for the project
                $fetchDetails = mysqli_fetch_assoc(mysqli_query(
                    $con,
                    "SELECT * FROM tbl_employer_project_name WHERE project_name='" . mysqli_real_escape_string($con, $project_name) . "' AND status=1"
                ));

                // Check if employer details were found
                if ($fetchDetails) {
                    // Extract the start time from the given timeslot
                    list($timeslot_start, $timeslot_end) = explode('-', $timeslot);

                    // Query to get interviewers for the specified project and matching timeslot
                    $q = "
                        SELECT *, NULL AS password 
                        FROM tbl_add_team 
                        WHERE employer_id='" . mysqli_real_escape_string($con, $fetchDetails['employer_id']) . "' 
                          AND FIND_IN_SET('" . mysqli_real_escape_string($con, $timeslot_start) . "', timings) > 0 
                        AND FIND_IN_SET('" . mysqli_real_escape_string($con, $checkfirstround) . "', interview_round) > 0
                          AND status=1
                    ";

                    $getInterviewers = mysqli_query($con, $q);
                    $interviewers = [];

                    while ($fet = mysqli_fetch_assoc($getInterviewers)) {
                        $interviewers[] = $fet;
                    }
                }
            }
            if (isset($_GET['skills']) && !empty($_GET['skills'])) {
                $skill = $_GET['skills'];
                $locations = explode(',', $_GET['preffered_location']);
                $experience = $_GET['experience'];

                // Building location query
                $location_query = '';
                foreach ($locations as $location) {
                    $location = trim($location);
                    if (!empty($location_query)) {
                        $location_query .= ' OR ';
                    }
                    $location_query .= 'preferred_location LIKE "%' . $location . '%"';
                }

                // Building experience query
                $experience_query = '';
                if (strpos($experience, '-') !== false) {
                    // Range format, e.g., "3-5"
                    list($min_exp, $max_exp) = explode('-', $experience);
                    $experience_query = 'total_expereience >= ' . (float)$min_exp . ' AND total_expereience <= ' . (float)$max_exp;
                } elseif (strpos($experience, '<') === 0) {
                    // Less than format, e.g., "<1"
                    $max_exp = substr($experience, 1);
                    $experience_query = 'total_expereience < ' . (float)$max_exp;
                } else {
                    // Exact match, e.g., "3"
                    $experience_query = 'total_expereience = ' . (float)$experience;
                }

                // Extract the start time from the given time slot (e.g., "06:00-06:30" => "06:00")
                $timeslot = $_GET['time_slot']; // Assuming you receive this from the GET request
                list($timeslot_start, $timeslot_end) = explode('-', $timeslot);

                // Query to get applicants who have the same main skill, matching experience, locations, and time
                // $query = 'SELECT * FROM tbl_applicant_add 
                //           WHERE main_skill = "' . $skill . '" 
                //           AND (' . $experience_query . ') 
                //           AND (' . $location_query . ') 
                //           AND FIND_IN_SET("' . $timeslot_start . '", timings) > 0 
                //           AND status = 1';
                $query = 'SELECT * FROM tbl_applicant_add 
          WHERE main_skill = "' . $skill . '" 
          AND (' . $experience_query . ') 
          AND (' . $location_query . ') 
          AND FIND_IN_SET("' . $timeslot_start . '", timings) > 0 
          AND status = 1
          AND created_by_id IS NOT NULL 
          AND created_by_id != ""';


                $getapplicants = mysqli_query($con, $query);
                $applicants = [];

                $scheduled_applicants = [];
                $unscheduled_applicants = [];
                $jobId = $_GET['Job_id'];
                $round = $_GET['round'];

                while ($applicant = mysqli_fetch_assoc($getapplicants)) {
                    $applicant_id = $applicant['applicant_id'];
                    $checkScheduledQuery = 'SELECT round, current_round_status 
                                            FROM applicant_scheduled 
                                            WHERE applicant_id = "' . $applicant_id . '" 
                                            AND job_id = "' . $jobId . '"';
                    $checkScheduled = mysqli_query($con, $checkScheduledQuery);

                    // If the applicant is found in applicant_scheduled table
                    if ($checkScheduled && mysqli_num_rows($checkScheduled) > 0) {
                        $scheduledData = mysqli_fetch_assoc($checkScheduled);

                        // Check if current_round_status is 1
                        if ((int)$scheduledData['current_round_status'] === 1) {
                            $currentround = (int)$scheduledData['round'];
                            $round = (int)$_GET['round'];

                            // Only add to scheduled_applicants if the round condition is satisfied
                            if ($round > $currentround) {
                                $scheduled_applicants[] = $applicant;
                            }
                        }

                        // Else: Do nothing, ignore the applicant entirely
                    } else {
                        // If the applicant is not found in the table at all
                        $unscheduled_applicants[] = $applicant;
                    }
                }



                // Combine scheduled and unscheduled applicants
                $applicants = array_merge($scheduled_applicants, $unscheduled_applicants);
            }
        }
    } elseif (($roundnumber == 1) && (isset($_GET['time_slot']) && !empty($_GET['time_slot']))) {
        $timeslot = $_GET['time_slot']; // Example: "06:00-06:30"

        if ((isset($_GET['time_slot']) && !empty($_GET['time_slot']))) {
            $project_name = $_GET['project'];
            $data = [];

            // Fetch employer details for the project
            $fetchDetails = mysqli_fetch_assoc(mysqli_query(
                $con,
                "SELECT * FROM tbl_employer_project_name WHERE project_name='" . mysqli_real_escape_string($con, $project_name) . "' AND status=1"
            ));

            // Check if employer details were found
            if ($fetchDetails) {
                // Extract the start time from the given timeslot
                list($timeslot_start, $timeslot_end) = explode('-', $timeslot);
                $checkfirstround = $_GET['round'];

                // Query to get interviewers for the specified project and matching timeslot
                $q = "
                    SELECT *, NULL AS password 
                    FROM tbl_add_team 
                    WHERE employer_id='" . mysqli_real_escape_string($con, $fetchDetails['employer_id']) . "' 
                      AND FIND_IN_SET('" . mysqli_real_escape_string($con, $timeslot_start) . "', timings) > 0 
                                              AND FIND_IN_SET('" . mysqli_real_escape_string($con, $checkfirstround) . "', interview_round) > 0

                      AND status=1
                ";

                $getInterviewers = mysqli_query($con, $q);
                $interviewers = [];

                while ($fet = mysqli_fetch_assoc($getInterviewers)) {
                    $interviewers[] = $fet;
                }
            }
        }
        if (isset($_GET['skills']) && !empty($_GET['skills'])) {
            $skill = $_GET['skills'];
            $locations = explode(',', $_GET['preffered_location']);
            $experience = $_GET['experience'];

            // Building location query
            $location_query = '';
            foreach ($locations as $location) {
                $location = trim($location);
                if (!empty($location_query)) {
                    $location_query .= ' OR ';
                }
                $location_query .= 'preferred_location LIKE "%' . $location . '%"';
            }

            // Building experience query
            $experience_query = '';
            if (strpos($experience, '-') !== false) {
                // Range format, e.g., "3-5"
                list($min_exp, $max_exp) = explode('-', $experience);
                $experience_query = 'total_expereience >= ' . (float)$min_exp . ' AND total_expereience <= ' . (float)$max_exp;
            } elseif (strpos($experience, '<') === 0) {
                // Less than format, e.g., "<1"
                $max_exp = substr($experience, 1);
                $experience_query = 'total_expereience < ' . (float)$max_exp;
            } else {
                // Exact match, e.g., "3"
                $experience_query = 'total_expereience = ' . (float)$experience;
            }

            // Extract the start time from the given time slot (e.g., "06:00-06:30" => "06:00")
            $timeslot = $_GET['time_slot']; // Assuming you receive this from the GET request
            list($timeslot_start, $timeslot_end) = explode('-', $timeslot);

            // Query to get applicants who have the same main skill, matching experience, locations, and time
            $query = 'SELECT * FROM tbl_applicant_add 
            WHERE main_skill = "' . $skill . '" 
            AND (' . $experience_query . ') 
            AND (' . $location_query . ') 
            AND FIND_IN_SET("' . $timeslot_start . '", timings) > 0 
            AND status = 1
            AND created_by_id IS NOT NULL 
            AND created_by_id != ""';


            $getapplicants = mysqli_query($con, $query);
            $applicants = [];
            while ($applicant = mysqli_fetch_assoc($getapplicants)) {
                $applicant_id = $applicant['applicant_id']; // Assuming 'id' is the applicant's unique ID
                $jobId = $_GET['Job_id']; // Assuming job ID is passed in the GET request

                // Check if the applicant is already scheduled
                $checkScheduledQuery = 'SELECT * FROM applicant_scheduled 
                                        WHERE applicant_id = "' . $applicant_id . '" 
                                        AND job_id = "' . $jobId . '"';
                $checkScheduled = mysqli_query($con, $checkScheduledQuery);

                if (mysqli_num_rows($checkScheduled) == 0) {
                    // If not scheduled, add to the applicants array
                    $applicants[] = $applicant;
                }
            }
        }
    } else {
        if ($roundnumber == 1) {
            if (isset($_GET['project']) && !empty($_GET['project'])) {
                $project_name = $_GET['project'];
                $data = [];
                $fetchDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tbl_employer_project_name WHERE project_name='" . $project_name . "' AND status=1"));

                $checkfirstround = $_GET['round'];

                $q = "SELECT *, NULL as pasword FROM tbl_add_team WHERE employer_id='" . mysqli_real_escape_string($con, string: @$fetchDetails['employer_id']) . "'  AND FIND_IN_SET('" . mysqli_real_escape_string($con, $checkfirstround) . "', interview_round) > 0 && status=1 ";

                $getInterviewers = mysqli_query($con, $q);
                $interviewers = [];
                while ($fet = mysqli_fetch_assoc($getInterviewers)) {
                    $interviewers[] = $fet;
                }
            }

            if (isset($_GET['skills']) && !empty($_GET['skills'])) {
                $skill = $_GET['skills'];
                $locations = explode(',', $_GET['preffered_location']);
                $experience = $_GET['experience'];

                // Building location query
                $location_query = '';
                foreach ($locations as $location) {
                    $location = trim($location);
                    if (!empty($location_query)) {
                        $location_query .= ' OR ';
                    }
                    $location_query .= 'preferred_location LIKE "%' . $location . '%"';
                }

                // Building experience query
                $experience_query = '';
                if (strpos($experience, '-') !== false) {
                    // Range format, e.g., "3-5"
                    list($min_exp, $max_exp) = explode('-', $experience);
                    $experience_query = 'total_expereience >= ' . (float)$min_exp . ' AND total_expereience <= ' . (float)$max_exp;
                } elseif (strpos($experience, '<') === 0) {
                    // Less than format, e.g., "<1"
                    $max_exp = substr($experience, 1);
                    $experience_query = 'total_expereience < ' . (float)$max_exp;
                } else {
                    // Exact match, e.g., "3"
                    $experience_query = 'total_expereience = ' . (float)$experience;
                }

                $query = 'SELECT * FROM tbl_applicant_add 
                          WHERE main_skill = "' . $skill . '" 
                          AND (' . $experience_query . ') 
                          AND (' . $location_query . ') 
                          AND status = 1
                           AND created_by_id IS NOT NULL 
          AND created_by_id != ""';

                $getapplicants = mysqli_query($con, $query);
                $applicants = [];

                while ($applicant = mysqli_fetch_assoc($getapplicants)) {
                    $applicant_id = $applicant['applicant_id']; // Assuming 'id' is the applicant's unique ID
                    $jobId = $_GET['Job_id']; // Assuming job ID is passed in the GET request

                    // Check if the applicant is already scheduled
                    $checkScheduledQuery = 'SELECT * FROM applicant_scheduled 
                                            WHERE applicant_id = "' . $applicant_id . '" 
                                            AND job_id = "' . $jobId . '"';
                    $checkScheduled = mysqli_query($con, $checkScheduledQuery);

                    if (mysqli_num_rows($checkScheduled) == 0) {
                        // If not scheduled, add to the applicants array
                        $applicants[] = $applicant;
                    }
                }
            }
        }
    }




    // function convertToUTC($time, $timezone)
    // {
    //     // Check if timezone is valid and not empty
    //     if (empty($timezone)) {
    //         // You may set a default timezone, e.g., UTC or return the original time if preferred
    //         $timezone = 'UTC';
    //     }

    //     try {
    //         $date = new DateTime($time, new DateTimeZone($timezone));
    //         $date->setTimezone(new DateTimeZone('UTC'));
    //         return $date->format('H:i');
    //     } catch (Exception $e) {
    //         // Handle invalid timezone, return null or error message if needed
    //         return null; // Or return $time if you'd prefer to keep original time on failure
    //     }
    // }


    // $matchedApplicants = [];
    // $matchedInterviewers = [];


    // foreach ($interviewers as $interviewer) {
    //     $interviewerTimeZone = $interviewer['time_zone'];
    //     $interviewerTimes = explode(',', $interviewer['timings']);
    //     $interviewerTimesUTC = array_map(function ($time) use ($interviewerTimeZone) {
    //         return convertToUTC($time, $interviewerTimeZone);
    //     }, $interviewerTimes);

    //     foreach ($applicants as $applicant) {
    //         // Skip if applicant is already matched
    //         if (in_array($applicant, $matchedApplicants)) {
    //             continue;
    //         }

    //         $applicantTimeZone = $applicant['time_zone'];
    //         $applicantTimes = explode(',', $applicant['timings']);
    //         $applicantTimesUTC = array_map(function ($time) use ($applicantTimeZone) {
    //             return convertToUTC($time, $applicantTimeZone);
    //         }, $applicantTimes);

    //         // Check if applicant has any matching time with interviewer
    //         $commonTimes = array_intersect($interviewerTimesUTC, $applicantTimesUTC);

    //         if (!empty($commonTimes)) {
    //             $matchedApplicants[] = $applicant;
    //         }
    //     }
    // }

    // // Step 2: Check if each matched applicant has at least one timing match with interviewer
    // foreach ($matchedApplicants as $applicant) {
    //     $applicantTimeZone = $applicant['time_zone'];
    //     $applicantTimes = explode(',', $applicant['timings']);
    //     $applicantTimesUTC = array_map(function ($time) use ($applicantTimeZone) {
    //         return convertToUTC($time, $applicantTimeZone);
    //     }, $applicantTimes);

    //     foreach ($interviewers as $interviewer) {
    //         $interviewerTimeZone = $interviewer['time_zone'];
    //         $interviewerTimes = explode(',', $interviewer['timings']);
    //         $interviewerTimesUTC = array_map(function ($time) use ($interviewerTimeZone) {
    //             return convertToUTC($time, $interviewerTimeZone);
    //         }, $interviewerTimes);

    //         // Check if interviewer has any matching time with applicant
    //         $commonTimes = array_intersect($applicantTimesUTC, $interviewerTimesUTC);

    //         if (!empty($commonTimes) && !in_array($interviewer, $matchedInterviewers, true)) {
    //             $matchedInterviewers[] = $interviewer;
    //         }
    //     }
    // }

    // Return matched applicants and interviewers in separate arrays
    if (!empty($applicants) && !empty($interviewers)) {
        $stat = [
            'status' => true,
            'matched_applicants' => $applicants,
            'matched_interviewers' => $interviewers
        ];
    } else {
        $stat = ['status' => false, 'message' => 'No Matching Applicants or Interviewers Found'];
    }
} else {
    $stat = ['status' => false, 'message' => 'Invalid Params'];
}

echo json_encode($stat);
