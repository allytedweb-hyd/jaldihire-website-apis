<?php
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type,Authorization');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../includes/db-connection/db.php";
include "../lib/jwt.php";

// Accesing the Headers

$headers = apache_request_headers();


// destructing the token
@$token = str_replace('Bearer ', '', $headers['Authorization']);

TokenVerify($token);


$deleteId =$_GET['dlt'];

        $get_team_data = mysqli_query($con,"UPDATE tbl_allyted_team SET status='0' WHERE allyted_member_id ='" . $deleteId . "'");
        if($get_team_data == true)
        {
          $stat = ['status'=>true, 'message' => 'Team Deleted Successfully', 'data'=>$get_team_data];
        }
        else{
          
          $stat = ['status'=>false,'message' => 'Failed to Delete'];
 
        }
        echo json_encode($stat);
   
?>