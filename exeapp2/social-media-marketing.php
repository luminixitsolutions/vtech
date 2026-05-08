<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Lead";
$Page = "Add-Lead";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Lead
    </title>
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
    <style type="text/css">
    .password-tog-info {
        display: inline-block;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        position: absolute;
        right: 50px;
        top: 30px;
        text-transform: uppercase;
        z-index: 2;
    }
    </style>
    <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

             <?php include_once 'back-header.php'; ?> 


            

                

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_social_media_marketing WHERE id='1'";
$row7 = getRecord($sql7);


if(isset($_POST['submit'])){
    $Videos = addslashes(trim($_POST["Videos"]));
     $Blogs = addslashes(trim($_POST["Blogs"]));
    $Influencers = addslashes(trim($_POST["Influencers"]));
$Status = 1;
$Creative = addslashes(trim($_POST["Creative"]));
$DocumentsStatus = addslashes(trim($_POST['DocumentsStatus']));
$ClainReason = addslashes(trim($_POST["ClainReason"]));
$ClainStatus = addslashes(trim($_POST["ClainStatus"]));
$BranchId = addslashes(trim($_POST["BranchId"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');


 
    $query2 = "UPDATE tbl_social_media_marketing SET Videos='$Videos',Blogs='$Blogs',Influencers = '$Influencers',Creative='$Creative',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id' WHERE id = '1'";
  $conn->query($query2);
  echo "<script>alert('Record Updated Successfully!');window.location.href='social-media-marketing.php';</script>";


    //header('Location:courses.php'); 

  }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Social Media Marketing</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                
      
        <div class="form-group col-md-12">
   <label class="form-label">No. of Videos Created </label>
     <input type="text" name="Videos" id="Videos" class="form-control"
                                                placeholder="" value="<?php echo $row7["Videos"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 
 

<div class="form-group col-md-12">
                                            <label class="form-label">No. of Blogs Created </label>
                                            <input type="text" name="Blogs" id="Blogs" class="form-control"
                                                placeholder="" value="<?php echo $row7["Blogs"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>


<div class="form-group col-md-12">
                                            <label class="form-label">No. of influencers Created </label>
                                            <input type="text" name="Influencers" id="Influencers" class="form-control"
                                                placeholder="" value="<?php echo $row7["Influencers"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

 <div class="form-group col-md-12">
                                            <label class="form-label">No. of Creative Created </label>
                                            <input type="text" name="Creative" id="Creative" class="form-control"
                                                placeholder="" value="<?php echo $row7["Creative"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>                                       

</div>
<br>

                                   <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                
                                    </div>
                               </div>




  
                                

 </div>
 </form>





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

</body>

</html>