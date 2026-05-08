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
$sql = "SELECT * FROM tbl_trip_details WHERE id='$id'";
$row7 = getRecord($sql);
if(isset($_POST['submit'])){
$DriverName = addslashes(trim($_POST['DriverName']));
$VehicalNo = addslashes(trim($_POST['VehicalNo']));
$InDate = addslashes(trim($_POST['InDate']));
$TripDetails = addslashes(trim($_POST['TripDetails']));
$OpeningReading = addslashes(trim($_POST['OpeningReading']));
$StartLattitude = addslashes(trim($_POST['StartLattitude']));
$StartLongitude = addslashes(trim($_POST['StartLongitude']));
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

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

$randno = rand(1,100);
$src = $_FILES['StartPhoto']['tmp_name'];
$fnm = substr($_FILES["StartPhoto"]["name"], 0,strrpos($_FILES["StartPhoto"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["StartPhoto"]["name"],strpos($_FILES["StartPhoto"]["name"],"."));
$dest = '../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$StartPhoto = $imagepath ;
} 
else{
    $StartPhoto = $_POST['OldStartPhoto'];
}

if($_GET['id']==''){
$sql = "INSERT INTO tbl_trip_details SET DriverId='$user_id',DriverName='$DriverName',VehicalNo='$VehicalNo',InDate='$InDate',TripDetails='$TripDetails',OpeningReading='$OpeningReading',StartLattitude='$StartLattitude',StartLongitude='$StartLongitude',CreatedBy='$user_id',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',StartPhoto='$StartPhoto',InTime='$CreatedTime'";
$conn->query($sql);
$PostId = mysqli_insert_id($conn);
$TripNo = rand(1000,9999)."".$PostId;
$sql2 = "UPDATE tbl_trip_details SET TripNo='$TripNo' WHERE id='$PostId'";
$conn->query($sql2);
echo "<script>window.location.href='running-trips.php';</script>";
}
else{
$sql = "UPDATE tbl_trip_details SET InDate='$InDate',TripDetails='$TripDetails',OpeningReading='$OpeningReading',StartPhoto='$StartPhoto' WHERE id='$id'";
$conn->query($sql);
echo "<script>window.location.href='running-trips.php';</script>";
}
}
?>


        <div class="main-container">

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Start Trip

</h4>

<div class="card">

<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">



       <div class="form-group col-md-3">
<label class="form-label">Driver Name </label>
<input type="text" name="DriverName" id="DriverName" class="form-control" placeholder="" value="<?php echo $Name; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Vehicle No </label>
<input type="text" name="VehicalNo" id="VehicalNo" class="form-control" placeholder="" value="<?php echo $row110['VehicalNo']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
                                            <label class="form-label">In Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="InDate" id="InDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['InDate']; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 
        
      
     
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Trip Details <span
                                                    class="text-danger">*</span> </label>
                                            <textarea name="TripDetails" id="TripDetails" class="form-control"
                                                placeholder=""
                                                autocomplete="off" required><?php echo $row7['TripDetails']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-3">
<label class="form-label">Opening Reading <span class="text-danger">*</span></label>
<input type="number" name="OpeningReading" id="OpeningReading" class="form-control" placeholder="" value="<?php echo $row7['OpeningReading']; ?>" required min="0">
 <div class="clearfix"></div>
</div>

 <div class="form-group col-md-12">
                                            <label class="form-label">Opening Reading Photo <span class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="StartPhoto"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldStartPhoto"
                                                    value="<?php echo $row7['StartPhoto'];?>" id="OldStartPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['StartPhoto']=='') {} else{?>
                                            <span id="show_photo">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        href="javascript:void(0)"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo"></a><img
                                                        src="../uploads/<?php echo $row7['StartPhoto'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                        <div class="form-group col-md-3">
<label class="form-label">Lattitude </label>
<input type="text" name="StartLattitude" id="StartLattitude" class="form-control" placeholder="" value="<?php echo $Latitude; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="StartLongitude" id="StartLongitude" class="form-control" placeholder="" value="<?php echo $Longitude; ?>" readonly>
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
