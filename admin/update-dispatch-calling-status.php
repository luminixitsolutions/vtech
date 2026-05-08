<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Products";
$Page = "Add-Products";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Product</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
<script src="ckeditor/ckeditor.js"></script>
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

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>
<?php 
$custid = $_GET['custid'];
$sql2 = "SELECT * FROM tbl_users WHERE id='$custid'";
$row2 = getRecord($sql2);
$sql = "SELECT * FROM tbl_dispatch_calling_info WHERE CustId='$custid' LIMIT 1";
$rncnt7 = getRow($sql);
$row7 = getRecord($sql);
if($rncnt7 > 0){
  $CustId = $row7['CustId'];
  $CellNo = $row7['CellNo'];
  $CustName = $row7['CustName'];
  $Taluka = $row7['Taluka'];
  $District = $row7['District'];
  $PumpCapacity = $row7['PumpCapacity'];
  $Subjects = $row7['Subjects'];
  $PersonName = $row7['PersonName'];
  $PersonContactNo = $row7['PersonContactNo'];
}
else{
  $CustId = $row2['id'];
  $CellNo = $row2['Phone'];
  $CustName = $row2['Fname'];
  $Taluka = $row2['Taluka'];
  $District = $row2['District'];
  $PumpCapacity = $row2['PumpCapacity'];
  $Subjects = "";
  $PersonName = "";
  $PersonContactNo = "";
}

?>

<?php 
  if(isset($_POST['submit'])){


$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');
$CreatedTime = date('h:i a');
$CustId= addslashes(trim($_POST['CustId']));
$CellNo = addslashes(trim($_POST['CellNo']));
$CustName = addslashes(trim($_POST['CustName']));
$Taluka = addslashes(trim($_POST['Taluka']));
$District = addslashes(trim($_POST['District']));
$PumpCapacity = addslashes(trim($_POST['PumpCapacity']));
$Subjects = addslashes(trim($_POST['Subjects']));
$PersonName = addslashes(trim($_POST['PersonName']));
$PersonContactNo = addslashes(trim($_POST['PersonContactNo']));
$keys   = $_POST['id'];
$Answer = $_POST['Answer'];
$Question = $_POST['Question'];
$Specify = $_POST['Specify'];
$sql = "DELETE FROM tbl_dispatch_calling_info WHERE SellId='$id' AND CustId='$CustId'";
$conn->query($sql);
$rncnt = $_POST['Rncnt'];

$result = array();
      foreach ($keys as $id => $key) {
          $result[$key] = array(
              '0' => $keys[$id],
              '1' => $Answer[$id],
              '2' => $Specify[$id],
              '3' => $Question[$id]
             
          ); 
      }

      if(is_array($result))
          {
              foreach($result as $row => $value)
              {
                $QuesId = $value[0];
                $Question = $value[3];
                $Answer = $value[1];
                $Specify = $value[2];
                //echo $QuesId." - ".$Answer."<br>";
               $sql22 = "INSERT INTO tbl_dispatch_calling_info SET SellId='$sellid',CustId='$CustId',QuesId='$QuesId',Question='$Question',Answer='$Answer',Specify='$Specify',CreatedBy='$user_id',CreatedDate='$CreatedDate',CellNo='$CellNo',CustName='$CustName',Taluka='$Taluka',District='$District',PumpCapacity='$PumpCapacity',Subjects='$Subjects',PersonName='$PersonName',PersonContactNo='$PersonContactNo'";
                $conn->query($sql22);

              }
            }

  echo "<script>alert('Dispatch Calling Confirmation Updated Successfully!');window.location.href='update-dispatch-calling-status.php';</script>";

    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Dispatched Calling Confirmation </h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

   <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Customer</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users tu WHERE tu.Roll=5 AND tu.ProjectType=1";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($CustId == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div> 

 <div class="form-group col-md-8">
   <label class="form-label">Customer Name </label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $CustName; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 
<div class="form-group col-md-4">
                                            <label class="form-label">Contact No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $CellNo; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
 

 <div class="form-group col-md-4">
   <label class="form-label">Taluka </label>
     <input type="text" name="Taluka" id="Taluka" class="form-control"
                                                placeholder="" value="<?php echo $Taluka; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 
 <div class="form-group col-md-4">
   <label class="form-label">District </label>
     <input type="text" name="District" id="District" class="form-control"
                                                placeholder="" value="<?php echo $District; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 
 
 <div class="form-group col-md-4">
   <label class="form-label">Pump Capacity </label>
     <input type="text" name="PumpCapacity" id="PumpCapacity" class="form-control"
                                                placeholder="" value="<?php echo $PumpCapacity; ?>"
                                                autocomplete="off" readonly>
    <div class="clearfix"></div>
 </div> 
 
 <div class="form-group col-md-8">
   <label class="form-label">Person Name </label>
     <input type="text" name="PersonName" id="PersonName" class="form-control"
                                                placeholder="" value="<?php echo $PersonName; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 
 
 <div class="form-group col-md-4">
   <label class="form-label">Person Contact No </label>
     <input type="text" name="PersonContactNo" id="PersonContactNo" class="form-control"
                                                placeholder="" value="<?php echo $PersonContactNo; ?>"
                                                autocomplete="off" readonly>
    <div class="clearfix"></div>
 </div> 


 <div class="form-group col-md-12">
   <label class="form-label">Subject </label>
     <input type="text" name="Subjects" id="Subjects" class="form-control"
                                                placeholder="" value="<?php echo $Subjects; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

<?php  
$sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=18";
$rncnt = getRow($sql);?>
<input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo $rncnt;?>">
<?php 
$row = getList($sql);
foreach($row as $result){
  $sql3 = "SELECT * FROM tbl_dispatch_calling_info WHERE QuesId='".$result["id"]."' AND CustId='$CustId'";
  $rncnt3 = getRow($sql3);
  $row3 = getRecord($sql3);

  ?>
   <input type="hidden" name="id[<?php echo $result["id"]; ?>]" value="<?php echo $result["id"]; ?>">
  <input type="hidden" name="Question[<?php echo $result["id"]; ?>]" value="<?php echo $result['Name'];?>">
<div class="form-group col-md-12">
<label class="form-label"><?php echo $result['Name'];?> </label><br>
<label class="switcher switcher-success">
                                        <input type="radio" class="switcher-input" name="Answer[<?php echo $result['id'];?>]" value="Yes" <?php if($row3['Answer'] == 'Yes'){?> checked <?php } ?>>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">Yes</span>
                                    </label>

                                    <label class="switcher switcher-danger">
                                        <input type="radio" class="switcher-input" name="Answer[<?php echo $result['id'];?>]" value="No" <?php if($row3['Answer'] == 'No'){?> checked <?php } ?>>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">No</span>
                                    </label>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label"><?php echo $result['ExpTitle'];?> </label>
<textarea name="Specify[<?php echo $result['id'];?>]" class="form-control" placeholder=""><?php echo $row3['Specify'];?></textarea>
 <div class="clearfix"></div>
</div>
<?php }  ?>

</div>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>
</div>
</div>
</div>

<?php include_once 'footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>
 
 <script type="text/javascript">
   $(document).ready(function() {
         $(document).on("change", "#CustId", function(event) {
            var val = this.value;
            window.location.href="update-dispatch-calling-status.php?custid="+val;
            var action = "getUserDetails";
            $.ajax({
                url: "ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType:"json",  
                success: function(data) {
                    
                    $('#Address').val(data.Address);
                    $('#Taluka').val(data.Taluka);
                    $('#Village').val(data.Village);
                    $('#District').val(data.District);
                    $('#CustName').val(data.Fname);
                    $('#CellNo').val(data.Phone);
                   
                    $('#PumpCapacity').val(data.PumpCapacity);
                    
                }
            });

        });
    });
 </script>
</body>
</html>