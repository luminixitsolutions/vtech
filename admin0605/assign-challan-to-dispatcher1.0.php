<?php 
session_start();
include_once 'config.php';
//require_once "exe-database.php";
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Challan-Dispatcher";
$Page = "Assign-Challan-Dispatcher";
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

   
   $DispatcherId = $_POST['DispatcherId'];
   $CreatedDate = date('Y-m-d H:i:s');
   $rncnt = $_POST['rncnt'];
    $rncnt2 = $_POST['rncnt2'];
    if($rncnt > 0){
      $number = count($_POST['CheckId']);
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                 $QtnId = addslashes(trim($_POST['QtnId'][$i]));
                $sql = "UPDATE tbl_sell SET ContractorAssignStatus='1',ContractorAssignId='$DispatcherId',ContractorAssignDate='$CreatedDate' WHERE id='$QtnId'";
                $conn->query($sql);
                $sql3 = "SELECT CustId FROM tbl_sell WHERE id='$QtnId'";
                $row3 = getRecord($sql3);
                $CustId = $row3['CustId'];
                $sql = "SELECT PumpCapacity FROM tbl_users WHERE id='$CustId'";
                $row = getRecord($sql);
                $PumpCapacity = $row['PumpCapacity'];

                $sql2 = "SELECT DispatchVal FROM tbl_contractor_commision WHERE UserId='$DispatcherId' AND Capacity='$PumpCapacity'";
                $row2 = getRecord($sql2);
                $Amount = $row2['DispatchVal'];

                $sql = "DELETE FROM tbl_made_contractor_commision WHERE CustId='$CustId' AND Roll=3";
                $conn->query($sql);
                
                $sql = "INSERT INTO tbl_made_contractor_commision SET ContractorId='$DispatcherId',CustId='$CustId',Capacity='$PumpCapacity',ScopeOfWork='Material Unloading',Amount='$Amount',CreatedDate='$CreatedDate',Roll=3";
                $conn->query($sql);

                }
              }
            }
        }
}

        /*$Title = "Customer Assign";   
    $Message = "Customer Assign To you for Dispatch Order";
    $sql73 = "SELECT Tokens,id FROM tbl_users WHERE Status='1' AND Tokens!=''";
    $sql73.= " AND id='$DispatcherId'";
      
    $data=mysqli_query($con,$sql73);
        
        while($row=mysqli_fetch_array($data))
        {
            
             $ReceiverId = $row['id'];
             $sql = "INSERT INTO tbl_notifications SET SenderId='$user_id',ReceiverId='$ReceiverId',Title='$Title',Message='$Message',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
            $conn->query($sql);

            $title = $Title;
            $body =  $Message;
            $reg_id = $row[0];
            $registrationIds = array($reg_id);
            //$url = "$SiteUrl/profile.php?id=$UserId";
            include '../incnotification.php';
         
        }*/

        echo "<script>alert('Challan Assign To Contractor');window.location.href='assign-challan-to-dispatcher.php';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Assign Challan For Dispatching To Contractor (Loading-Unloading)
<!-- <span style="float: right;">
<a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New Sell</a></span> -->
</h4>

<div class="card" style="padding: 10px;">
 <form id="validation-form" method="post" enctype="multipart/form-data" action="">   
 
 <?php 
            $i=1;
           if($Roll==1 || $Roll==7){
                $sql = "SELECT ts.*,tb.Name As Branch FROM tbl_sell ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id WHERE ts.Status=1 ";
             }
             else if($Roll==27){
                $sql = "SELECT ts.*,tb.Name As Branch FROM tbl_sell ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id WHERE ts.Status=1 AND ts.BranchId='$BranchId'"; 
             }
             else{
                $sql = "SELECT ts.*,tb.Name As Branch FROM tbl_sell ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id WHERE ts.Status=1 AND ts.CreatedBy='$user_id'"; 
             }
            
        $rncnt = getRow($sql);?>
        <input type="hidden" name="rncnt" value="<?php echo $rncnt;?>">
        
      
        
   <div id="accordion2">
<div class="card mb-2">
                                        
  <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                
<div class="form-row">
 <div class="form-group col-lg-4">
<label class="form-label"> Contractor<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="DispatcherId" id="DispatcherId" required>
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
               <th>Assign To</th>
                <th>DM No</th>
               <th>Store</th>
                <th>Customer Name</th>
                <th>Contact No</th>
                 
                  <th>Total Stock Qty</th>
               <th>Date</th>
                
               
            </tr>
        </thead>
        <tbody>
           <?php 
            $i=1;
             if($Roll==1 || $Roll==7){
                $sql = "SELECT ts.*,tb.Name As Branch,tu.Fname As InchargeName FROM tbl_sell ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON tu.id=ts.ContractorAssignId WHERE ts.Status=1 ";
             }
             else if($Roll==27){
                $sql = "SELECT ts.*,tb.Name As Branch,tu.Fname As InchargeName FROM tbl_sell ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON tu.id=ts.ContractorAssignId WHERE ts.Status=1 AND ts.BranchId='$BranchId'"; 
             }
             else{
                $sql = "SELECT ts.*,tb.Name As Branch,tu.Fname As InchargeName FROM tbl_sell ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON tu.id=ts.ContractorAssignId WHERE ts.Status=1 AND ts.CreatedBy='$user_id'"; 
             }
            
            $sql.=" ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT SUM(Qty) AS TotQty FROM `tbl_sell_products` WHERE SellId='".$row['id']."'";
                $row2 = getRecord($sql2);
                $TotQty = $row2['TotQty'];

                $sql22 = "SELECT * FROM tbl_sell WHERE ContractorAssignStatus=1 AND id='".$row['id']."'";
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
                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?> </td>
                 <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                 <input type="hidden" value="<?php echo $row['id']; ?>" name="QtnId[]">
               <input type="hidden" value="<?php echo $row['Fname']; ?>" name="CustName[]">
               <input type="hidden" value="<?php echo $row['Phone']; ?>" name="CellNo[]">
               <input type="hidden" value="<?php echo $row['Address']; ?>" name="Address[]">

<td><?php echo $row['InchargeName']; ?></td> 
             <td><?php echo $row['InvoiceNo']; ?></td>
               <td><?php echo $row['Branch']; ?></td>
              <td><?php echo $row['CustName']; ?></td>
              <td><?php echo $row['CellNo']; ?></td>
             <td><?php echo $TotQty; ?></td>
               <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
             
              
            </tr>
           <?php $i++;} ?>


           
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

        function featured2(id) {
            if ($('#Check_Id2' + id).prop('checked') == true) {
                $('#CheckId2' + id).val(1);
            } else {
                $('#CheckId2' + id).val(0);
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
