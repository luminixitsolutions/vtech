<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once "database.php";
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Orders";
$Page = "View-Orders";
$SiteUrl = "https://rjorg.in/shoppingbazaar/mobapp2";
$AdminSiteUrl = "https://rjorg.in/shoppingbazaar/adminapp";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Orders List</title>
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









<style type="text/css">
 
.bs-vertical-wizard {
    border-right: 1px solid #eaecf1;
    padding-bottom: 50px;
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
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Orders Tracking</h4>
<?php 
$Oid = $_GET['oid'];
$sql2 = "SELECT * FROM orders WHERE id='$Oid'";
$row2 = getRecord($sql2);
$CustId = $row2['UserId'];
$OrderNo = $row2['OrderNo'];
$pid = $_GET['pid'];
$sql1 = "SELECT ProductName FROM products WHERE id='$pid'";
$row1 = getRecord($sql1);
$ProdName = $row1['ProductName'];
$CreatedDate = date('Y-m-d H:i:s');

if($_POST['action'] == 'UpdateTrack'){
$id = $_POST['id'];
$pid = $_POST['pid'];
$oid = $_POST['oid'];
$SubDate = $_POST['SubDate'];
$OrderStatus = $_POST['OrderStatus'];
$Step2 = addslashes(trim($_POST['Step2']));
$Step3 = addslashes(trim($_POST['Step3']));
$Step4 = addslashes(trim($_POST['Step4']));

 $sql2 = "SELECT Step2,Step3,Step4 FROM tbl_subscription_dates WHERE Oid='$oid' AND SubDate='$SubDate' AND ProductId='$pid'";
$row2 = getRecord($sql2);
if($Step2!=''){
  if($_POST['Step2Date']=='' || $_POST['Step2Date']=='0000-00-00 00:00:00'){
    $Step2Date = date('Y-m-d H:i:s');
  }
  else{
    $Step2Date = $_POST['Step2Date'];
  }
  $Step2By = $user_id;
  //customer notification
  $sqlc11 = "SELECT Tokens,Phone,Fname FROM customers WHERE id='$CustId'";
  $data=mysqli_query($con,$sqlc11);
  while($rowc11=mysqli_fetch_array($data))
  {
  $title = $Step2;
  //$body =  "Updated at ".date("h:i a, d M", strtotime(str_replace('-', '/',$Step2Date)))."";
  $body = "Your Daily Doorstep Order ".$Step2;
  $reg_id = $rowc11[0];
  $registrationIds = array($reg_id);
  $url = "$SiteUrl/my-orders-details.php?oid=$oid";
  include '../incnotification.php';
    
  $Phone = $rowc11[1];
  $Fname = $rowc11[2];
  $TempId = "1307163593796955149";
   $Message = 'Dear '.$Fname.' For a seamless delivery experience, we will verify your address tomorrow between 10:00 AM and 06:00 PM Please allow our delivery executive inside the society for the same. Request you to also inform your security, if applicable. Have a great day! -Team Daily Door Services By - DDSSER';
   if($row2['Step2']==''){
  include 'incsmsfile.php';
   }
  }
  
  //admin notification
  $sqlc11_1 = "SELECT Tokens FROM customers WHERE Roll='1'";
  $data_1=mysqli_query($con3,$sqlc11_1);
  while($rowc11_1=mysqli_fetch_array($data_1))
  {
  $title = $OrderNo." ".$Step2;
  $body =  "Updated at ".date("h:i a, d M", strtotime(str_replace('-', '/',$Step2Date)))."";
  //$body = $OrderNo." Daily Doorstep Order ".$Step2;
  $reg_id = $rowc11_1[0];
  $registrationIds = array($reg_id);
  $url = "$AdminSiteUrl/my-orders-details.php?oid=$oid";
  include '../incnotification.php';
  }
}
if($Step3!=''){
    if($_POST['Step3Date']=='' || $_POST['Step3Date']=='0000-00-00 00:00:00'){
$Step3Date = date('Y-m-d H:i:s');
}
else{
    $Step3Date = $_POST['Step3Date'];
  }
  $Step3By = $user_id;
  //customer notification
  $sqlc11 = "SELECT Tokens FROM customers WHERE id='$CustId'";
  $data=mysqli_query($con,$sqlc11);
  while($rowc11=mysqli_fetch_array($data))
  {
  $title = $Step3;
  //$body =  "Updated at ".date("h:i a, d M", strtotime(str_replace('-', '/',$Step3Date)))."";
  $body = "Your Daily Doorstep Order ".$Step3;
  $reg_id = $rowc11[0];
  $registrationIds = array($reg_id);
  $url = "$SiteUrl/my-orders-details.php?oid=$oid";
  include '../incnotification.php';
  }
  
  //admin notification
  $sqlc11_1 = "SELECT Tokens FROM customers WHERE Roll='1'";
  $data_1=mysqli_query($con3,$sqlc11_1);
  while($rowc11_1=mysqli_fetch_array($data_1))
  {
  $title = $OrderNo." ".$Step3;
  $body =  "Updated at ".date("h:i a, d M", strtotime(str_replace('-', '/',$Step2Date)))."";
  //$body = $OrderNo." Daily Doorstep Order ".$Step3;
  $reg_id = $rowc11_1[0];
  $registrationIds = array($reg_id);
  $url = "$AdminSiteUrl/my-orders-details.php?oid=$oid";
  include '../incnotification.php';
  }
}
if($Step4!=''){
    if($_POST['Step4Date']=='' || $_POST['Step4Date']=='0000-00-00 00:00:00'){
$Step4Date = date('Y-m-d H:i:s');
}
else{
    $Step4Date = $_POST['Step4Date'];
  }
  $Step4By = $user_id;
  //customer notification
  $sqlc11 = "SELECT Tokens,Phone,Fname FROM customers WHERE id='$CustId'";
  $data=mysqli_query($con,$sqlc11);
  while($rowc11=mysqli_fetch_array($data))
  {
  $title = $Step4;
  //$body =  "Updated at ".date("h:i a, d M", strtotime(str_replace('-', '/',$Step4Date)))."";
  $body = "Your Daily Doorstep Order ".$Step4;
  $reg_id = $rowc11[0];
  $registrationIds = array($reg_id);
  $url = "$SiteUrl/my-orders-details.php?oid=$oid";
  include '../incnotification.php';
  
   $Phone = $rowc11[1];
  $Fname = $rowc11[2];
  $TempId = "1307163593857093936";
  $Message = 'How was the experience with your Daily Door Services delivery today? Please share your feedback with us http://www.dailydoorservices.com/ {DDSSER}';
if($OrderStatus == 1){
  include 'incsmsfile.php';
}
  }
  
  //admin notification
  $sqlc11_1 = "SELECT Tokens FROM customers WHERE Roll='1'";
  $data_1=mysqli_query($con3,$sqlc11_1);
  while($rowc11_1=mysqli_fetch_array($data_1))
  {
  $title = $OrderNo." ".$Step4;
  $body =  "Updated at ".date("h:i a, d M", strtotime(str_replace('-', '/',$Step4Date)))."";
  //$body = $OrderNo." Daily Doorstep Order ".$Step2;
  $reg_id = $rowc11_1[0];
  $registrationIds = array($reg_id);
  $url = "$AdminSiteUrl/my-orders-details.php?oid=$oid";
  include '../incnotification.php';
  
 
  
  }
}

//exit();
$randno = rand(1,100);
$src = $_FILES['TrackPhoto']['tmp_name'];
$fnm = substr($_FILES["TrackPhoto"]["name"], 0,strrpos($_FILES["TrackPhoto"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["TrackPhoto"]["name"],strpos($_FILES["TrackPhoto"]["name"],"."));
$dest = '../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$TrackPhoto = $imagepath;
} 
else{
  $TrackPhoto = $_POST['OldPhoto'];
}

$sql2 = "UPDATE tbl_subscription_dates SET Oid='$oid',Step2='$Step2',Step3='$Step3',Step4='$Step4',OrderStatus='$OrderStatus',Step2Date='$Step2Date',Step3Date='$Step3Date',Step4Date='$Step4Date',Step2By='$Step2By',Step3By='$Step3By',Step4By='$Step4By',Photo='$TrackPhoto',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE SubDate='$SubDate' AND Oid='$oid' AND id='$id'";
$conn->query($sql2);

  
  
echo "<script>alert('Tracking Status Updated Successfully!');window.location.href='view-order-tracking.php?oid=$oid&pid=$pid';</script>";
}
 ?>
<div class="card">
<div class="card-datatable table-responsive">
<div class="form-row">  
<div class="col-lg-6 col-md-6">
    <div class="card ui-timeline mb-4">
      <h5 class="card-header"><?php echo $ProdName; ?></h5>
      <div class="card-body">
        <div class="timelines-box">
     <?php 
      $sql = "SELECT * FROM tbl_subscription_dates WHERE Oid='$Oid' AND ProductId='$pid'";
      $row = getList($sql);
      foreach($row as $result){
        if($result['OrderStatus']=='2') {
          $OrderStatus = "Product In Progress!";

        }
        else if($result['OrderStatus']=='5') {
          $OrderStatus = "Product Dispatch!";
        }
        else{
          $OrderStatus = "Product Delivered!";
        }
        if($result['ModifiedDate']!=''){
            $UpdatedDate = $result['ModifiedDate'];
        }
        else{
          $UpdatedDate = $result['CreatedDate'];  
        }
        
      ?>     
        <div class="row pt-3 pb-4">
        <div class="col-auto text-right update-meta">
          <p class="text-muted mb-0 d-inline"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['SubDate'])));?></p>
          <?php if($result['OrderStatus'] == 1) {?>
           <i class="fa fa-check bg-success update-icon"></i>
         <?php } else if($result['OrderStatus'] == 5) {?>
           <i class="fa fa-truck bg-warning update-icon"></i>
         <?php } else{?>
          <i class="fa fa-times bg-danger update-icon"></i>
        <?php } ?>
        </div>
        <div class="col">
          <?php if($result['OrderStatus'] == 1) {?>
          <h6 class="mb-1">Product Delivered!</h6>
          <p class="text-muted mb-0">Product Delivered Successfully. Updated at <?php echo date("h:i a d M", strtotime(str_replace('-', '/',$result['ModifiedDate'])));?></p>
        <?php } else{?>
          <h6 class="mb-1"><?php echo $OrderStatus; ?></h6>
          <p class="text-muted mb-0"><?php echo $OrderStatus; ?> Updated at <?php echo date("h:i a, d M", strtotime(str_replace('-', '/',$UpdatedDate)));?></p>
        <?php } ?>
          <div class="bs-vertical-wizard" style="padding-bottom: 10px;">
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
                                     <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><img src="../uploads/<?php echo $result['Photo'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>   
                                     <?php } else{}?> 
                                    </a>
                                </li>   
                                <?php } else{?>  
                                <li class="locked">
                                    <a href="#">Step 4 :<i class="ico fa fa-lock ico-muted"></i></a>
                                </li> 
                                <?php } ?>    
                             </ul>
                        </div>
                       
                       <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modals-default<?php echo $result['id']; ?>"> Update Track</button>
                       
                       <?php include 'inctrackmodel.php'; ?>
        </div>
      </div>
    <?php } ?>
      
      
</div>
</div>
</div>
</div>

<div class="col-lg-6 col-md-12">

</div>
</div>

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
<script>
  function deleteTrackPhoto(id){
    if(confirm("Are you sure you want to delete Photo?"))  
           {  
             var action = "deleteTrackPhoto";
             var Photo = $('#OldPhoto'+id).val();
             $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {

      $('#show_photo'+id).hide();
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }
  }

  </script>

</body>
</html>
