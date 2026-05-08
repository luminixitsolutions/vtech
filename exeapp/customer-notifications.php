<?php 
session_start();
include_once 'config.php';
require_once "database.php";
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Notifications";
$Page = "Customer-Notification";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Notifications</title>
 <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
</head>
<body>
<style type="text/css">
  .password-tog-info {
  display: inline-block;
cursor: pointer;
font-size: 12px;
font-weight: 600;
position: absolute;
right: 50px;
top: 30px;
text-transform: uppercase;
z-index: 2;
}


</style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">









<?php 
  if(isset($_POST['submit'])){
    $AccType = $_POST['AccType'];
    $AccName = $_POST['AccName'];
    $Title = addslashes(trim($_POST['Title']));
    $Message = addslashes(trim($_POST['Message']));
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');
    $sql73 = "SELECT Tokens,id FROM tbl_users WHERE Status='1' AND Tokens!=''";
    if($AccName == 'all'){
      $sql73.= "";
    }
    else{
        $sql73.= " AND id='$AccName'";
      }
      
    //echo $sql73;exit();
    $data=mysqli_query($con,$sql73);
        
        while($row=mysqli_fetch_array($data))
        {
            
             $ReceiverId = $row['id'];
             $sql = "INSERT INTO tbl_notifications SET SenderId='$user_id',ReceiverId='$ReceiverId',Title='$Title',Message='$Message',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
            $conn->query($sql);

            $title = $Title;
            $body =  $Message;
            $reg_id = $row[0];
            $registrationIds = array($reg_id);
            //$url = "$SiteUrl/profile.php?id=$UserId";
            include '../incnotification.php';
         
        }
        exit();
    ?>
    <script>
        alert("Notification Sent Successfully");
        window.location.href="customer-notifications.php";
    </script>
    <?php 
  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Customer Notifications</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">
<div class="form-group col-md-12">
<label class="form-label">Customer Name<span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="AccName" name="AccName" required>
    <option value="all" selected>All Customer</option>
      <optgroup label="Customer">
     <?php 
     $sql1 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=5";
     $row1 = getList($sql1);
     foreach($row1 as $result)
      {
      ?>
    <option  value="<?php echo $result['id']; ?>"><?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
 </optgroup>
 
 <optgroup label="Manufacturer">
     <?php 
     $sql1 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=3";
     $row1 = getList($sql1);
     foreach($row1 as $result)
      {
      ?>
    <option  value="<?php echo $result['id']; ?>"><?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
 </optgroup>
 
  <optgroup label="Dealer">
     <?php 
     $sql1 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=9";
     $row1 = getList($sql1);
     foreach($row1 as $result)
      {
      ?>
    <option  value="<?php echo $result['id']; ?>"><?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
 </optgroup>
 
</select>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-12">
<label class="form-label">Title <span class="text-danger">*</span></label>
<input type="text" name="Title" id="Title" class="form-control" placeholder="Title" value="" autocomplete="off" required>
</div>
<div class="form-group col-md-12">
  <label class="form-label">Notification <span class="text-danger">*</span></label>
  <textarea class="form-control" name="Message" required></textarea>
</div>

</div>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>
</div>
</div>
</div>

<?php include_once 'footer.php'; ?>
</div>
 </main>

    <!-- footer-->
    


    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
       <?php include_once 'footer_script.php'; ?>
<script type="text/javascript">
  function getMembers(val){
    var action = "getMembers";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#AccName').html(data);
    }
    });
  }
</script>
</body>
</html>
