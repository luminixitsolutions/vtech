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
    <title><?php echo $Proj_Title; ?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl; ?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/flot/flot.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/bootstrap-select/bootstrap-select.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/select2/select2.css">
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
                        <h5 class="font-weight-bold py-3 mb-0">Create Application Form</h5>

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

$status = addslashes(trim($_POST["status"]));
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
    created_at='$created_at',document_submitted='$document_submitted',status='$status',AcDc='$AcDc',Surface='$Surface',PumpCapacity='$PumpCapacity'";
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
    updated_at='$created_at',document_submitted='$document_submitted',status='$status',AcDc='$AcDc',Surface='$Surface',PumpCapacity='$PumpCapacity' WHERE id = '$id'";
                                $conn->query($query2);

                                echo "<script>alert('Record Updated Successfully!');window.location.href='view-application-form.php';</script>";
                            }
                            //header('Location:courses.php'); 

                        }
                        ?>

                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="row">

    <div class="form-group col-md-12">
        <label class="form-label">Applicant Name (आवेदक का नाम) <span class="text-danger">*</span></label>
        <input type="text" name="applicant_name" class="form-control" required value="<?php echo $row7['applicant_name']; ?>">
    </div>

    <div class="form-group col-md-12">
        <label class="form-label">Father's Name (पिता / पित का नाम) <span class="text-danger">*</span></label>
        <input type="text" name="father_name" class="form-control" required value="<?php echo $row7['father_name']; ?>">
    </div>

    <div class="form-group col-md-4">
        <label class="form-label">District (जिला) <span class="text-danger">*</span></label>
        <input type="text" name="district" class="form-control" required value="<?php echo $row7['district']; ?>">
    </div>
    <div class="form-group col-md-4">
        <label class="form-label">Tehsil (तहसील) <span class="text-danger">*</span></label>
        <input type="text" name="tehsil" class="form-control" required value="<?php echo $row7['tehsil']; ?>">
    </div>
    <div class="form-group col-md-4">
        <label class="form-label">Village (गांव) <span class="text-danger">*</span></label>
        <input type="text" name="village" class="form-control" required value="<?php echo $row7['village']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Lok Sabha (लोकसभा) <span class="text-danger">*</span></label>
        <input type="text" name="lok_sabha" class="form-control" required value="<?php echo $row7['lok_sabha']; ?>">
    </div>
    <div class="form-group col-md-6">
        <label class="form-label">Vidhan Sabha (विधानसभा) <span class="text-danger">*</span></label>
        <input type="text" name="vidhan_sabha" class="form-control" required value="<?php echo $row7['vidhan_sabha']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Pincode (पिन कोड) <span class="text-danger">*</span></label>
        <input type="text" name="pincode" class="form-control" required value="<?php echo $row7['pincode']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Mobile Number (मोबाइल नंबर) <span class="text-danger">*</span></label>
        <input type="text" name="mobile" class="form-control" required value="<?php echo $row7['mobile']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Email ID (ईमेल आईडी) </label>
        <input type="email" name="email" class="form-control" value="<?php echo $row7['email']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Gender (लिंग) <span class="text-danger">*</span></label>
        <select name="gender" class="form-control" required>
            <option value="">Select</option>
            <option value="Male" <?php if($row7['gender'] == 'Male') echo 'selected'; ?>>पुरुष</option>
            <option value="Female" <?php if($row7['gender'] == 'Female') echo 'selected'; ?>>महिला</option>
            <option value="Other" <?php if($row7['gender'] == 'Other') echo 'selected'; ?>>अन्य</option>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Aadhaar Number (आधार मांक) <span class="text-danger">*</span></label>
        <input type="text" name="aadhaar" class="form-control" required value="<?php echo $row7['aadhaar']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Bank Account Holder Name <span class="text-danger">*</span></label>
        <input type="text" name="account_holder" class="form-control" required value="<?php echo $row7['account_holder']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Bank Account Number <span class="text-danger">*</span></label>
        <input type="text" name="account_number" class="form-control" required value="<?php echo $row7['account_number']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
        <input type="text" name="ifsc" class="form-control" required value="<?php echo $row7['ifsc']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Bank Name <span class="text-danger">*</span></label>
        <input type="text" name="bank_name" class="form-control" required value="<?php echo $row7['bank_name']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Branch Name <span class="text-danger">*</span></label>
        <input type="text" name="branch_name" class="form-control" required value="<?php echo $row7['branch_name']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Samagra ID <span class="text-danger">*</span></label>
        <input type="text" name="samagra_id" class="form-control" required value="<?php echo $row7['samagra_id']; ?>">
    </div>
    <div class="form-group col-md-6">
        <label class="form-label">Family Samagra ID <span class="text-danger">*</span></label>
        <input type="text" name="family_samagra_id" class="form-control" required value="<?php echo $row7['family_samagra_id']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Caste (जाित) <span class="text-danger">*</span></label>
        <select name="caste" class="form-control" required>
            <option value="">Select</option>
            <option value="GEN" <?php if($row7['caste'] == 'GEN') echo 'selected'; ?>>GEN</option>
            <option value="OBC" <?php if($row7['caste'] == 'OBC') echo 'selected'; ?>>OBC</option>
            <option value="SC" <?php if($row7['caste'] == 'SC') echo 'selected'; ?>>SC</option>
            <option value="ST" <?php if($row7['caste'] == 'ST') echo 'selected'; ?>>ST</option>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Khasra Number (खसरा नंबर) <span class="text-danger">*</span></label>
        <input type="text" name="khasra_number" class="form-control" required value="<?php echo $row7['khasra_number']; ?>">
    </div>

    <div class="form-group col-md-3">
        <label class="form-label">Water Source Type <span class="text-danger">*</span></label>
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
        <label class="form-label">Micro Irrigation Option (Yes/No) <span class="text-danger">*</span></label>
        <select name="micro_irrigation" class="form-control" required>
            <option value="">Select</option>
            <option value="Yes" <?php if($row7['micro_irrigation'] == 'Yes') echo 'selected'; ?>>Yes</option>
            <option value="No" <?php if($row7['micro_irrigation'] == 'No') echo 'selected'; ?>>No</option>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Total Land Area (हेक्टेयर में) <span class="text-danger">*</span></label>
        <input type="text" name="land_area" class="form-control" required value="<?php echo $row7['land_area']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Depth of Borewell (फीट में) <span class="text-danger">*</span></label>
        <input type="text" name="borewell_depth" class="form-control" required value="<?php echo $row7['borewell_depth']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Daily Water Requirement (लीटर में) <span class="text-danger">*</span></label>
        <input type="text" name="water_requirement" class="form-control" required value="<?php echo $row7['water_requirement']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Estimated Distance from Source to Panel (फीट में) <span class="text-danger">*</span></label>
        <input type="text" name="distance_to_panel" class="form-control" required value="<?php echo $row7['distance_to_panel']; ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-label">Depth of Ground Water (फीट में) <span class="text-danger">*</span></label>
        <input type="text" name="ground_water_depth" class="form-control" required value="<?php echo $row7['ground_water_depth']; ?>">
    </div>

   <!-- <div class="form-group col-md-6">
        <label class="form-label">Upload Document (PDF/JPG/PNG) <span class="text-danger">*</span></label>
        <input type="file" name="documents" class="form-control">
        <?php if(!empty($row7['documents'])) { ?>
            <a href="<?php echo $row7['documents']; ?>" target="_blank">View Existing Document</a>
        <?php } ?>
    </div>-->
    
    <div class="form-group col-md-6">
        <label class="form-label">Document Submitted <span class="text-danger">*</span></label>
        <select name="document_submitted" class="form-control" required>
            <option value="">Select</option>
            <option value="Yes" <?php if($row7['document_submitted'] == 'Yes') echo 'selected'; ?>>Yes</option>
            <option value="No" <?php if($row7['document_submitted'] == 'No') echo 'selected'; ?>>No</option>
        </select>
    </div>
    
    <div class="form-group col-md-6">
        <label class="form-label">Application Status <span class="text-danger">*</span></label>
        <select name="status" class="form-control" required>
            <option value="">Select</option>
            <option value="0" <?php if($row7['status'] == '0') echo 'selected'; ?>>Pending</option>
            <option value="1" <?php if($row7['status'] == '1') echo 'selected'; ?>>Approved</option>
            <option value="2" <?php if($row7['status'] == '2') echo 'selected'; ?>>Reject</option>
        </select>
    </div>

    <div class="form-group col-md-2">
        <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Submit</button>
    </div>

</div>
                                </form>
                            </div>
                        </div>
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
    <script src="<?php echo $SiteUrl; ?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/libs/popper/popper.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/libs/select2/select2.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/libs/bootstrap-select/bootstrap-select.js"></script>

    <!-- Demo -->
    <script src="<?php echo $SiteUrl; ?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/analytics.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/pages/forms_selects.js"></script>
</body>

</html>