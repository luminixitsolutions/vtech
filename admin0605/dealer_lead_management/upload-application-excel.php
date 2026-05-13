<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
require_once('../vendor/php-excel-reader/excel_reader2.php');
require_once('../vendor/SpreadsheetReader.php');
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
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/select2/select2.css">
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
                        <h5 class="font-weight-bold py-3 mb-0">Upload Application Excel</h5>
                        
 <?php 

if (isset($_POST['submit'])) {
    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    if (in_array($_FILES["file"]["type"], $allowedFileType)) {
        $targetPath = '../uploads/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        //require('vendor/autoload.php'); // PhpSpreadsheet
        $Reader = new \SpreadsheetReader($targetPath);
        $sheetCount = count($Reader->sheets());

        for ($i = 0; $i < $sheetCount; $i++) {
            $Reader->ChangeSheet($i);

            foreach ($Reader as $Row) {
                // Skip header row
                if ($Row[0] == "Applicant Name" || $Row[0] == "") continue;

                $applicant_name   = mysqli_real_escape_string($conn, $Row[0]);
                $father_name      = mysqli_real_escape_string($conn, $Row[1]);
                $district         = mysqli_real_escape_string($conn, $Row[2]);
                $tehsil           = mysqli_real_escape_string($conn, $Row[3]);
                $village          = mysqli_real_escape_string($conn, $Row[4]);
                $lok_sabha        = mysqli_real_escape_string($conn, $Row[5]);
                $vidhan_sabha     = mysqli_real_escape_string($conn, $Row[6]);
                $pincode          = mysqli_real_escape_string($conn, $Row[7]);
                $mobile           = mysqli_real_escape_string($conn, $Row[8]);
                $email            = mysqli_real_escape_string($conn, $Row[9]);
                $gender           = mysqli_real_escape_string($conn, $Row[10]);
                $aadhaar          = mysqli_real_escape_string($conn, $Row[11]);
                $account_holder   = mysqli_real_escape_string($conn, $Row[12]);
                $account_number   = mysqli_real_escape_string($conn, $Row[13]);
                $ifsc             = mysqli_real_escape_string($conn, $Row[14]);
                $bank_name        = mysqli_real_escape_string($conn, $Row[15]);
                $branch_name      = mysqli_real_escape_string($conn, $Row[16]);
                $samagra_id       = mysqli_real_escape_string($conn, $Row[17]);
                $family_samagra_id= mysqli_real_escape_string($conn, $Row[18]);
                $caste            = mysqli_real_escape_string($conn, $Row[19]);
                $khasra_number    = mysqli_real_escape_string($conn, $Row[20]);
                $water_source     = mysqli_real_escape_string($conn, $Row[21]);
                $micro_irrigation = mysqli_real_escape_string($conn, $Row[22]);
                $land_area        = mysqli_real_escape_string($conn, $Row[23]);
                $borewell_depth   = mysqli_real_escape_string($conn, $Row[24]);
                $water_requirement= mysqli_real_escape_string($conn, $Row[25]);
                $distance_to_panel= mysqli_real_escape_string($conn, $Row[26]);
                $ground_water_depth= mysqli_real_escape_string($conn, $Row[27]);
                $document_submitted= mysqli_real_escape_string($conn, $Row[28]);
                $AcDc             = mysqli_real_escape_string($conn, $Row[29]);
                $Surface          = ""; // not in excel
                $PumpCapacity     = ""; // not in excel
                $status           = "1"; // default active

                $created_at = date("Y-m-d H:i:s");

                if (!empty($applicant_name) && !empty($mobile)) {
                    $sql = "INSERT INTO tbl_mp_pump_applications SET 
                        applicant_name='$applicant_name',
                        father_name='$father_name',
                        district='$district',
                        tehsil='$tehsil',
                        village='$village',
                        lok_sabha='$lok_sabha',
                        vidhan_sabha='$vidhan_sabha',
                        pincode='$pincode',
                        mobile='$mobile',
                        email='$email',
                        gender='$gender',
                        aadhaar='$aadhaar',
                        account_holder='$account_holder',
                        account_number='$account_number',
                        ifsc='$ifsc',
                        bank_name='$bank_name',
                        branch_name='$branch_name',
                        samagra_id='$samagra_id',
                        family_samagra_id='$family_samagra_id',
                        caste='$caste',
                        khasra_number='$khasra_number',
                        water_source='$water_source',
                        micro_irrigation='$micro_irrigation',
                        land_area='$land_area',
                        borewell_depth='$borewell_depth',
                        water_requirement='$water_requirement',
                        distance_to_panel='$distance_to_panel',
                        ground_water_depth='$ground_water_depth',
                        document_submitted='$document_submitted',
                        AcDc='$AcDc',
                        Surface='$Surface',
                        PumpCapacity='$PumpCapacity',
                        status='$status',
                        created_at='$created_at',
                        createdby='$user_id'";

                    $conn->query($sql);
                }
            }
        }
?>
<script>
alert("Excel Data Imported into the Database Successfully!");
window.location.href='upload-application-excel.php';
</script>
<?php
    } else {
        echo "<script>alert('Invalid File Type. Upload Excel File.');</script>";
    }
}
?>

<div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                
      
        

<div class="form-group col-md-12">
   <label class="form-label">Upload Excel File <span class="text-danger">*</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../sample_files/tbl_mp_pump_applications.xlsx" download>Download Sample Excel File</a></label>
    <input type="file" name="file" id="" class="form-control" placeholder="" autocomplete="off" required>
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
    <script src="<?php echo $SiteUrl;?>/assets/libs/select2/select2.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/analytics.js"></script>
     <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>
</body>

</html>
