<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Reports";
$Page = "Sell-Reports";
$sql = "SELECT Fname FROM tbl_users WHERE id='".$_GET['id']."'";
$row = getRecord($sql);
$ContractorName = $row['Fname'];
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?>_<?php echo $ContractorName;?>_commission_report</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once '../header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'report-sidebar.php'; ?>


<div class="layout-container">

<?php include_once '../top_header.php'; ?>


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0"><?php echo $ContractorName;?> Commission Report</h4>

<div class="card" style="padding: 10px;">
    
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
              <th>Beneficiary ID</th>
                <th>Customer Name</th>
                <th>Scope Of Work</th>
                <th>Amount</th>
                <th>Date</th>
               
            
              
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $UserId = $_GET['id'];
            $sql = "SELECT tc.*,tu.Fname,tu.BeneficiaryId FROM tbl_made_contractor_commision tc INNER JOIN tbl_users tu On tu.id=tc.CustId WHERE tc.ContractorId='$UserId' ORDER BY tc.id DESC 
                    ";
             
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td><?php echo $i; ?></td>
               <td><?php echo $row['BeneficiaryId']; ?></td>
              <td><?php echo $row['Fname']; ?></td>
            
              <td><?php echo $row['ScopeOfWork']; ?></td>
               <td><?php echo number_format($row['Amount'],2); ?></td>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate'])))?></td>
              
            </tr>
           <?php $i++;} ?>

          
        </tbody>
    </table>
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

<script type="text/javascript">
  function receiptPrint(id){
     setTimeout(function() {
        window.open(
            'receipt.php?id=' + id + '&roll=vendor', 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=800'
        );
    }, 1);
 }
    $(document).ready(function() {
    $('#example').DataTable({
       "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'pdfHtml5'
        ]
    });
});
</script>
</body>
</html>
