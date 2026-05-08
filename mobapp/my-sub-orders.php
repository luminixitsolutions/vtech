<?php session_start();
require_once 'config.php';
$id = $_GET['id'];
$PageName = "My Sub. Orders";
$Page = "Room";
$WallMsg = "NotShow";
$Page = "Home";
$user_id = $_SESSION['User']['id'];?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

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
    <link href="vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/daterangepicker-master/daterangepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 

        <!-- page content start -->

       
            <div class="container mb-4" style="padding-right: 1px;
padding-left: 1px;">

                   
                <div class="card mb-3">
                     <div class="card-header border-bottom">
                        <h6 class="mb-0" style="text-align: center;">My Subscription Orders</h6>
                    </div>
                     <?php 
                                     $FromDate2 = $_POST['FromDate'];
                                     $ToDate2 = $_POST['ToDate'];
                                     $FromDate = date("Y-m-d", strtotime(str_replace('/', '-',$_POST['FromDate'])));
                                     $ToDate = date("Y-m-d", strtotime(str_replace('/', '-',$_POST['ToDate'])));
                                    
                                       $sql2 = "SELECT ord.*,os.Name As OrderStatus,pm.Name As Payment_Method FROM orders ord 
                                           LEFT JOIN payment_method pm ON pm.id=ord.PaymentMethod 
                                           LEFT JOIN order_status os ON os.id=ord.OrderProcess
                                           WHERE ord.UserId='$user_id' AND ord.Status=1 AND ord.Type='Subscription' ORDER BY ord.srno DESC"; 
                                     
                                    //echo $sql2;
                                        $res2 = $conn->query($sql2);
                                        $row_cnt = mysqli_num_rows($res2);
                                        if($row_cnt > 0){
                                        while($row = $res2->fetch_assoc()){
                                        if($row['OrderProcess'] == 1){
                                            $OrderStatus = "<span style='color:green;'>Order Delivered</span>";
                                        }
                                        else if($row['OrderProcess'] == 2){
                                            $OrderStatus = "<span style='color:orange;'>In Progress</span>";
                                        }
                                        else if($row['OrderProcess'] == 3){
                                            $OrderStatus = "<span style='color:red;'>Order Cancel</span>";
                                        }
                                        else if($row['OrderProcess'] == 4){
                                            $OrderStatus = "<span style='color:orange;'>In Progress</span>";
                                        }
                                        else if($row['OrderProcess'] == 5){
                                            $OrderStatus = "<span style='color:blue;'>Order Dispatch</span>";
                                        }
                                        else{
                                            $OrderStatus = "";
                                        }
                                        $Total_Order = $row["OrderTotal"]+$row["ShippingCharge"]-$row["Promoprice"]-$row["Discount"]-$row["SevenDaysFree"];
                                     ?>
                    <div class="card-body position-relative">
                        <div class="row mb-2">
                            <div class="col">
                                <p class="text-secondary small">Order On : <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['OrderDate'])))." ".$row['OrderTime'];?></p>
                            </div>
                           <!-- <div class="col-auto">
                                <p class="text-success"><?php echo $OrderStatus; ?></p>
                            </div>-->
                        </div>
                        <div class="media">                            
                            <div class="media-body">
                                <h6 class="mb-1 text-default"><a href="my-orders-details.php?oid=<?php echo $row['id'];?>"><?php echo $row['OrderNo'];?></a></h6>
                                <h6 class="mb-1">&#8377;<?php echo number_format($Total_Order,2); ?></h6>
                            </div>
                            <a href="my-orders-details.php?oid=<?php echo $row['id'];?>" style="padding-top: 10px;"><button class="btn btn-sm btn-default rounded">View Details</button></a>                          
                        </div>
                    </div>
                    <hr >
                <?php }} ?>
                    
                </div>
             
                
            
        </div>
    </main>
 <?php include_once 'footer.php';?>

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

  <script src="vendor/daterangepicker-master/moment.min.js"></script>
    <script src="vendor/daterangepicker-master/daterangepicker.js"></script>
    <!-- page level custom script -->
    <script src="js/app.js"></script>

    </body>

</html>
