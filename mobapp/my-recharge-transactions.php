<?php session_start();
require_once 'config.php';
$id = $_GET['id'];
$PageName = "Recharge Transactions";
$Page = "Recharge";
$WallMsg = "NotShow";
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

                   
                <div class="card">
                     <div class="card-header border-bottom">
                        <h6 class="mb-0" style="text-align: center;">My Recharge Transactions</h6>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <ul class="list-group list-group-flush">
                             <?php 
                                        $srno = 1;
                                       $sql2 = "SELECT rd.*,tro.Photo FROM recharge_details rd LEFT JOIN tbl_rec_operator tro ON rd.OperatorId=tro.Code WHERE rd.SkyUserId='$user_id' GROUP BY rd.id ORDER BY rd.id DESC"; 
                                        $res2 = $conn->query($sql2);
                                        $row_cnt = mysqli_num_rows($res2);
                                        if($row_cnt > 0){
                                        while($row = $res2->fetch_assoc()){
                                          
                                     ?>
                            <li class="list-group-item">
                                <div class="row align-items-center" onclick="getRechHist(<?php echo $row['id']; ?>)">
                                    <div class="col-auto pr-0">
                                        <div class="avatar avatar-40 rounded">
                                            <div class="background">
                                               <img src="../uploads/<?php echo $row['Photo']; ?>" style="width: 40px;height: 40px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-normal mb-1"><?php echo $row['MobileNo']; ?></h6>
                                       
                                        <p class="small text-secondary"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate'])))?> , <?php echo $row['CreatedTime']; ?></p>
                                    </div>
                                    <div class="col-auto">
                                        <h6 class="text-success">&#8377;<?php echo number_format($row["Amount"],2); ?></h6>
                                    </div>
                                </div>
                            </li>
                        <?php } }?>
                       
                           
                        </ul>
                        
                    </div>
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
 <script type="text/javascript">
                function getRechHist(id){
                    window.location.href="recharge-history.php?rechid="+id;
                }
            </script>
    </body>

</html>
