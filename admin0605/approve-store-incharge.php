<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Approve-Store-Incharge";
$Page = "Approve-Store-Incharge";
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
    
    $rncnt = $_POST['rncnt'];
    $rncnt2 = $_POST['rncnt2'];
  
    
    if($rncnt > 0){
        $number = count($_POST['CheckId']);
   $StoreInchId = $_POST['StoreInchId'];
   $CreatedDate = date('Y-m-d H:i:s');
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                $QtnId = addslashes(trim($_POST['QtnId'][$i]));
                $sql = "UPDATE tbl_quotation SET ApproveStatus='1',ApproveBy='$user_id',ApproveDate='$CreatedDate' WHERE id='$QtnId'";
                $conn->query($sql);

                }
              }
            }
        }
    }

if($rncnt2 > 0){
         $number2 = count($_POST['CheckId2']);

   $StoreInchId = $_POST['StoreInchId'];
   $CreatedDate = date('Y-m-d H:i:s');
    if($number2 > 0)  
      {  
        for($i2=0; $i2<$number2; $i2++)  
          {  
            if(trim($_POST["CheckId2"][$i2] != ''))  
              {
                $CheckId2 = addslashes(trim($_POST['CheckId2'][$i2]));
                if($CheckId2 == 1){
                $QtnId = addslashes(trim($_POST['QtnId2'][$i2]));
                $sql = "UPDATE tbl_users SET ApproveStatus='1',ApproveBy='$user_id',ApproveDate='$CreatedDate' WHERE id='$QtnId'";
                $conn->query($sql);

                }
              }
            }
        }
}
        
        echo "<script>alert('Approve By Store Incharge');window.location.href='approve-store-incharge.php';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Approve By Store Incharge
<!-- <span style="float: right;">
<a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New Sell</a></span> -->
</h4>

<div class="card" style="padding: 10px;">
 <form id="validation-form" method="post" enctype="multipart/form-data" action="">  
 
        <?php 
            $i=1;
            if($Roll==1 || $Roll==7){
            $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_quotation tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.StoreInchId 
                    WHERE tp.PaidStatus=1 AND tp.StoreInchStatus=1
            
            ORDER BY tp.CreatedDate DESC";
        }
        else{
            $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_quotation tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.StoreInchId 
                    WHERE tp.PaidStatus=1 AND tp.StoreInchStatus=1 AND tp.StoreInchId='$user_id'
            ORDER BY tp.CreatedDate DESC";
        }
            $rncnt = getRow($sql);?>
        <input type="hidden" name="rncnt" value="<?php echo $rncnt;?>">
        
         <?php 
            $i=1;
            if($Roll==1 || $Roll==7){
            $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_users tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.StoreInchId 
                    WHERE tp.StoreInchStatus=1 AND tp.ProjectType=1
            
            ORDER BY tp.CreatedDate DESC";
        }
        else{
            $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_users tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.StoreInchId 
                    WHERE tp.StoreInchStatus=1 AND tp.StoreInchId='$user_id' AND tp.ProjectType=1
            ORDER BY tp.CreatedDate DESC";
        }
        $rncnt2 = getRow($sql);?>
        <input type="hidden" name="rncnt2" value="<?php echo $rncnt2;?>">
        
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <th>Assign To</th>
                <th>Customer Name</th> 
                <th>Contact No</th>
                <th>Address</th>
                <th>QTN NO</th>
                <th>QTN Date</th>
                <th>Paid Status</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Bal Amount</th>
                <th>Paid Date</th>
                <th>Paid By</th>
              
                
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            if($Roll==1 || $Roll==7){
            $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_quotation tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.StoreInchId 
                    WHERE tp.PaidStatus=1 AND tp.StoreInchStatus=1
            
            ORDER BY tp.CreatedDate DESC";
        }
        else{
            $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_quotation tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.StoreInchId 
                    WHERE tp.PaidStatus=1 AND tp.StoreInchStatus=1 AND tp.StoreInchId='$user_id'
            ORDER BY tp.CreatedDate DESC";
        }
           
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
               $sql22 = "SELECT * FROM tbl_quotation WHERE ApproveStatus=1 AND id='".$row['id']."'";
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
               <td><?php echo $row['CustName']; ?></td> 
              
                <td><?php echo $row['CellNo']; ?></td>
                <td><?php echo $row['Address']; ?></td>
                <td><?php echo $row['InvoiceNo']; ?></td>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
                <td><?php if($row['PaidStatus']=='1'){echo "<span style='color:green;'>Paid</span>";} else { echo "<span style='color:red;'>Not Paid</span>";} ?></td>
                <td>&#8377;<?php echo number_format($row["TotalAmt"],2); ?></td>
                <td>&#8377;<?php echo number_format($row["PaidAmt"],2); ?></td>
                <td>&#8377;<?php echo number_format($row["TotalAmt"]-$row["PaidAmt"],2); ?></td>
                <td><?php if($row['PaidStatus']=='1'){ echo date("d/m/Y", strtotime(str_replace('-', '/',$row['PaidDate']))); } ?></td>
                <td><?php echo $row['PayMode']; ?></td>
             
              
            </tr>
           <?php $i++;} ?>


           <?php 
            $i=1;
            if($Roll==1 || $Roll==7){
            $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_users tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.StoreInchId 
                    WHERE tp.StoreInchStatus=1 AND tp.ProjectType=1
            
            ORDER BY tp.CreatedDate DESC";
        }
        else{
            $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_users tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.StoreInchId 
                    WHERE tp.StoreInchStatus=1 AND tp.StoreInchId='$user_id' AND tp.ProjectType=1
            ORDER BY tp.CreatedDate DESC";
        }
        
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
               $sql22 = "SELECT * FROM tbl_users WHERE ApproveStatus=1 AND id='".$row['id']."'";
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
                    <input type="checkbox" id="Check_Id2<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured2(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?> </td>
                 <input type="hidden" value="0" name="CheckId2[]" id="CheckId2<?php echo $row['id']; ?>">
                 <input type="hidden" value="<?php echo $row['id']; ?>" name="QtnId2[]">
               <input type="hidden" value="<?php echo $row['Fname']; ?>" name="CustName[]">
               <input type="hidden" value="<?php echo $row['Phone']; ?>" name="CellNo[]">
               <input type="hidden" value="<?php echo $row['Address']; ?>" name="Address[]">

                <td><?php echo $row['InchargeName']; ?></td> 
                <td><?php echo $row['Fname']; ?></td>               
                <td><?php echo $row['Phone']; ?></td>
                <td><?php echo $row['Address']; ?></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
             
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>

<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Approve</button>
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
