<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Vendors";
$Page = "View-Vendors";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Vendors Account List</title>
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
  $sql11 = "DELETE FROM tbl_warranty_registration WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-warranty-registration.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Warranty Customers
   
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Customer Name</th>
                <th>Contact No</th>
                <th>Address</th>
                <th>Warranty Start Date</th>
                <th>End Date</th>
               
               
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $CurrDate=date('Y-m-d');
            $sql = "SELECT tw.*,tu.* FROM tbl_warranty_registration tw 
                    LEFT JOIN tbl_users tu ON tu.id=tw.CustId WHERE tu.Roll=5 AND tw.EndDate>='$CurrDate' ORDER BY tu.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td><?php echo $i;?></td>
               <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
              
                <td><?php echo $row['Phone']; ?></td>
             
                  <td><?php echo $row['Address']; ?></td>
              
                    
                 
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StartDate']))); ?></td>
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['EndDate']))); ?></td>
           
            </tr>
           <?php $i++;} ?>
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
<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable( {
       "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
      });
});
</script>
</body>
</html>
