<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
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
  <br>
   <h4>Assigning Items</h4>
  
                            <div class="card-body" style="padding: 0px;">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="tabhome125" role="tabpanel" aria-labelledby="tabhome125-tab">
                                        <?php 
           
             $sql = "SELECT ts.*,tb.Name As StoreName,tu.Fname As StoreIncName,tu2.Fname As StoreExeName FROM tbl_distibute_items2 ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON ts.StoreInchId=tu.id 
                    LEFT JOIN tbl_users tu2 ON ts.StoreExeId=tu2.id WHERE ts.Status=1 AND ts.StoreInchId='$user_id' 
                    ";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                 $sql2 = "SELECT SUM(Qty) AS TotQty FROM `tbl_distibute_item_details2` WHERE DistId='".$row['id']."'";
                $row2 = getRecord($sql2);
                $TotQty = $row2['TotQty'];
             ?>
<div class="card mb-4" id="bgcolor<?php echo $row['id'];?>">

                    <div class="card-body">
                         <div class="row">
                                    <div class="col">
                        <h6 style="margin-bottom: 1px;font-size: 15px;"><strong>Total Stock Qty : </strong><?php echo $TotQty; ?></h6>
                      
                        
                        <p style="margin-bottom: 1px;"><strong>Date :</strong> <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?> </p>
                         <p style="margin-bottom: 1px;"><strong>Store Executive Name :</strong> <?php echo $row['StoreExeName']; ?> </p>
                     </div>
                                    <div class="col-auto pl-0">
                                        <a href="view-assigning-store-items.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-finish">View</a>
                                    </div>
                                </div>
                    </div>
                </div>
                <?php }   ?>

                

                                    </div>
                                
                                   
                                </div>
                            </div>

   
               
                </div>

 
<br><br>




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
