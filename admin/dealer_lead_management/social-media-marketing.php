<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Lead";
$Page = "Add-Lead";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title;?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/flot/flot.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.css">
</head>

<body>
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] Ebd -->
    <!-- [ Layout wrapper ] Start -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'lead-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once '../top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Social Media Marketing</h5>
                        
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
                    <!-- [ content ] End -->
                    <!-- [ Layout footer ] Start -->
                    
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/popper/popper.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/analytics.js"></script>
     <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>
</body>

</html>
