<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Dispatch-Officer";
$Page = "Assign-Dispatch-Officer";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Sell List</title>
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
        

        <div class="main-container">








<?php

if(isset($_POST['submit'])){

   $number = count($_POST['CheckId']);

   $DispatchOfficerId = $_POST['DispatchOfficerId'];
   $CreatedDate = date('Y-m-d H:i:s');
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                $PoId = addslashes(trim($_POST['PoId'][$i]));
                $sql = "UPDATE tbl_purchase_order SET DispatchOfficerStatus='1',DispatchOfficerId='$CoordinatorId',DispatchOfficerDate='$CreatedDate' WHERE id='$PoId'";
                $conn->query($sql);

                }
              }
            }
        }

        echo "<script>alert('Order Assign To Dispatch Order');window.location.href='assign-to-dispatch-officer.php';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Assign To Dispatch Officer
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
<label class="form-label"> Dispatch Officer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="DispatchOfficerId" id="DispatchOfficerId" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=26";
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
                <th>Print</th>
                <th>Order Track</th>
                <!-- <th>Branch</th>  -->
                <th>Company Name</th>
                <th>Manufacture Name</th>
                <th>Contact No</th>
               
                <th>Date</th>
                <th>Gross Amt </th>
                <!--<th>CGST Amt </th>
                <th>SGST Amt </th>
                <th>IGST Amt </th>-->
                <th>Sub Total </th>
              <!--  <th>Less URD</th>-->
                <th>Discount</th>
                <th>Net Payable</th>
                <th>Delivery Date</th>
             
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.*,tb.Name As Branch,tu.Fname As CompanyName FROM tbl_purchase_order ts LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id
                     LEFT JOIN tbl_users tu ON ts.CompId=tu.id WHERE ts.Status=1 AND ts.DeliveredStatus=1";
             if($_POST['CustId']){
                $CustId = $_POST['CustId'];
                if($CustId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.CustId='$CustId'";
                }
            }

            if($_POST['BranchId']){
                $BranchId = $_POST['BranchId'];
                if($BranchId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.BranchId='$BranchId'";
                }
            }
             if($_POST['CompId']){
                $CompId = $_POST['CompId'];
                if($CompId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.CompId='$CompId'";
                }
            }

            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ts.InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND ts.InvoiceDate<='$ToDate'";
            }
            $sql.=" ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                // $sql2 = "SELECT SUM(PaidAmt) AS PaidAmt FROM tbl_general_ledger WHERE SellId='".$row['id']."' AND Type='PR'";
                // $row2 = getRecord($sql2);
                // $PaidAmt = $row2['PaidAmt'];
                if($row['DeliveredStatus'] == 1){
                    $Status = "Order Received";
                }
                else if($row['ReceiveStatus'] == 1){
                    $Status = "Order Received";
                }
                else if($row['SendStatus'] == 1){
                    $Status = "Order Sent";
                }
                else if($row['ApplyStatus'] == 1){
                    $Status = "Order Applied";
                }
                else{
                    $Status = "Apply Order";
                }

                $sql22 = "SELECT * FROM tbl_purchase_order WHERE DispatchOfficerStatus=1 AND id='".$row['id']."'";
                $rncnt22 = getRow($sql22);
                if($rncnt22 > 0){
                     $bcolor = "background-color: #b9efb9;";
                }
                else{
                    $bcolor = "";
                }
             ?>
            <tr style="<?php echo $bcolor;?>">
               <td> <?php if($rncnt22 > 0){} else{?><label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?></td>
                 <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                   <input type="hidden" value="<?php echo $row['id']; ?>" name="PoId[]">
                <td><a href="invoice.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $row['InvoiceNo']; ?></a></td>
                <td><?php echo $Status;?></td>
 <!-- <td><?php echo $row['Branch']; ?></td> -->
  <td><?php echo $row['CompanyName']; ?></td>
              <td><?php echo $row['CustName']; ?></td>
              <td><?php echo $row['CellNo']; ?></td>
              
               <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
                <td>&#8377;<?php echo $row['GrossAmt']; ?></td>
              <!--  <td>&#8377;<?php echo $row['CgstAmt']; ?></td>
             <td>&#8377;<?php echo $row['SgstAmt']; ?></td>
             <td>&#8377;<?php echo $row['IgstAmt']; ?></td>-->
             <td>&#8377;<?php echo $row['SubTotal']; ?></td>
            <!-- <td>&#8377;<?php echo $row['UcdAmt']; ?></td>-->
             <td>&#8377;<?php echo $row['Discount']; ?></td>
             <td>&#8377;<?php echo $row['Total']; ?></td>
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['DeliveryDate']))); ?></td>
            
          
            
              
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
