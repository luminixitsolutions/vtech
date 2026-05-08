<?php
session_start();
$sessionid = session_id();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'allocateLeads'){
    $allocateid = $_POST['allocateid'];
    $leadid = $_POST['leadid'];
    $query2 = "UPDATE tbl_leads SET AllocateId='$allocateid' WHERE id = '$leadid'";
  $conn->query($query2);
  echo 1;
}
?>