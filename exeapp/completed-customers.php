<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Delivery-Products";
$Page = "Completed-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Customer Account List</title>
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










<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Completed Customers
    
</h4>

<div class="card" style="padding: 10px;">

    

<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <!-- <th>Photo</th> -->
               <th>Invoice No</th>
               <th>Invoice Date</th>
                <th>Customer Name</th>
               <th>Contact No</th>
                <!-- <th>Email Id</th> -->
                <th>Taluka</th>
                 <!-- <th>User Type</th> -->

                <th>Status</th>
                 
                <th>Register Date</th>
              
             <th>Print</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            if($Roll == 1 || $Roll == 7){
            $sql = "SELECT tu.*,td.InvNo,td.InvoiceDate,td.id As deliverid FROM tbl_delivered_invoice td 
                    LEFT JOIN tbl_users tu ON td.CustId=tu.id WHERE tu.Roll=5";
        }
        else{
            $sql = "SELECT tu.*,td.InvNo,td.InvoiceDate,td.id As deliverid FROM tbl_delivered_invoice td 
                    LEFT JOIN tbl_users tu ON td.CustId=tu.id WHERE tu.Roll=5 AND tu.BranchId='$BranchId'";
        }
            if($_POST['UserType']){
                $UserType = $_POST['UserType'];
                if($UserType == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.UserType='$UserType'";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND tu.CreatedDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND tu.CreatedDate<='$ToDate'";
            }
            $sql.= " ORDER BY tu.id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
             ?>
            <tr>
               <!-- <td> <?php if($row["Photo"] == '') {?>
                  <img src="user_icon.jpg" class="d-block ui-w-100 rounded-circle"  style="width: 100px;height: 100px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <img src="../uploads/<?php echo $row["Photo"];?>" class="d-block ui-w-100 " alt="" style="width: 100px;height: 100px;">
                  <?php }  else{?>
                 <img src="user_icon.jpg" class="d-block ui-w-100 rounded-circle" style="width: 100px;height: 100px;"> 
             <?php } ?></td> -->
             <td><?php echo $row['InvNo']; ?>
              <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
                <td><a href="customer-profile.php?id=<?php echo $row['id']; ?>" target="_new"><?php echo $row['Fname']." ".$row['Lname']; ?></a></td>
              
              <td><?php echo $row['Phone']."<br>".$row['Phone2']; ?></td>
                <!-- <td><?php echo $row['EmailId']; ?></td> -->
              
                  <td><?php echo $row['Taluka']; ?></td>
              
                 
                 <td><?php if($row['Status']=='1'){echo "<span style='color:green;'>Approved</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>

               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
            
           <td><a href="print-delivery-customer-challan.php?id=<?php echo $row['deliverid']; ?>" target="_blank"><i class="lnr lnr-printer text-danger"></i></a></td>
              
            </tr>
           <?php }  ?>
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
});
</script>
</body>
</html>
