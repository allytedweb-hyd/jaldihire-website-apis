<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin");
// include "includes/db.php";
include "../../includes/db-connection/db.php";


        

$deleteId =$_GET['dlt'];

$get_addteam_data = mysqli_query($con,"UPDATE tbl_add_team SET status='3' WHERE id ='" . $deleteId . "'");
if($get_addteam_data == true)
{
  $stat = ['status'=>true, 'message' => 'Team Deleted Successfully', 'data'=>$get_addteam_data];
}
else{
  
  $stat = ['status'=>true,'message' => 'Failed to Delete'];

}
echo json_encode($stat);
?>