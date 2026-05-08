<?php session_start();
require_once 'config.php';
$id = $_GET['id'];
$PageName = "Tracking Product";
$Page = "Home";
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
  
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 

<style type="text/css">
 
.bs-vertical-wizard {
    border-right: 1px solid #eaecf1;
    /*padding-bottom: 50px;*/
}

.bs-vertical-wizard ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.bs-vertical-wizard ul>li {
    display: block;
    position: relative;
}

.bs-vertical-wizard ul>li>a {
    display: block;
    padding: 10px 10px 10px 40px;
    color: #333c4e;
    font-size: 17px;
    font-weight: 400;
    letter-spacing: .8px;
}

.bs-vertical-wizard ul>li>a:before {
    content: '';
    position: absolute;
    width: 1px;
    height: calc(100% - 25px);
    background-color: #bdc2ce;
    left: 13px;
    bottom: -9px;
    z-index: 3;
}

.bs-vertical-wizard ul>li>a .ico {
    pointer-events: none;
    font-size: 14px;
    position: absolute;
    left: 10px;
    top: 15px;
    z-index: 2;
}

.bs-vertical-wizard ul>li>a:after {
    content: '';
    position: absolute;
    border: 2px solid #bdc2ce;
    border-radius: 50%;
    top: 14px;
    left: 6px;
    width: 16px;
    height: 16px;
    z-index: 3;
}

.bs-vertical-wizard ul>li>a .desc {
    display: block;
    color: #bdc2ce;
    font-size: 11px;
    font-weight: 400;
    line-height: 1.8;
    letter-spacing: .8px;
}

.bs-vertical-wizard ul>li.complete>a:before {
    background-color: #5cb85c;
    opacity: 1;
    height: calc(100% - 25px);
    bottom: -9px;
}

.bs-vertical-wizard ul>li.complete>a:after {display:none;}
.bs-vertical-wizard ul>li.locked>a:after {display:none;}
.bs-vertical-wizard ul>li:last-child>a:before {display:none;}

.bs-vertical-wizard ul>li.complete>a .ico {
    left: 8px;
}

.bs-vertical-wizard ul>li>a .ico.ico-green {
    color: #5cb85c;
}

.bs-vertical-wizard ul>li>a .ico.ico-muted {
    color: #bdc2ce;
}

.bs-vertical-wizard ul>li.current {
    background-color: #fff;
}

.bs-vertical-wizard ul>li.current>a:before {
    background-color: #ffe357;
    opacity: 1;
}

.bs-vertical-wizard ul>li.current>a:after {
    border-color: #ffe357;
    background-color: #ffe357;
    opacity: 1;
}

.bs-vertical-wizard ul>li.current:after, .bs-vertical-wizard ul>li.current:before {
    left: 100%;
    top: 50%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
}

.bs-vertical-wizard ul>li.current:after {
    border-color: rgba(255,255,255,0);
    border-left-color: #fff;
    border-width: 10px;
    margin-top: -10px;
}

.bs-vertical-wizard ul>li.current:before {
    border-color: rgba(234,236,241,0);
    border-left-color: #eaecf1;
    border-width: 11px;
    margin-top: -11px;
}

</style>
        <!-- page content start -->
<?php 
$Oid = $_GET['oid'];
$pid = $_GET['pid'];
$sql1 = "SELECT ProductName FROM products WHERE id='$pid'";
$row1 = getRecord($sql1);
$ProdName = $row1['ProductName'];
$CreatedDate = date('Y-m-d');
?>
   
   <?php 
    if(isset($_POST['cancel_submit'])){
  $oid = $_POST['oid'];
  $pid = $_POST['pid'];
  $id = $_POST['id'];
  $ReasonId = $_POST['ReasonId'];
  $CancelDate = $_POST['CancelDate'];
  $CancelTime = $_POST['CancelTime'];
  $sql22 = "SELECT * FROM order_cancel_reason WHERE id='$ReasonId'";
  $row22 = getRecord($sql22);
  if($ReasonId == 0){
    $Message = addslashes(trim($_POST['Message']));
  }
  else{
    $Message = addslashes(trim($row22['Reasons']));
  }
  /*$sql = "INSERT INTO cancel_orders SET Ordid='$oid',OrdNo='$ordno',CancelDate='$CancelDate',CancelTime='$CancelTime',Message='$Message',ReasonId='$ReasonId',CancelById='$user_id',CancelBy='Customer'";
  $conn->query($sql);*/
   $sql = "UPDATE tbl_subscription_dates SET OrderStatus='3',CancelDate='$CancelDate',CancelTime='$CancelTime',CancelMessage='$Message' WHERE id='$id' AND oid='$oid'";
  $conn->query($sql);
  ?>
  <script type="text/javascript">
    //alert("Order Canceled Successfully!");
    window.location.href="view-order-tracking.php?oid=<?php echo $oid; ?>&pid=<?php echo $pid; ?>";
  </script>
 <?php
 } ?>    
<div class="container mb-4" style="padding-right: 1px;padding-left: 1px;">
        <div class="card mb-3">
            <div class="card-header border-bottom">
                <h6 class="mb-0" style="text-align: center;"><?php echo $ProdName; ?></h6>
            </div>
    
 <div class="accordion" id="accordionExample">
    <?php 
        $CurrDate = date('Y-m-d');
      $sql = "SELECT * FROM tbl_subscription_dates WHERE Oid='$Oid' AND ProductId='$pid'";
      $row = getList($sql);
      foreach($row as $result){
        if($result['OrderStatus']=='2') {
          $OrderStatus = "<span style='color:orange;'>Product In Progress!<span>";
          $icon = "<i class='fa fa-times' style='font-size: 20px;color: red;'></i>";
        }
        else if($result['OrderStatus']=='5') {
          $OrderStatus = "Product Dispatch!";
          $icon = "<i class='fa fa-truck' style='font-size: 20px;color: orange;'></i>";
        }
        else if($result['OrderStatus']=='3') {
          $OrderStatus = "Order Cancelled!";
          $icon = "<i class='fa fa-truck' style='font-size: 20px;color: red;'></i>";
        }
        else{
          $OrderStatus = "Product Delivered!";
          $icon = "<i class='fa fa-check' style='font-size: 20px;color: #06f306;'></i>";
        }
      ?>  
                    <div class="card">
                        <div class="card-header" id="headingOne<?php echo $result['id']; ?>">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left px-0" type="button" data-toggle="collapse" data-target="#collapseOne<?php echo $result['id']; ?>" <?php if($CurrDate == $result['SubDate']) {?> aria-expanded="true" <?php } else{?> aria-expanded="false" <?php } ?>aria-controls="collapseOne<?php echo $result['id']; ?>">
                                    <?php echo $icon." ".date("d/m/Y", strtotime(str_replace('-', '/',$result['SubDate'])));?> 

                                    <span style="float:right;">
                                        <?php if($result['OrderStatus'] == 1) {?>
                                            <span style='color:#06f306;'>Product Delivered!</span>
                                          <?php } else{?> 
                                          <?php echo $OrderStatus; ?>   
                                          <?php } ?>
                                    </span>
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne<?php echo $result['id']; ?>" class="collapse <?php if($CurrDate == $result['SubDate']) {?>show<?php } ?>" aria-labelledby="headingOne<?php echo $result['id']; ?>" data-parent="#accordionExample">
                            <div class="card-body">
                                 <div class="bs-vertical-wizard">
                            <ul>

                                <li class="complete">
                                    <a href="#">Order Placed . <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc"></span>
                                    </a>
                                </li>
                               
                                  <?php if($result['Step2'] != '') {?>
                                  <li class="complete prev-step">
                                    <a href="#"><?php echo $result['Step2']; ?><i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Updated at <?php echo date("h:i a, d M", strtotime(str_replace('-', '/',$result['Step2Date'])));?></span>
                                    </a>
                                  </li> 
                                  <?php } else{?>  
                                  <li class="locked">
                                    <a href="#">Step 2 :<i class="ico fa fa-lock ico-muted"></i></a>
                                  </li>
                                  <?php } ?>
                                 
                                
                                <?php if($result['Step3'] != '') {?>  
                                <li class="complete prev-step">
                                    <a href="#"><?php echo $result['Step3']; ?><i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Updated at <?php echo date("h:i a, d M", strtotime(str_replace('-', '/',$result['Step3Date'])));?></span>
                                    </a>
                                </li>   
                                <?php } else{?>  
                                <li class="locked">
                                    <a href="#">Step 3 :<i class="ico fa fa-lock ico-muted"></i></a>
                                </li> 
                                <?php } ?>   

                                 <?php if($result['Step4'] != '') {?>  
                                <li class="complete prev-step">
                                    <a href="#"><?php echo $result['Step4']; ?><i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Updated at <?php echo date("h:i a, d M", strtotime(str_replace('-', '/',$result['Step4Date'])));?></span>
                               <?php if($result['Photo'] == ''){}
                               else if(file_exists("../uploads/".$result['Photo'])){?>      
                                <div class=" border-0 mb-2 overflow-hidden">
                                    <div class="card-body position-relative avatar avatar-90 mb-1 rounded">
                                    <a href="javascript:void(0)" class="background" style="background-image: url('../uploads/<?php echo $result['Photo']; ?>');">
                                        <img src="../uploads/<?php echo $result['Photo']; ?>" alt="">
                                    </a>
                                    </div>
                                </div> 
                                <?php } else{}?> 
                                    </a>
                                </li>   
                                <?php } else{?>  
                                <li class="locked">
                                    <a href="#">Step 4 :<i class="ico fa fa-lock ico-muted"></i></a>
                                </li> 
                                <?php } ?> 

                             </ul>
                             <?php if($result['OrderStatus']=='2') {?>
                             <a href="javascript:void(0)" data-toggle="modal" data-target="#modals-default2<?php echo $result['id']; ?>" style="float:right;padding-right: 20px;font-size: 25px;color: red;">Cancel Order</a>
                              <?php include 'canceled_modal.php'; ?>
                             <br><br><?php } ?>
                        </div>
                        <?php 
                        $PrevDate = date('Y-m-d', strtotime('-1 day', strtotime($result['SubDate'])));
                        if($CurrDate == $PrevDate){?>
                          <a href="shop-category.php" class="btn btn-success btn-sm"><span class="material-icons">local_mall</span> Add Item</a>
                      <?php } ?>
                            </div>
                        </div>
                    </div>
                     <?php } ?>
                    
                    
                </div>
              <br><br>
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
     $(document).ready(function() {
$(document).on("change", ".ReasonId", function(event){
  var val = this.value;
  if(val == '0'){
    $('.ReasonShow').show();
    $('.Message').attr("required",true);
  }
  else{
    $('.ReasonShow').hide();
    $('.Message').attr("required",false);
  }
});
});
     </script>
    </body>

</html>
