<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Purchase-Order";
$Page = "View-Purchase-Order";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Purchase Order List</title>
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
  $sql11 = "DELETE FROM tbl_purchase_order WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_purchase_order_products WHERE SellId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_general_ledger WHERE SellId = '$id' AND SellType='Purchase'";
  $conn->query($sql11);
  /* $sql11 = "DELETE FROM tbl_stocks WHERE SellId = '$id' AND SellType='Purchase'";
  $conn->query($sql11);*/
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-purchase-order.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
   
<h4 class="font-weight-bold py-3 mb-0">Installer </h4>


<div class="card" style="padding: 10px;">
     
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
      <thead>
            <tr>
               <th>Photo</th>
                <th>Contractor Name</th>
                <th>Installer Name</th>
                
                <th>Email Id</th>
                <th>Contact No</th>
                <th>Password</th>
                 <th>Roll</th>
                <th>Status</th>
                <th>Register Date</th>
               
               
            </tr>
        </thead>
        <tbody>
              <?php 
            $sql = "SELECT tu.*,tut.Name,tu2.Fname As ContractorName FROM tbl_users tu 
                    LEFT JOIN tbl_user_type tut ON tut.id=tu.Roll 
                    LEFT JOIN tbl_users tu2 ON tu2.id=tu.UnderInstallerId 
                    WHERE tu.Roll IN(34,35,36,37) ORDER BY tu.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td> <?php if($row["Photo"] == '') {?>
                  <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <img src="../uploads/<?php echo $row["Photo"];?>" class="d-block ui-w-40 rounded-circle" alt="" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle" style="width: 40px;height: 40px;"> 
             <?php } ?></td>
              <td><?php echo $row['ContractorName']; ?></td>
               <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
              
                
                <td><?php echo $row['EmailId']; ?></td>
               <td><?php echo $row['Phone']; ?></td>
                  <td><?php echo $row['Password']; ?></td>
              
                     <td><?php echo $row['Name'];?></td>
                 <td><?php if($row['Status']=='1'){echo "<span style='color:green;'>Approved</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>
              
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
              
            </tr>
           <?php } ?>
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
    $('#example').DataTable({
       "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
    });

 $(document).on("change", "#ModelNo", function(event) {
            var val = this.value;
            var action = "getModelNo";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#ProductNo').html(data);
                  
                }
            });

        });
    
});
</script>
</body>
</html>
