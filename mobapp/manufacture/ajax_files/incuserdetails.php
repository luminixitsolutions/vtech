<?php 
$user_id = $_SESSION['User']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Options = explode(',',$row77['Options']);
?>