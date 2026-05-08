<?php 
session_start();
include_once 'config.php';
require_once "exe-database.php";
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Site-Installation";
$Page = "Assign-Site-Installation";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Sell List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php

if(isset($_POST['submit'])){

   $number = count($_POST['CheckId']);

   $InstallerId = $_POST['InstallerId'];
   $CreatedDate = date('Y-m-d H:i:s');
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                $CustId = addslashes(trim($_POST['CustId'][$i]));
                $sql = "UPDATE tbl_users SET ContractorInstallerStatus='1',ContractorInstallerId='$InstallerId',ContractorInstallerDate='$CreatedDate' WHERE id='$CustId'";
                $conn->query($sql);

                $sql = "SELECT PumpCapacity FROM tbl_users WHERE id='$CustId'";
                $row = getRecord($sql);
                $PumpCapacity = $row['PumpCapacity'];

                $sql2 = "SELECT InstallationVal FROM tbl_contractor_commision WHERE UserId='$InstallerId' AND Capacity='$PumpCapacity'";
                $row2 = getRecord($sql2);
                $Amount = $row2['InstallationVal'];

                $sql = "DELETE FROM tbl_made_contractor_commision WHERE CustId='$CustId' AND Roll=5";
                $conn->query($sql);

                $sql = "INSERT INTO tbl_made_contractor_commision SET ContractorId='$InstallerId',CustId='$CustId',Capacity='$PumpCapacity',ScopeOfWork='Installation',Amount='$Amount',CreatedDate='$CreatedDate',Roll=5";
                $conn->query($sql);

                }
              }
            }
        }
        
   
        echo "<script>alert('Site Assign To Contractor');window.location.href='assign-site-to-installation.php';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Assign Site For Installation To Contractor
<!-- <span style="float: right;">
<a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New Sell</a></span> -->
</h4>

<div class="card" style="padding: 10px;">
    <form id="validation-form" method="post" enctype="multipart/form-data" action="">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                
<div class="form-row">
 <div class="form-group col-lg-4">
<label class="form-label"> Contractor<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="InstallerId" id="InstallerId" required>
<option selected="" value="">Select</option>
 <?php 
//   $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(34,35,36,37) AND Options LIKE '%86%'";
$sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(40)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>


</div>

       

 


                                            </div>
                                        </div>
                                    </div>
   </div>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
              
               
                <th>Customer Name</th>
                <th>Contact No</th>
                <th>Address</th>
                <th>Contractor</th>
                
                <th>Assign Date</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
           
            $sql = "SELECT ts.* FROM tbl_sell ts WHERE ts.Status=1";
            $sql.=" GROUP BY ts.CustId ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
                $sql22 = "SELECT tu2.Fname As CoName,tu.ContractorInstallerDate FROM tbl_users tu 
                          LEFT JOIN tbl_users tu2 ON tu2.id=tu.ContractorInstallerId 
                          WHERE tu.ContractorInstallerStatus=1 AND tu.id='".$row['CustId']."'";
                 $row22 = getRecord($sql22);         
                $rncnt22 = getRow($sql22);
                if($rncnt22 > 0){
                     $bcolor = "background-color: #b9efb9;";
                }
                else{
                    $bcolor = "";
                }
                

             ?>
            <tr style="<?php echo $bcolor;?>">
                <td><?php if($rncnt22 > 0){} else{?>
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $row['CustId']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['CustId']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?> </td>
                 <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['CustId']; ?>">
                 <input type="hidden" value="<?php echo $row['CustId']; ?>" name="CustId[]">
               <input type="hidden" value="<?php echo $row['Fname']; ?>" name="CustName[]">
               <input type="hidden" value="<?php echo $row['Phone']; ?>" name="CellNo[]">
               <input type="hidden" value="<?php echo $row['Address']; ?>" name="Address[]">
               
              <td><?php echo $row['CustName']; ?></td>
              <td><?php echo $row['CellNo']; ?></td>
               <td><?php echo $row['Address']; ?></td>
              <td><?php echo $row22['CoName']; ?></td>
               <td><?php if($row22['ContractorInstallerDate']==''){echo "";} else { echo date("d/m/Y", strtotime(str_replace('-', '/',$row22['ContractorInstallerDate']))); } ?></td>  
          
             
          
           
              
            </tr>
           <?php  $i++;} ?>
        </tbody>
    </table>
</div>

<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Assign</button>
</div>
</form>
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
     function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }

    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
    });
});
</script>
</body>
</html>
