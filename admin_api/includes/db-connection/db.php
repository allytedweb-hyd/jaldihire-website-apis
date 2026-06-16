<?php


// production connection
// $con = mysqli_connect(hostname: 'localhost', 'u237107965_jaldihire_db', 'Jaldihire@123', 'u237107965_jaldihire_db');

// production remote connection
//$con = mysqli_connect('217.21.87.154', 'u237107965_jaldihire_db', 'Jaldihire@123', 'u237107965_jaldihire_db');

// production test connection
// $con = mysqli_connect('localhost', 'u237107965_test_jaldihire', 'Test@123$#', 'u237107965_test_jaldihire');

// production test remote connection
// $con = mysqli_connect('217.21.87.154', 'u237107965_test_jaldihire', 'Test@123$#', 'u237107965_test_jaldihire');


// jaldihire.com domain connection
 $con = mysqli_connect('localhost', 'jaldihiredb', 'jdData@321$#', 'jaldihiredb');

// jaldihire.com remote connection
// $con = mysqli_connect('217.21.87.103', 'u237107965_jaldihire', 'Jaldihire@321$#', 'u237107965_jaldihire');

// if($con == true){
//      echo"<script>alert('DB successfully')</script>";
// }
// else{ 
//  echo"<script>alert('DB unsuccessfully')</script>";
// }