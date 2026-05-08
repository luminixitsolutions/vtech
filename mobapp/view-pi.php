<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Profile";
$UserId = $_SESSION['User']['id'];
$user_id = $UserId;
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId'];
$Roll = $row11['Roll'];
//$mycity = $row['CityId'];
$ExpDate = date("d/m/Y", strtotime(str_replace('-', '/',$row11['Validity'])));

$sql = "SELECT * FROM tbl_quotation WHERE CustId='$UserId'";
$row = getRecord($sql);
$CustApprove = $row['CustApprove'];
$url =  'https://vtechsolar.com/uploads/'.$row["File"];
 ?>
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
    <link href="css/toastr.min.css" rel="stylesheet" id="style">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
    <!-- screen loader -->
    


    <!-- menu main -->
     <?php include_once 'sidebar.php'; ?>



    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
        <?php include_once 'top_header.php'; ?>
    
   
    
        <div class="main-container">
          

<div class="row justify-content-center" style="padding-left: 10px;padding-right: 10px;">
            <div class="col-6">
                <button type="button" class="btn btn-default rounded btn-block" onclick="onDownloadFileFromUrl('<?php echo $url;?>','<?php echo $row["File"];?>',1)">Download PI</button>
            </div>
            
             <div class="col-6">
                <button type="button" class="btn btn-default rounded btn-block" onclick="onDownloadFileFromUrl('<?php echo $url;?>','<?php echo $row["File"];?>',2)">View PI</button>
            </div>
            <br>
            <div class="col-12" style="padding-top: 10px;">
                <button type="button" class="btn btn-success rounded btn-block" id="approvebtn" onclick="approvePi(<?php echo $UserId;?>)" <?php if($CustApprove==1){?> disabled <?php } ?>>Approve PI</button>
            </div>
        </div>

            
            
            
            
        </div>
    </main>

    <?php include_once 'footer.php';?>


    <!-- color settings style switcher -->
    



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
     <script src="js/toastr.min.js"></script>
    <script>
          function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
  
  function onDownloadFileFromUrl(url,filename,Download){
         //Android.onDownloadFileFromUrl(''+url+'',''+filename+'');
        if(Download == 1){
            Android.onDownloadFileFromUrl(''+url+'',''+filename+'');
        }
        else{
         Android.onClickPdfReportView(''+url+'');
        }
    }
  
  function approvePi(cid){
      var action = "approvePi";
      $.ajax({  
                url :"ajax_files/ajax_customers.php",  
                method:"POST",  
                data:{action:action,cid:cid},
                 beforeSend:function(){
     $('#approvebtn').attr('disabled','disabled');
     $('#approvebtn').text('Please Wait...');
    },
                success:function(data){ 
                    toastr.success('PI Approved Successfully!', 'Success', {timeOut: 3000}); 
                    $('#approvebtn').attr('disabled','disabled');
     $('#approvebtn').text('Approved');
                }
      });
                
  }
      </script>
</body>

</html>
