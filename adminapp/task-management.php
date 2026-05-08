<?php session_start();
$sessionid = session_id();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Home";

$uid = $_REQUEST['uid'];    
//$_SESSION['Location'] = $city_id;
if($_REQUEST['uid'] == ''){
  $uid = $_SESSION['User']['id'];
}
else{
$uid = $_REQUEST['uid'];    
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}

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
    <link rel="manifest" href="manifest.json" />

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
</script>

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link rel="stylesheet" href="dist/css/styles.css" />
   
</head>

  

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop">
    
    
    
 <?php include_once 'sidebar.php'; ?>

    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
      <?php include_once 'top_header.php'; ?>

        <!-- page content start -->
<!-- page content start -->
   
<style>
.custom-card {
  border: none;
  background: #ffffff;
  border-radius: 16px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  height: 100px; /* Set fixed height */
  display: flex;
  align-items: center;
  justify-content: center;
}

.custom-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
}

.card-body {
  padding: 15px;
  text-align: center;
}

.project-name {
  font-size: 0.9rem;
  font-weight: 600;
  color: #333333;
  margin-bottom: 6px;
  text-transform: uppercase;
  line-height: 1.2;
  white-space: normal;
}

.project-count {
  font-size: 1.6rem;
  font-weight: bold;
  color: #1976d2;
}

.project-name {
  min-height: 40px;
}

.fancy-heading {
  text-align: center;
  font-size: 22px; /* default for desktop */
  font-weight: 700;
  letter-spacing: 1px;
  background: linear-gradient(to right, #f97316, #fb923c);
  color: #fff;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  text-transform: uppercase;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* ðŸ‘‡ Font size adjustment for mobile screens */
@media (max-width: 576px) {
  .fancy-heading {
    font-size: 16px;
    padding: 10px;
  }
}

</style>
        <div class="main-container  text-center" style="background-color:#fff;">

             <div class="container ">
                  <h5 class="card-header fancy-heading">
  TASK DASHBOARD
</h5>
                <div class="row text-center mt-4">

    <div class="col-6 col-md-3 col-xl-2">
        <a href="view-tasks.php" class="project-card">
            <div class="card custom-card mb-4">
                <div class="card-body text-center">
                    <div class="project-name">Total Task</div>
                    <div class="project-count">
                        <?php  
                        $sql4 = "SELECT * FROM tbl_tasks";
                        echo getRow($sql4);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <a href="view-tasks.php?FromDate=<?php echo date('Y-m-d'); ?>&ToDate=<?php echo date('Y-m-d'); ?>" class="project-card">
            <div class="card custom-card mb-4">
                <div class="card-body text-center">
                    <div class="project-name">Today Tasks</div>
                    <div class="project-count">
                        <?php  
                        $sql4 = "SELECT * FROM tbl_tasks WHERE TaskDate='".date('Y-m-d')."'";
                        echo getRow($sql4);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <a href="view-tasks.php?ClainStatus=Pending" class="project-card">
            <div class="card custom-card mb-4">
                <div class="card-body text-center">
                    <div class="project-name">Pending Tasks</div>
                    <div class="project-count">
                        <?php  
                        $sql4 = "SELECT * FROM tbl_tasks WHERE ClainStatus='Pending'";
                        echo getRow($sql4);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <a href="view-tasks.php?ClainStatus=In Process" class="project-card">
            <div class="card custom-card mb-4">
                <div class="card-body text-center">
                    <div class="project-name">In Process Tasks</div>
                    <div class="project-count">
                        <?php  
                        $sql4 = "SELECT * FROM tbl_tasks WHERE ClainStatus='In Process'";
                        echo getRow($sql4);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <a href="view-tasks.php?ClainStatus=Closed" class="project-card">
            <div class="card custom-card mb-4">
                <div class="card-body text-center">
                    <div class="project-name">Closed Tasks</div>
                    <div class="project-count">
                        <?php  
                        $sql4 = "SELECT * FROM tbl_tasks WHERE ClainStatus='Closed'";
                        echo getRow($sql4);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <a href="view-tasks.php?ClainStatus=Cancelled" class="project-card">
            <div class="card custom-card mb-4">
                <div class="card-body text-center">
                    <div class="project-name">Cancelled Tasks</div>
                    <div class="project-count">
                        <?php  
                        $sql4 = "SELECT * FROM tbl_tasks WHERE ClainStatus='Cancelled'";
                        echo getRow($sql4);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>




  
</div>


            </div>

           
           
                               
            
    </main>

    <!-- footer-->
  <?php include_once 'footer.php'; ?>


<script src="dist/aos.js"></script>
    <script>
      AOS.init({
        easing: 'ease-in-out-sine'
      });
    </script>


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

    <!-- PWA app service registration and works -->
    <script src="js/pwa-services.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>

       <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
      <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script>
        function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
    </script>

</body>

</html>
