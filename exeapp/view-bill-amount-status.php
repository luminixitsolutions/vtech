<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Bill-Amount-Status";
$Page = "Bill-Amount-Status";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Bill-Amount-Status</title>
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
<h4 class="font-weight-bold py-3 mb-0">Bill Amount Status Lists
   
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
                <th>Customer Name</th> 
                <th>Contact No</th>
                <th>Address</th>
                <th>QTN NO</th>
                <th>QTN Date</th>
                <th>Paid Status</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Bal Amount</th>
                <th>Paid Date</th>
                <th>Paid By</th>
                <th>Pay</th>
                <th>Pay Balance</th>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.* FROM tbl_quotation tp 
            
            ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $BalAmt = $row["TotalAmt"]-$row["PaidAmt"];
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['CustName']; ?></td> 
              
                <td><?php echo $row['CellNo']; ?></td>
                <td><?php echo $row['Address']; ?></td>
                <td><?php echo $row['InvoiceNo']; ?></td>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
                <td><?php if($row['PaidStatus']=='1'){echo "<span style='color:green;'>Paid</span>";} else { echo "<span style='color:red;'>Not Paid</span>";} ?></td>
                <td>&#8377;<?php echo number_format($row["TotalAmt"],2); ?></td>
                <td>&#8377;<?php echo number_format($row["PaidAmt"],2); ?></td>
                <td>&#8377;<?php echo number_format($row["TotalAmt"]-$row["PaidAmt"],2); ?></td>
                <td><?php if($row['PaidStatus']=='1'){ echo date("d/m/Y", strtotime(str_replace('-', '/',$row['PaidDate']))); } ?></td>
                <td><?php echo $row['PayMode']; ?></td>
                 <?php if($BalAmt>0){?>
                <td>
        <?php if($row['PaidStatus']=='1'){?>
        <a href="pay-amount.php?qid=<?php echo $row['id']; ?>" class="btn btn-success btn-finish">Update</a>
        <?php } else {?>
                    <a href="pay-amount.php?qid=<?php echo $row['id']; ?>" class="btn btn-primary btn-finish">Pay</a>
                    <?php } ?>
                    </td>
                <td>
                    <?php if($row['PaidStatus']=='1'){?>
                    <a href="pay-bal-amount.php?qid=<?php echo $row['id']; ?>" class="btn btn-primary btn-finish">Pay Balance</a></td>
                <?php } else {} } else { ?>
                <td>
        
                    <a href="#" class="btn btn-success btn-finish" disabled>Paid</a>
                    </td>
                <td></td>
                <?php } ?>
        
              
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
