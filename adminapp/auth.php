<?php
if(!isset($_SESSION['User'])){
  echo "<script>window.location.href='login.php';</script>";
  //header('Location:login.php');
}
?>