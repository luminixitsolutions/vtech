<?php session_start();
require_once 'config.php';
$id = $_GET['id'];
$PageName = "My Commission";
$UserId = $_SESSION['User']['id'];
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

<body class="body-scroll d-flex flex-column h-100 menu-overlay">



    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?>

        <!-- page content start -->



        <div class="container">
            <br>
            <h4>Applications Details</h4>
<?php 
 $id = $_GET['id'];
                        $sql7 = "SELECT * FROM tbl_mp_pump_applications WHERE id='$id'";
                        $row = getRecord($sql7);
                        ?>
            <div class="card-body" style="padding: 0px;">
            <table class="table table-bordered" style="margin-bottom: 30px; width:100%;">
  
    <tr><th>Applicant Name</th><td><?php echo $row['applicant_name']; ?></td></tr>
    <tr><th>Father's Name</th><td><?php echo $row['father_name']; ?></td></tr>
    <tr><th>District</th><td><?php echo $row['district']; ?></td></tr>
    <tr><th>Tehsil</th><td><?php echo $row['tehsil']; ?></td></tr>
    <tr><th>Village</th><td><?php echo $row['village']; ?></td></tr>
    <tr><th>Lok Sabha</th><td><?php echo $row['lok_sabha']; ?></td></tr>
    <tr><th>Vidhan Sabha</th><td><?php echo $row['vidhan_sabha']; ?></td></tr>
    <tr><th>Pincode</th><td><?php echo $row['pincode']; ?></td></tr>
    <tr><th>Mobile</th><td><?php echo $row['mobile']; ?></td></tr>
    <tr><th>Email</th><td><?php echo $row['email']; ?></td></tr>
    <tr><th>Gender</th><td><?php echo $row['gender']; ?></td></tr>
    <tr><th>Aadhaar</th><td><?php echo $row['aadhaar']; ?></td></tr>
    <tr><th>Account Holder</th><td><?php echo $row['account_holder']; ?></td></tr>
    <tr><th>Account Number</th><td><?php echo $row['account_number']; ?></td></tr>
    <tr><th>IFSC</th><td><?php echo $row['ifsc']; ?></td></tr>
    <tr><th>Bank Name</th><td><?php echo $row['bank_name']; ?></td></tr>
    <tr><th>Branch Name</th><td><?php echo $row['branch_name']; ?></td></tr>
    <tr><th>Samagra ID</th><td><?php echo $row['samagra_id']; ?></td></tr>
    <tr><th>Family Samagra ID</th><td><?php echo $row['family_samagra_id']; ?></td></tr>
    <tr><th>Caste</th><td><?php echo $row['caste']; ?></td></tr>
    <tr><th>Khasra Number</th><td><?php echo $row['khasra_number']; ?></td></tr>
    <tr><th>Water Source</th><td><?php echo $row['water_source']; ?></td></tr>
    <tr><th>Micro Irrigation</th><td><?php echo $row['micro_irrigation']; ?></td></tr>
    <tr><th>Land Area</th><td><?php echo $row['land_area']; ?></td></tr>
    <tr><th>Borewell Depth</th><td><?php echo $row['borewell_depth']; ?></td></tr>
    <tr><th>Water Requirement</th><td><?php echo $row['water_requirement']; ?></td></tr>
    <tr><th>Distance to Panel</th><td><?php echo $row['distance_to_panel']; ?></td></tr>
    <tr><th>Ground Water Depth</th><td><?php echo $row['ground_water_depth']; ?></td></tr>
    <tr>
        <th>Documents</th>
        <td>
            <?php if($row["documents"] == '') { ?>
                <span style="color:red;">No Document Found</span>
            <?php } else if(file_exists('../uploads/'.$row["documents"])) { ?>
                <a href="../uploads/<?php echo $row["documents"];?>" target="_blank">View Receipt</a>
            <?php } else { ?>
                <span style="color:red;">No Document Found</span>
            <?php } ?>
        </td>
    </tr>
    <tr><th>Created Date</th><td><?php echo date("d/m/Y", strtotime($row['created_at'])); ?></td></tr>

</table>
            </div>



        </div>


        </div>
    </main><br><br><br>
    <?php include_once 'footer.php'; ?>

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