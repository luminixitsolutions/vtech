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
$sql = "SELECT tp.*,tc.Name As IssueName,tu.BeneficiaryId,tu.EmailId,tu.ProjectType FROM tbl_service_complaint tp
                    LEFT JOIN tbl_issues tc ON tc.id=tp.Issue 
                    LEFT JOIN tbl_users tu ON tu.id=tp.CustId WHERE tp.id='$id'";
$row7 = getRecord($sql);
$CellNo = $row7['Phone'];
if(isset($_POST['submit'])){
    $CustId = $_POST['CustId'];
    $CreatedDate = date('Y-m-d H:i:s');
$CreatedTime = date('h:i a');
$BeneficiaryId = addslashes(trim($_POST['BeneficiaryId']));
$Lattitude = addslashes(trim($_POST['Lattitude']));
$Longitude = addslashes(trim($_POST['Longitude']));
$CustName = addslashes(trim($_POST['CustName']));
$Specify = addslashes(trim($_POST['Specify']));
$ServiceDate = addslashes(trim($_POST['ServiceDate']));
$RelatedIssue = addslashes(trim($_POST['RelatedIssue']));
$Issue = addslashes(trim($_POST['Issue']));
$ClainStatus = addslashes(trim($_POST['ClainStatus']));
$Remark = addslashes(trim($_POST['Remark']));
$SerialNo = addslashes(trim($_POST['SerialNo']));
$Problem = addslashes(trim($_POST['Problem']));

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


$sql = "INSERT INTO tbl_complaint_engg_actions SET EnggId='$user_id',CompId='$id',CustId='$CustId',BeneficiaryId='$BeneficiaryId',CustName='$CustName',ServiceDate='$ServiceDate',RelatedIssue='$RelatedIssue',Issue='$Issue',ClainStatus='$ClainStatus',Specify='$Specify',Remark='$Remark',Photo='$Photo',Lattitude='$Lattitude',Longitude='$Longitude',CreatedBy='$user_id',CreatedDate='$CreatedDate',Problem='$Problem',SerialNo='$SerialNo'";
$conn->query($sql);

if($ClainStatus == 'Not Solved'){
$sql = "UPDATE tbl_service_complaint SET ClainStatus='$ClainStatus',EnggSolveStatus='Not Solved',EnggAssignStatus='0',EnggAssignId=0,EnggAssignDate='' WHERE id='$id'";
$conn->query($sql);
}
else if($ClainStatus == 'Under Maintaince'){
$sql = "UPDATE tbl_service_complaint SET EnggSolveStatus='Under Maintaince' WHERE id='$id'";
$conn->query($sql);
}
else{
  $sql = "UPDATE tbl_service_complaint SET ClainStatus='$ClainStatus',EnggSolveStatus='$ClainStatus',EnggAssignStatus='1' WHERE id='$id'";
$conn->query($sql);  
}

// $otp = rand(1000,9999);
// $_SESSION['otp'] = $otp;
echo "<script>window.location.href='pending-complaints.php';</script>";
}
?>


        <div class="main-container">

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Take Complaint Action

</h4>

<div class="card">

<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

   <input type="hidden" name="CustId" id="CustId" class="form-control" placeholder="" value="<?php echo $row7['CustId']; ?>" autocomplete="off" readonly>
<div class="form-group col-md-4">
                                            <label class="form-label">Beneficiary ID </label>
                                            <input type="text" name="BeneficiaryId" class="form-control"
                                                placeholder="" value="<?php echo $row7['BeneficiaryId']; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-4">
                                            <label class="form-label">Customer Name </label>
                                            <input type="text" name="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row7['CustName']; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 
       
<div class="form-group col-md-4">
                                            <label class="form-label"> Date </label>
                                            <input type="date" name="ServiceDate" id="ServiceDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 
        
      
                                       
<div class="form-group col-md-4">
<label class="form-label"> Service Related Issue<span class="text-danger">*</span></label>
 <select class="form-control" name="RelatedIssue" id="RelatedIssue" required>

<option selected="" value="">Select Related Issue</option>

  <option value="Repair" <?php if($row7['RelatedIssue'] == 'Repair'){?> selected <?php } ?>>Repair</option>
    <option value="Replacement" <?php if($row7['RelatedIssue'] == 'Replacement'){?> selected <?php } ?>>Replacement</option>
</select>
<div class="clearfix"></div>
</div>

<!-- <div class="form-group col-lg-4">
<label class="form-label"> Issue<span class="text-danger">*</span></label>
 <select class="form-control" name="Issue" id="Issue" required>
<option selected="" value="">Select Issue</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_issues WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['Issue'] == $result['id']){?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div> -->

<div class="form-group col-md-4">
                                            <label class="form-label">Problems </label>
                                            <textarea class="form-control"
                                                placeholder="" name="Problem"
                                                autocomplete="off" ><?php echo $row7['Problem']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-4">
                                            <label class="form-label"> Serial No </label>
                                            <input type="text" name="SerialNo" id="SerialNo" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 

 <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Status </label>
                                            <select class="form-control" id="ClainStatus" name="ClainStatus">
<option <?php if($row7['ClainStatus']=='Under Maintaince'){ ?> selected <?php } ?> value="Under Maintaince">Under Maintaince</option> 
<option <?php if($row7['ClainStatus']=='Issue Solved'){ ?> selected <?php } ?> value="Issue Solved">Issue Solved</option>
 <option <?php if($row7['ClainStatus']=='Not Solved'){ ?> selected <?php } ?> value="Not Solved">Not Solved</option>
          
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>  

                                        <div class="form-group col-md-4">
                                            <label class="form-label">If Not Solved, Specify </label>
                                            <textarea name="Specify" id="Specify" class="form-control"
                                                placeholder=""
                                                autocomplete="off"><?php echo $row7['Specify']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div> 

                                           <div class="form-group col-md-4">
                                            <label class="form-label">Remark </label>
                                            <textarea name="Remark" id="Remark" class="form-control"
                                                placeholder=""
                                                autocomplete="off"></textarea>
                                            <div class="clearfix"></div>
                                        </div> 

                                          <div class="form-group col-md-12">
                                            <label class="form-label">Photo from site <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto"
                                                    value="<?php echo $row7['Photo'];?>" id="OldPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo']=='') {} else{?>
                                            <span id="show_photo">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        href="javascript:void(0)"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo"></a><img
                                                        src="../uploads/<?php echo $row7['Photo'];?>" alt=""
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
