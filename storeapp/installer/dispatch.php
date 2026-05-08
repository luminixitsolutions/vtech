<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['instuser']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
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
        

        <div class="main-container" style="background-color: #f1f1f1;">






<div class="container">
    <h4 class="font-weight-bold py-3 mb-0">Dispatch Orders 
    <!-- <span style="float: right;">
<a href="dispatch-order.php" class="btn btn-secondary btn-round">Dispatch</a></span> -->
</h4>
    <?php 
           
            $sql = "SELECT tdo.*,tu.Fname,tu.EmailId,tu.Phone,tu.Address,tu.ProjectType,tu.BeneficiaryId FROM tbl_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.DispatcherId='$user_id'";
            if($_REQUEST['val'] != ''){
                $sql.=" AND tdo.Inst_Dispatcher_Otp_Verify='".$_REQUEST['val']."'";
            }
            $sql.= " ORDER BY tdo.id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT (case when Dispatch='1' then sum(Qty) else '0' end) as dispatchqty,(case when Dispatch='0' then sum(Qty) else '0' end) as balqty FROM `tbl_sell_products` WHERE SellId='".$row['id']."'";
                $row2 = getRecord($sql2);
                $dispatchqty = $row2['dispatchqty'];
                $balqty = $row2['balqty'];
                $totalqty = $dispatchqty+$balqty;
             ?>
<div class="card mb-4">

                    <div class="card-body">
                        <h6 style="margin-bottom: 1px;font-size: 15px;"><?php echo $row['Fname']; ?></h6>
                        <p style="margin-bottom: 1px;"><strong>(<?php echo $row['BeneficiaryId']; ?>)</strong></p>
                        <p style="margin-bottom: 1px;"><strong>Contact No :</strong> <?php echo $row['Phone']; ?> </p>
                        <?php if($row['EmailId']!=''){?>
                        <p style="margin-bottom: 1px;"><strong>Email Id :</strong> <?php echo $row['EmailId']; ?></p>
                    <?php } if($row['Address']!=''){?>
                      <p style="margin-bottom: 1px;"><strong>Address :</strong> <?php echo $row['Address']; ?> </p>
                  <?php } ?>
                        <p style="margin-bottom: 1px;"><strong>DM No: </strong><?php echo $row['InvoiceNo']; ?></p>
                        <p style="margin-bottom: 1px;"><strong>Order Date: </strong><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></p>
                        <p style="margin-bottom: 1px;"><strong>Total Qty: </strong><?php echo $totalqty;?> | <strong>Dispatch: </strong><?php echo $dispatchqty;?> | <strong>Bal: </strong><?php echo $balqty;?></p>
                       <!-- <a href="dispatch-order.php?id=<?php echo $row['id']; ?>&invno=<?php echo $row['InvoiceNo']; ?>" class="btn btn-success btn-finish">Dispatch Items</a> -->
                                                       
                    </div>
                </div>
                <?php } ?>
                </div>








<?php include_once 'footer.php'; ?>

</div>

</main>
<br><br>
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
 function chageSurveyDetails(val,id){
   var action = "chageSurveyDetails";
            $.ajax({
                url: "ajax_files/ajax_customer_account.php",
                method: "POST",
                data: {
                    action: action,
                    id: id,
                    val:val
                },
                success: function(data) {
                    alert("Survey Details Changed.");
                  
                }
            });
 }
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
