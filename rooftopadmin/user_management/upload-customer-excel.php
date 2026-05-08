<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
require_once('../vendor/php-excel-reader/excel_reader2.php');
require_once('../vendor/SpreadsheetReader.php');
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "Upload-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Lead
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once '../header_script.php'; ?>
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
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'account-sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once '../top_header.php'; ?>

                <?php 
if(isset($_POST['submit'])){
    $ProjectId = $_POST['ProjectId'];
     $ProjectType = $_POST['ProjectType'];
    $AgencyId = $_POST['AgencyId'];
    $SchemeId = $_POST['SchemeId'];
    $CreatedDate = date('Y-m-d');
    
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
          
                $BeneficiaryId = "";
                if(isset($Row[0])) {
                    $BeneficiaryId = mysqli_real_escape_string($conn,$Row[0]);
                }
                
                $Fname = "";
                if(isset($Row[1])) {
                    $Fname = mysqli_real_escape_string($conn,$Row[1]);
                }

                $Taluka = "";
                if(isset($Row[2])) {
                    $Taluka = mysqli_real_escape_string($conn,$Row[2]);
                }

                $Village = "";
                if(isset($Row[3])) {
                    $Village = mysqli_real_escape_string($conn,$Row[3]);
                }

                $Block = "";
                if(isset($Row[4])) {
                    $Block = mysqli_real_escape_string($conn,$Row[4]);
                }

                $District = "";
                if(isset($Row[5])) {
                    $District = mysqli_real_escape_string($conn,$Row[5]);
                }

                $StateId = "";
                if(isset($Row[6])) {
                    $StateId = mysqli_real_escape_string($conn,$Row[6]);
                }

                $Phone = "";
                if(isset($Row[7])) {
                    $Phone = mysqli_real_escape_string($conn,$Row[7]);
                }

                $PumpCapacity = "";
                if(isset($Row[8])) {
                    $PumpCapacity = mysqli_real_escape_string($conn,$Row[8]);
                }

                $WoNo = "";
                if(isset($Row[9])) {
                    $WoNo = mysqli_real_escape_string($conn,$Row[9]);
                }

                

               
$CreatedTime = date('h:i a');
               
               
                if (!empty($Fname) || !empty($Phone)) {
                    $qx = "INSERT INTO tbl_users SET BeneficiaryId='$BeneficiaryId',Fname='$Fname',
                     Taluka='$Taluka',Village = '$Village',Status='1',Phone='$Phone',Roll='5',
                     ProjectType = '$ProjectType',AgencyId='$AgencyId',SchemeId='$SchemeId',
                     CreatedDate='$CreatedDate',CreatedBy='$user_id',Block='$Block',District='$District',
                     StateId='$StateId',PumpCapacity='$PumpCapacity',WoNo='$WoNo',ProjectId='$ProjectId'";
                    $conn->query($qx);
                }
             }
        
         }
         
         $sql = "DELETE FROM tbl_users WHERE Taluka='Taluka'";
         $conn->query($sql);
?>
<script>
alert("Excel Data Imported into the Database");
    window.location.href='upload-customer-excel.php';
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
                        <h4 class="font-weight-bold py-3 mb-0">Upload Customer Excel</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                
      
                                    <div class="form-group col-md-3">
                                                        <label class="form-label">Project Type <span class="text-danger">*</span></label>
                                                        <select class="form-control" id="ProjectType" name="ProjectType" required>
                                                           <!--  <option value="" selected disabled>Select Project Type</option> -->
                                                            
                                                            <option value="2" <?php if ($_REQUEST["ProjectType"] == '2') { ?> selected <?php } ?>>Rooftop Projects</option> 
                                                        </select>
                                                        <div class="clearfix"></div>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label class="form-label">Gov Agency <span class="text-danger">*</span></label>
                                                        <select class="form-control" id="AgencyId" name="AgencyId">
                                                        <option value="" selected disabled>Select Gov Agency</option>
                                                            <?php
                                                           
                                                            $q = "select Fname,id from tbl_users WHERE Roll=11 ORDER BY Fname ASC";
                                                            $r = $conn->query($q);
                                                            while ($rw = $r->fetch_assoc()) {
                                                            ?>
                                                                <option <?php if ($_REQUEST['SchemeId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

 <div class="form-group col-md-3">
            <label class="form-label">Project <span class="text-danger">*</span></label>
<select class="form-control" id="ProjectId" name="ProjectId" required>
<option selected="" disabled="">Select Project</option>
  <?php 
        $q = "select * from tbl_rooftop_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['ProjectId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
    </div>

                                                    <div class="form-group col-md-3">
                                                        <label class="form-label">Yojana <span class="text-danger">*</span></label>
                                                        <select class="form-control" id="SchemeId" name="SchemeId" required>
                                                        <option value="" selected disabled>Select Yojana</option>
                                                            <?php
                                                           
                                                            $q = "select * from tbl_rooftop_scheme WHERE Status='1' ORDER BY Name ASC";
                                                            $r = $conn->query($q);
                                                            while ($rw = $r->fetch_assoc()) {
                                                            ?>
                                                                <option <?php if ($_REQUEST['SchemeId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

<div class="form-group col-md-12">
   <label class="form-label">Upload Excel File <span class="text-danger">*</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../sample_files/sample_customer_excel.xlsx" download>Download & Upload Sample Excel File</a>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../sample_files/state_excel.xlsx" download>Download State Excel File For State Id</a>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../sample_files/pump_capacity_excel.xlsx" download>Download Pump Capacity Excel File For Id</a></label>
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


                   


                    <?php include_once '../footer.php'; ?>
                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once '../footer_script.php'; ?>

</body>

</html>