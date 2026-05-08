<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage="Dashboard";
$Page = "Dashboard";
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$UserCat = $row77['CatId'];
$Options = explode(',',$row77['Options']);


/*function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 
$n = 10;


$sql = "SELECT * FROM products";
$row = getList($sql);
foreach($row as $result){
    $Code = RandomStringGenerator($n); 
    $Code2 = $Code."".$result['id'];
    $sql2 = "UPDATE products SET code='$Code2' WHERE id='".$result['id']."'";
    $conn->query($sql2);
}*/
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - Dashboard</title>
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
    .mr_5 {
        margin-right: 3rem !important;
    }
    </style>
   <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

            <?php include_once 'back-header.php'; ?> 


            

              


                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>

<div class="row">
                             
                            
               
                          <!-- Staustic card 2 Start -->
                      
                            
                           
                            
                        <div class="col-sm-6 col-xl-3">
                                <a href="#">
                               <div class="card bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2>0</h2>
                                        <h6 class="mb-0">Customer Management</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                           
                            <div class="col-sm-6 col-xl-3">
                                <a href="#">
                               <div class="card bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2>0</h2>
                                        <h6 class="mb-0">Serialized Product Registration</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <a href="#">
                               <div class="card bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2>0</h2>
                                        <h6 class="mb-0">Warranty Registration</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                           
                           
                            
                            
                    </div>
                        


</div>


                    



                <?php include_once 'footer.php'; ?>

            </div>

        </div>

    </div>

    <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>

</body>

</html>