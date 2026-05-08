<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once('vendor/php-excel-reader/excel_reader2.php');
require_once('vendor/SpreadsheetReader.php');
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Lead";
$Page = "Add-Lead";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Lead
    </title>
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
    <style type="text/css">
    .password-tog-info {
        display: inline-block;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        position: absolute;
        right: 50px;
        top: 30px;
        text-transform: uppercase;
        z-index: 2;
    }
    </style>
    <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

             <?php include_once 'back-header.php'; ?> 


            

                

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_social_media_marketing WHERE id='1'";
$row7 = getRecord($sql7);


if(isset($_POST['submit'])){
    $ClainReason = $_POST['ClainReason'];
    $CustId = $_POST['CustId'];
    $CreatedDate = $_POST['CreatedDate'];

    
    $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  
  if(in_array($_FILES["file"]["type"],$allowedFileType)){

         $targetPath = '../uploads/'.$_FILES['file']['name'];
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

               

               
               
                if (!empty($CellNo) || !empty($CustName) || !empty($Address)) {

                        $TicketNo= "#".rand(1000,9999);
                     $qx = "INSERT INTO tbl_leads SET TicketNo='$TicketNo',CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='1',Address='$Address',DocumentsStatus='',ClainReason = '$ClainReason',ClainStatus='In Progress',CreatedDate='$CreatedDate',CreatedBy='$user_id'";
                    $conn->query($qx);

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

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Upload Lead Excel</h4>

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
   <label class="form-label">Upload Excel File <span class="text-danger">*</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="sample_files/sample_lead_excel.xlsx" download>Download Sample Excel File</a></label>
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