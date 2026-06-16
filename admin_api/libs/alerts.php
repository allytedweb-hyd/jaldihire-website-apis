<?php
function showAlert($status, $heading, $message)
{
  echo "<script>Swal.fire(
  '$heading',
  '$message',
  '$status'
)</script>";
}