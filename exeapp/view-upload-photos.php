<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Page";
$Page = "View-Upload-Photo";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Upload Pdf List</title>
 <!-- manifest meta -->
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

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
</head>
<body>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">








<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_photos WHERE id = '$id'";
  $conn->query($sql11);
 
  
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
       window.location.href="view-upload-photos.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Uploaded Photo List
  <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="upload-photos.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Upload Photo</a></span><?php } ?>
</h4>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
       <thead>
            <tr>
              <th>#</th>
               <th>Photo</th>
                <th>Url</th>
                <?php if(in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
            </tr>
        </thead>
<tbody>
<?php 
$i=1;
 $sql2 = "SELECT * FROM tbl_photos"; 

    $res2 = $conn->query($sql2);
    $row_cnt = mysqli_num_rows($res2);
    if($row_cnt > 0){
    while($row = $res2->fetch_assoc()){ ?>
<tr>
             <td><?php echo $i; ?></td>
           
              <td><?php if($row["Photo"] == '') {?>
                  <span style="color:red;">Photo Not Found!</span>
                <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>  
                <img src="../uploads/<?php echo $row['Photo']; ?>" style="width:100px;height:100px;">
                <?php } else{?>
                  <span style="color:red;">Photo Not Found!</span>
                <?php } ?></td>
             <td><?php echo $SiteUrl;?>/uploads/<?php echo $row['Photo']; ?></td>
              <?php if(in_array("11", $Options)) {?>
         
<td>
 <!--<a href="upload-photos.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;--><a onClick="return confirm('Are you sure you want delete this photo?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a>
</td>
<?php } ?>
</tr>
<?php $i++;}} ?>

</tbody>
    </table>
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

<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


<script type="text/javascript">
 
	$(document).ready(function() {
    $('#example').DataTable({
      
    });
});
</script>
</body>
</html>
