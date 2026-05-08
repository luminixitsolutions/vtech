<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Bill-Amount-Status";
$Page = "Bill-Amount-Status";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Bill-Amount-Status</title>
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



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Bill Amount Status Lists
   
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
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
                <th>Pay</th>
                <th>Pay Balance</th>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.* FROM tbl_rooftop_quotation tp 
            
            ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $BalAmt = $row["TotalAmt"]-$row["PaidAmt"];
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
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
                 <?php if($BalAmt>0){?>
                <td>
        <?php if($row['PaidStatus']=='1'){?>
        <a href="pay-amount.php?qid=<?php echo $row['id']; ?>" class="btn btn-success btn-finish">Update</a>
        <?php } else {?>
                    <a href="pay-amount.php?qid=<?php echo $row['id']; ?>" class="btn btn-primary btn-finish">Pay</a>
                    <?php } ?>
                    </td>
                <td>
                    <?php if($row['PaidStatus']=='1'){?>
                    <a href="pay-bal-amount.php?qid=<?php echo $row['id']; ?>" class="btn btn-primary btn-finish">Pay Balance</a></td>
                <?php } else {} } else { ?>
                <td>
        
                    <a href="#" class="btn btn-success btn-finish" disabled>Paid</a>
                    </td>
                <td></td>
                <?php } ?>
        
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
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
    $('#example').DataTable({
    });
});
</script>
</body>
</html>
