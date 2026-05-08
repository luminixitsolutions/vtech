<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['User']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
//print_r($_SESSION["cart_item"]);echo "<pre>";
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


            <?php
                        $id = $_GET['id'];
                        $sql7 = "SELECT * FROM tbl_mp_pump_applications WHERE id='$id'";
                        $row7 = getRecord($sql7);
                        if (isset($_POST['submit'])) {
                            $applicant_name = addslashes(trim($_POST["applicant_name"]));
$father_name = addslashes(trim($_POST["father_name"]));
$district = addslashes(trim($_POST["district"]));
$tehsil = addslashes(trim($_POST["tehsil"]));
$village = addslashes(trim($_POST["village"]));
$lok_sabha = addslashes(trim($_POST["lok_sabha"]));
$vidhan_sabha = addslashes(trim($_POST["vidhan_sabha"]));
$pincode = addslashes(trim($_POST["pincode"]));
$mobile = addslashes(trim($_POST["mobile"]));
$email = addslashes(trim($_POST["email"]));
$gender = addslashes(trim($_POST["gender"]));
$aadhaar = addslashes(trim($_POST["aadhaar"]));
$account_holder = addslashes(trim($_POST["account_holder"]));
$account_number = addslashes(trim($_POST["account_number"]));
$ifsc = addslashes(trim($_POST["ifsc"]));
$bank_name = addslashes(trim($_POST["bank_name"]));
$branch_name = addslashes(trim($_POST["branch_name"]));
$samagra_id = addslashes(trim($_POST["samagra_id"]));
$family_samagra_id = addslashes(trim($_POST["family_samagra_id"]));
$caste = addslashes(trim($_POST["caste"]));
$khasra_number = addslashes(trim($_POST["khasra_number"]));
$water_source = addslashes(trim($_POST["water_source"]));
$micro_irrigation = addslashes(trim($_POST["micro_irrigation"]));
$land_area = addslashes(trim($_POST["land_area"]));
$borewell_depth = addslashes(trim($_POST["borewell_depth"]));
$water_requirement = addslashes(trim($_POST["water_requirement"]));
$distance_to_panel = addslashes(trim($_POST["distance_to_panel"]));
$ground_water_depth = addslashes(trim($_POST["ground_water_depth"]));
$document_submitted = addslashes(trim($_POST["document_submitted"]));
$AcDc = addslashes(trim($_POST["AcDc"]));
$Surface = addslashes(trim($_POST["Surface"]));
$PumpCapacity = addslashes(trim($_POST["PumpCapacity"]));
                            $CreatedDate = date('Y-m-d');
                            $ModifiedDate = date('Y-m-d');
                            $CreatedTime = date('h:i a');
$created_at = date("Y-m-d H:i:s"); // Current timestamp

$randno = rand(1, 100);
                     $src = $_FILES['documents']['tmp_name'];
                    $fnm = substr($_FILES["documents"]["name"], 0, strrpos($_FILES["documents"]["name"], '.'));
                    $fnm = str_replace(" ", "_", $fnm);
                    $ext = substr($_FILES["documents"]["name"], strpos($_FILES["documents"]["name"], "."));
                    $dest = '../uploads/' . $randno . "_" . $fnm . $ext;
                    $imagepath =  $randno . "_" . $fnm . $ext;
                    if (move_uploaded_file($src, $dest)) {
                        $documents = $imagepath;
                    } else {
                        $documents = $_POST['OldPhoto'];
                    }
                    
                    
                    
                            if ($_GET['id'] == '') {
                                $qx = "INSERT INTO tbl_mp_pump_applications SET 
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
    documents='$documents',
    created_at='$created_at',createdby='$user_id',document_submitted='$document_submitted',AcDc='$AcDc',Surface='$Surface',PumpCapacity='$PumpCapacity'";
                                $conn->query($qx);
                               
                                echo "<script>alert('Record Saved Successfully!');window.location.href='view-application-form.php';</script>";
                            } else {

                                 $query2 = "UPDATE tbl_mp_pump_applications SET 
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
    documents='$documents',
    updated_at='$created_at',modifiedby='$user_id',document_submitted='$document_submitted',AcDc='$AcDc',Surface='$Surface',PumpCapacity='$PumpCapacity' WHERE id = '$id'";
                                $conn->query($query2);

                                echo "<script>alert('Record Updated Successfully!');window.location.href='view-application-form.php';</script>";
                            }
                            //header('Location:courses.php'); 

                        }
                        ?>


            <div class="main-container">

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Create Pump Application Form

                        </h4>

                        <div class="card">

                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" enctype="multipart/form-data">
                                    <div class="form-row">


                                        <div class="form-group col-md-12">
        <label>Applicant Name (आवेदक का नाम) <span class="text-danger">*</span></label>
        <input type="text" name="applicant_name" class="form-control" required value="<?php echo $row7['applicant_name']; ?>">
    </div>

    <div class="form-group col-md-12">
        <label>Father's Name (पिता / पित का नाम) <span class="text-danger">*</span></label>
        <input type="text" name="father_name" class="form-control" required value="<?php echo $row7['father_name']; ?>">
    </div>

    <div class="form-group col-md-4">
        <label>District (जिला) <span class="text-danger">*</span></label>
        <input type="text" name="district" class="form-control" required value="<?php echo $row7['district']; ?>">
    </div>
    <div class="form-group col-md-4">
        <label>Tehsil (तहसील) <span class="text-danger">*</span></label>
        <input type="text" name="tehsil" class="form-control" required value="<?php echo $row7['tehsil']; ?>">
    </div>
    <div class="form-group col-md-4">
        <label>Village (गांव) <span class="text-danger">*</span></label>
        <input type="text" name="village" class="form-control" required value="<?php echo $row7['village']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Lok Sabha (लोकसभा) <span class="text-danger">*</span></label>
        <input type="text" name="lok_sabha" class="form-control" required value="<?php echo $row7['lok_sabha']; ?>">
    </div>
    <div class="form-group col-md-6">
        <label>Vidhan Sabha (विधानसभा) <span class="text-danger">*</span></label>
        <input type="text" name="vidhan_sabha" class="form-control" required value="<?php echo $row7['vidhan_sabha']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Pincode (पिन कोड) <span class="text-danger">*</span></label>
        <input type="text" name="pincode" class="form-control" required value="<?php echo $row7['pincode']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Mobile Number (मोबाइल नंबर) <span class="text-danger">*</span></label>
        <input type="text" name="mobile" class="form-control" required value="<?php echo $row7['mobile']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Email ID (ईमेल आईडी) <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" required value="<?php echo $row7['email']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Gender (लिंग) <span class="text-danger">*</span></label>
        <select name="gender" class="form-control" required>
            <option value="">Select</option>
            <option value="Male" <?php if($row7['gender'] == 'Male') echo 'selected'; ?>>पुरुष</option>
            <option value="Female" <?php if($row7['gender'] == 'Female') echo 'selected'; ?>>महिला</option>
            <option value="Other" <?php if($row7['gender'] == 'Other') echo 'selected'; ?>>अन्य</option>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label>Aadhaar Number (आधार मांक) <span class="text-danger">*</span></label>
        <input type="text" name="aadhaar" class="form-control" required value="<?php echo $row7['aadhaar']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Bank Account Holder Name <span class="text-danger">*</span></label>
        <input type="text" name="account_holder" class="form-control" required value="<?php echo $row7['account_holder']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Bank Account Number <span class="text-danger">*</span></label>
        <input type="text" name="account_number" class="form-control" required value="<?php echo $row7['account_number']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>IFSC Code <span class="text-danger">*</span></label>
        <input type="text" name="ifsc" class="form-control" required value="<?php echo $row7['ifsc']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Bank Name <span class="text-danger">*</span></label>
        <input type="text" name="bank_name" class="form-control" required value="<?php echo $row7['bank_name']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Branch Name <span class="text-danger">*</span></label>
        <input type="text" name="branch_name" class="form-control" required value="<?php echo $row7['branch_name']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Samagra ID <span class="text-danger">*</span></label>
        <input type="text" name="samagra_id" class="form-control" required value="<?php echo $row7['samagra_id']; ?>">
    </div>
    <div class="form-group col-md-6">
        <label>Family Samagra ID <span class="text-danger">*</span></label>
        <input type="text" name="family_samagra_id" class="form-control" required value="<?php echo $row7['family_samagra_id']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Caste (जाित) <span class="text-danger">*</span></label>
        <select name="caste" class="form-control" required>
            <option value="">Select</option>
            <option value="GEN" <?php if($row7['caste'] == 'GEN') echo 'selected'; ?>>GEN</option>
            <option value="OBC" <?php if($row7['caste'] == 'OBC') echo 'selected'; ?>>OBC</option>
            <option value="SC" <?php if($row7['caste'] == 'SC') echo 'selected'; ?>>SC</option>
            <option value="ST" <?php if($row7['caste'] == 'ST') echo 'selected'; ?>>ST</option>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label>Khasra Number (खसरा नंबर) <span class="text-danger">*</span></label>
        <input type="text" name="khasra_number" class="form-control" required value="<?php echo $row7['khasra_number']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Water Source Type (जल स्रोत का प्रकार) <span class="text-danger">*</span></label>
        <select name="water_source" class="form-control" required>
            <option value="">Select</option>
            <option value="Borewell 4 inch" <?php if($row7['water_source'] == 'Borewell 4 inch') echo 'selected'; ?>>Borewell 4 inch</option>
            <option value="Borewell 6 inch" <?php if($row7['water_source'] == 'Borewell 6 inch') echo 'selected'; ?>>Borewell 6 inch</option>
            <option value="Borewell 8 inch" <?php if($row7['water_source'] == 'Borewell 8 inch') echo 'selected'; ?>>Borewell 8 inch</option>
            <option value="Borewell 12 inch" <?php if($row7['water_source'] == 'Borewell 12 inch') echo 'selected'; ?>>Borewell 12 inch</option>
            <option value="Well" <?php if($row7['water_source'] == 'Well') echo 'selected'; ?>>कुआं</option>
            <option value="Canal" <?php if($row7['water_source'] == 'Canal') echo 'selected'; ?>>नहर</option>
            <option value="Pond" <?php if($row7['water_source'] == 'Pond') echo 'selected'; ?>>तालाब</option>
            <option value="River" <?php if($row7['water_source'] == 'River') echo 'selected'; ?>>नदी</option>
        </select>
    </div>
    
     <div class="form-group col-md-3">
    <label class="form-label">AC/DC </label>
    <select class="form-control" id="AcDc" name="AcDc">

        <option value="AC" <?php if ($row7["AcDc"] == 'AC') { ?> selected
            <?php } ?>>AC</option>
        <option value="DC" <?php if ($row7["AcDc"] == 'DC') { ?> selected
            <?php } ?>>DC</option>
    </select>
    <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
    <label class="form-label">Type Of Pump </label>

    <select class="form-control" id="Surface" name="Surface">
        <option selected="" disabled="" value="">Select Type Of Pump</option>
        <?php
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=4 ORDER BY Name ASC";
        $r = $conn->query($q);
        while ($rw = $r->fetch_assoc()) {
        ?>
            <option <?php if ($row7['Surface'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
        <?php } ?>
    </select>
    <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
    <label class="form-label">Capacity </label>

    <select class="form-control" id="PumpCapacity" name="PumpCapacity">
        <option selected="" disabled="" value="">Select Pump Capacity</option>
        <?php
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=2 ORDER BY id ASC";
        $r = $conn->query($q);
        while ($rw = $r->fetch_assoc()) {
        ?>
            <option <?php if ($row7['PumpCapacity'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
        <?php } ?>
    </select>
    <div class="clearfix"></div>
</div>

    <div class="form-group col-md-6">
        <label>Micro Irrigation Option (Yes/No) <span class="text-danger">*</span></label>
        <select name="micro_irrigation" class="form-control" required>
            <option value="">Select</option>
            <option value="Yes" <?php if($row7['micro_irrigation'] == 'Yes') echo 'selected'; ?>>Yes</option>
            <option value="No" <?php if($row7['micro_irrigation'] == 'No') echo 'selected'; ?>>No</option>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label>Total Land Area (हेक्टेयर में) <span class="text-danger">*</span></label>
        <input type="text" name="land_area" class="form-control" required value="<?php echo $row7['land_area']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Depth of Borewell (फीट में) <span class="text-danger">*</span></label>
        <input type="text" name="borewell_depth" class="form-control" required value="<?php echo $row7['borewell_depth']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Daily Water Requirement (लीटर में) <span class="text-danger">*</span></label>
        <input type="text" name="water_requirement" class="form-control" required value="<?php echo $row7['water_requirement']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Estimated Distance from Source to Panel (फीट में) <span class="text-danger">*</span></label>
        <input type="text" name="distance_to_panel" class="form-control" required value="<?php echo $row7['distance_to_panel']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Depth of Ground Water (फीट में) <span class="text-danger">*</span></label>
        <input type="text" name="ground_water_depth" class="form-control" required value="<?php echo $row7['ground_water_depth']; ?>">
    </div>

    <!--<div class="form-group col-md-12">
        <label>Upload Document (PDF/JPG/PNG) <span class="text-danger">*</span></label>
        <input type="file" name="documents" class="form-control">
        <?php if(!empty($row7['documents'])) { ?>
            <a href="<?php echo $row7['documents']; ?>" target="_blank">View Existing Document</a>
        <?php } ?>
    </div>-->
    
    <div class="form-group col-md-6">
        <label>Document Submitted <span class="text-danger">*</span></label>
        <select name="document_submitted" class="form-control" required>
            <option value="">Select</option>
            <option value="Yes" <?php if($row7['document_submitted'] == 'Yes') echo 'selected'; ?>>Yes</option>
            <option value="No" <?php if($row7['document_submitted'] == 'No') echo 'selected'; ?>>No</option>
        </select>
    </div>
</div>
                               <br>       

                                    <button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <br><br>

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