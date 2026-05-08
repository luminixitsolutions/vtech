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
        

<?php
$id = $_GET['id'];
$sql = "SELECT * FROM tbl_users WHERE id='$id'";
$row7 = getRecord($sql);
$CellNo = $row7['Phone'];
if(isset($_POST['submit'])){
    $CustId = $_POST['CustId'];
    $CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');
$InstInspectionDate = addslashes(trim($_POST['InstInspectionDate']));
$Lattitude = addslashes(trim($_POST['Lattitude']));
$Longitude = addslashes(trim($_POST['Longitude']));
$InstInspectionStatus = addslashes(trim($_POST['InstInspectionStatus']));
$Specify = addslashes(trim($_POST['Specify']));

$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
    $Photo = $_POST['OldPhoto'];
}

$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
$dest2 = '../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
} 
else{
    $Photo2 = $_POST['OldPhoto2'];
}

$sql = "UPDATE tbl_users SET InspectionPhoto='$Photo',InstInspectionDate='$InstInspectionDate',InstInspectionStatus='$InstInspectionStatus',InspectionSpecify='$Specify',InspectionBy='$user_id',InspectionLattitude='$Lattitude',InspectionLongitude='$Longitude',InspectionOtpVerify=0 WHERE id='$id'";
$conn->query($sql);

$otp = rand(1000,9999);
$_SESSION['otp'] = $otp;
echo "<script>window.location.href='inspection-otp-verify.php?id=$id&phone=$CellNo';</script>";
}
?>


        <div class="main-container">

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Inspection

</h4>

<div class="card">

<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

   

 <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Customer </label>
                                            <select class="form-control" id="CustId" name="CustId">
<!-- <option selected="" disabled="" value="">Select Customer</option> -->
   <?php 
        $q = "SELECT tu.* FROM tbl_users tu WHERE tu.id='$id' ORDER BY tu.Fname";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option  selected value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>   

       
<div class="form-group col-md-4">
                                            <label class="form-label">Inspection Date </label>
                                            <input type="date" name="InstInspectionDate" id="InstInspectionDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['InstInspectionDate']; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 
        
      
                                       


 <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Inspection Done/Not </label>
                                            <select class="form-control" id="InstInspectionStatus" name="InstInspectionStatus">
<option <?php if($row7['InstInspectionStatus']=='Yes'){ ?> selected <?php } ?> value="Yes">Yes</option>
 <option <?php if($row7['InstInspectionStatus']=='No'){ ?> selected <?php } ?> value="No">No</option>
            
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>  

                                        <div class="form-group col-md-4">
                                            <label class="form-label">If No, Specify </label>
                                            <textarea name="Specify" id="Specify" class="form-control"
                                                placeholder=""
                                                autocomplete="off"><?php echo $row7['InspectionSpecify']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div> 

                                          <div class="form-group col-md-12">
                                            <label class="form-label">GEO tagged Photo from site <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto"
                                                    value="<?php echo $row7['InspectionPhoto'];?>" id="OldPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['InspectionPhoto']=='') {} else{?>
                                            <span id="show_photo">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        href="javascript:void(0)"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo"></a><img
                                                        src="../uploads/<?php echo $row7['InspectionPhoto'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>


                                        <div class="form-group col-md-3">
<label class="form-label">Lattitude </label>
<input type="text" name="Lattitude" id="Lattitude" class="form-control" placeholder="" value="<?php echo $Latitude; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="Longitude" id="Longitude" class="form-control" placeholder="" value="<?php echo $Longitude; ?>" readonly>
<div class="clearfix"></div>
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

<script>
    function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }


    function getItemLists(id){
        window.location.href="dispatch-order.php?CustId="+id;
    }

    $(document).ready(function() {
    $('#example').DataTable({
       "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
    searching: false,
    });
});
</script>
</body>
</html>
