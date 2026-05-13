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
                        <h5 class="font-weight-bold py-3 mb-0">Upload Lead Excel</h5>
                        
 <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_social_media_marketing WHERE id='1'";
$row7 = getRecord($sql7);

function RandomStringGenerator($n)
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
 

if(isset($_POST['submit'])){
    $ClainReason = $_POST['ClainReason'];
    $CustId = $_POST['CustId'];
    $CreatedDate = $_POST['CreatedDate'];

    
    $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  
  if(in_array($_FILES["file"]["type"],$allowedFileType)){

         $targetPath = '../../uploads/'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        
        $Reader = new SpreadsheetReader($targetPath);
        $sheetCount = count($Reader->sheets());
       
        for($i=0;$i<$sheetCount;$i++)
        {
            
            $Reader->ChangeSheet($i);
            
            foreach ($Reader as $Row)
            {
          
                $CellNo = "";
                if(isset($Row[0])) {
                    $CellNo = mysqli_real_escape_string($conn,$Row[0]);
                }
                
                $CustName = "";
                if(isset($Row[1])) {
                    $CustName = mysqli_real_escape_string($conn,$Row[1]);
                }

                 $Address = "";
                if(isset($Row[2])) {
                    $Address = mysqli_real_escape_string($conn,$Row[2]);
                }

               
$CreatedTime = date('h:i a');
               
               
                if (!empty($CellNo) || !empty($CustName) || !empty($Address)) {

                        $TicketNo= "#".rand(1000,9999);
                     $qx = "INSERT INTO tbl_leads SET TicketNo='$TicketNo',CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='1',Address='$Address',DocumentsStatus='',ClainReason = '$ClainReason',ClainStatus='In Progress',CreatedDate='$CreatedDate',CreatedBy='$user_id'";
                    $conn->query($qx);
                    $PostId = mysqli_insert_id($conn);
                    $n = 10;
                    $Code2 = RandomStringGenerator($n); 
                    $Code = $Code2."".$PostId;
                    $sql = "UPDATE tbl_leads SET code='$Code' WHERE id='$PostId'";
                    $conn->query($sql);

                      $Steps = "Customer In Leads";
  $sql = "INSERT INTO tbl_steps SET CustId='0',Steps='$Steps',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',CustName='$CustName',Address='$Address',Phone='$CellNo',LeadSource='$ClainReason',EmpId='$CustId',LeadId='$PostId'";
  $conn->query($sql);

                }
             }
        
         }
         
         $sql = "DELETE FROM tbl_leads WHERE CellNo='CellNo'";
         $conn->query($sql);
?>
<script>
alert("Excel Data Imported into the Database");
    window.location.href='upload-excel.php';
</script>
<?php
  }
  else
  { 
        $type = "error";
        $message = "Invalid File Type. Upload Excel File.";
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
                                    
                                
      
        <div class="form-group col-lg-6">
<label class="form-label"> Lead Source<span class="text-danger">*</span></label>
 <select class="form-control" name="ClainReason" id="ClainReason" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=10";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['ClainReason'] == $result['Name']){?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label"> Dealer/Employee/Users </label>
 <select class="select2-demo form-control" name="CustId" id="CustId">
     <option selected="" value="">Select </option>
  <optgroup label="Dealer">    
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=9";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</optgroup>

<optgroup label="Employee">
     <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(2,6,7,12)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</optgroup>



</select>
<div class="clearfix"></div>
</div>    

<div class="form-group col-md-4">
   <label class="form-label">Lead Date <span class="text-danger">*</span></label>
    <input type="date" name="CreatedDate" id="" class="form-control" placeholder="" autocomplete="off" required>
    <div class="clearfix"></div>
</div> 

<div class="form-group col-md-8">
   <label class="form-label">Upload Excel File <span class="text-danger">*</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../sample_files/sample_lead_excel.xlsx" download>Download Sample Excel File</a></label>
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
