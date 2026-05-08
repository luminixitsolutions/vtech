<?php
if(!isset($_SESSION['User'])){
  header('Location:login.php');
}
?>