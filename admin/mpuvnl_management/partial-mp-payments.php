<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Customers";
$Page = "Add-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Customer Account
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

<div class="layout-wrapper layout-2">
<div class="layout-inner">

 <?php include_once 'mp-sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once '../top_header.php'; ?>



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Partial Payments Of MPUVNL Customer
</h4><br>

<div class="card" style="padding:10px;">
    <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                


       <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                                <div class="form-row">


                                                  

                                                    <div class="form-group col-md-2">
                                                        <label class="form-label">Pump Capacity </label>
                                                        <select class="form-control" id="PumpCapacity" name="PumpCapacity">
                                                        <option value="all" selected>All</option>
                                                        
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=2 ORDER BY id ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['PumpCapacity']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
                                                    </div>

                                                    <div class="form-group col-md-2">
        <label class="form-label">State <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="StateId" name="StateId">
<option selected="" value="all">All State</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
    </div>

<div class="form-group col-md-2">
        <label class="form-label">Village <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="Village" name="Village">
<option selected="" value="all">All Village</option>
 <?php 
        $q = "select DISTINCT(Village) AS Village from tbl_users WHERE Village!='' AND ProjectType='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['Village']==$rw['Village']){ ?> selected <?php } ?> value="<?php echo $rw['Village']; ?>"><?php echo $rw['Village']; ?></option>
              <?php } ?>
</select>
    </div>
    
    <div class="form-group col-md-2">
        <label class="form-label">District <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="District" name="District">
<option selected="" value="all">All District</option>
 <?php 
        $q = "select DISTINCT(District) AS District from tbl_users WHERE District!='' AND ProjectType='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['District']==$rw['District']){ ?> selected <?php } ?> value="<?php echo $rw['District']; ?>"><?php echo $rw['District']; ?></option>
              <?php } ?>
</select>
    </div>

    
                                                    <input type="hidden" id="Search" value="Search">
                                                    <div class="form-group col-md-1" style="padding-top:25px;">
                                                        <button type="button" onclick="search()" class="btn btn-primary btn-finish">Search</button>
                                                    </div>
                                                    <?php if (isset($_REQUEST['Search'])) { ?>
                                                        <div class="form-group col-md-1">
                                                            <label class="form-label">&nbsp;</label>
                                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                            </form>

 


                                            </div>
                                        </div>
                                    </div>
   </div>

<?php
            // Summary query for balance / paid / total amount analysis (same filters as table)
            $sql_sum = "SELECT 
                    COUNT(ts.id) AS TotalCustomers,
                    SUM(tcm.Amount) AS GrandTotalAmount,
                    SUM(IFNULL(paid.TotalPaid,0)) AS GrandTotalPaid,
                    SUM(tcm.Amount - IFNULL(paid.TotalPaid,0)) AS GrandBalance
                    FROM tbl_users ts
                    LEFT JOIN tbl_common_master tcm ON tcm.id = ts.PumpCapacity
                    LEFT JOIN (SELECT customer_id, SUM(credit) AS TotalPaid FROM tbl_customer_payment_ledger GROUP BY customer_id) paid ON paid.customer_id = ts.id
                    WHERE ts.Status=1 AND ts.ProjectType=1 AND ts.SurveyMatch=0 AND ts.ProjectId=106 AND ts.MpSelectionStatus=1
                    AND (tcm.Amount - IFNULL(paid.TotalPaid,0)) > 0 AND IFNULL(paid.TotalPaid,0) != 0";
            if(!empty($_REQUEST['PumpCapacity']) && $_REQUEST['PumpCapacity']!='all') $sql_sum .= " AND ts.PumpCapacity='".$conn->real_escape_string($_REQUEST['PumpCapacity'])."'";
            if(!empty($_REQUEST['StateId']) && $_REQUEST['StateId']!='all') $sql_sum .= " AND ts.StateId='".$conn->real_escape_string($_REQUEST['StateId'])."'";
            if(!empty($_REQUEST['Village']) && $_REQUEST['Village']!='all') $sql_sum .= " AND ts.Village='".$conn->real_escape_string($_REQUEST['Village'])."'";
            if(!empty($_REQUEST['District']) && $_REQUEST['District']!='all') $sql_sum .= " AND ts.District='".$conn->real_escape_string($_REQUEST['District'])."'";
            $res_sum = $conn->query($sql_sum);
            $sum = $res_sum ? $res_sum->fetch_assoc() : null;
?>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white font-weight-bold">Payment analysis (partial payments)</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <strong>Total Amount</strong><br>
                        <span class="h5 text-dark">₹<?php echo $sum ? number_format($sum['GrandTotalAmount'],2) : '0.00'; ?></span>
                    </div>
                    <div class="col-md-3">
                        <strong>Total Paid</strong><br>
                        <span class="h5 text-success">₹<?php echo $sum ? number_format($sum['GrandTotalPaid'],2) : '0.00'; ?></span>
                    </div>
                    <div class="col-md-3">
                        <strong>Balance</strong><br>
                        <span class="h5 text-danger">₹<?php echo $sum ? number_format($sum['GrandBalance'],2) : '0.00'; ?></span>
                    </div>
                    <div class="col-md-3">
                        <strong>Customers (Partial)</strong><br>
                        <span class="h5"><?php echo $sum ? (int)$sum['TotalCustomers'] : 0; ?></span>
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
              
              
                <th>Beneficiary ID</th>
                <th>Customer Name</th>
                <th>Contact No</th>
                <th>Pump Capacity</th>
                <th>State</th>
                <th>Village</th>
                <th>District</th>
               <th>Total Amount</th>
<th>Total Paid</th>
<th>Balance</th>
<th>Status</th>
<th>Receive Payment</th>

                
               
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
           
            $sql = "SELECT 
ts.*, 
tcm.Name AS Pump_Capacity,
tcm.Amount AS PumpAmt,
ts2.Name AS StateName,
IFNULL(SUM(l.credit),0) AS TotalPaid,
(tcm.Amount - IFNULL(SUM(l.credit),0)) AS BalanceAmt

FROM tbl_users ts
LEFT JOIN tbl_common_master tcm ON tcm.id = ts.PumpCapacity
LEFT JOIN tbl_state ts2 ON ts2.id = ts.StateId
LEFT JOIN tbl_customer_payment_ledger l ON l.customer_id = ts.id

WHERE ts.Status=1 
AND ts.ProjectType=1 

AND ts.ProjectId=106 
AND ts.MpSelectionStatus=1
";

            
            if($_REQUEST['CoordinatorStatus']!=''){
                if($_REQUEST['CoordinatorStatus'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND ts.CoordinatorStatus='".$_REQUEST['CoordinatorStatus']."'";
                }
            }

            if($_REQUEST['PumpCapacity']!=''){
                if($_REQUEST['PumpCapacity'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND ts.PumpCapacity='".$_REQUEST['PumpCapacity']."'";
                }
            }
            if($_REQUEST['StateId']!=''){
                if($_REQUEST['StateId'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND ts.StateId='".$_REQUEST['StateId']."'";
                }
            }
            if($_REQUEST['Village']!=''){
                if($_REQUEST['Village'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND ts.Village='".$_REQUEST['Village']."'";
                }
            }
            if($_REQUEST['District']!=''){
                if($_REQUEST['District'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND ts.District='".$_REQUEST['District']."'";
                }
            }
            $sql .= " GROUP BY ts.id ORDER BY ts.id DESC";

            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
                
if($row['BalanceAmt'] > 0 && $row['TotalPaid']!=0){ ?>
<tr style="<?php echo $bcolor;?>">

    <td><?php echo $row['BeneficiaryId']; ?></td>
    <td><?php echo $row['Fname']; ?></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><?php echo $row['Pump_Capacity']; ?></td>
    <td><?php echo $row['StateName']; ?></td>
    <td><?php echo $row['Village']; ?></td>
    <td><?php echo $row['District']; ?></td>

    <td>₹<?php echo number_format($row['PumpAmt'],2); ?></td>
    <td>₹<?php echo number_format($row['TotalPaid'],2); ?></td>
    <td><strong class="text-danger">₹<?php echo number_format($row['BalanceAmt'],2); ?></strong></td>

    <td>
        <?php
        if($row['TotalPaid'] == 0){
            echo "<span class='badge badge-danger'>Pending</span>";
        } else {
            echo "<span class='badge badge-warning'>Partial</span>";
        }
        ?>
    </td>

    <td>
        <button type="button" class="btn btn-success"
        onclick="openPaymentModal(
        '<?php echo $row['id']; ?>',
        '<?php echo $row['PumpCapacity']; ?>',
        '<?php echo $row['Pump_Capacity'];?>',
        '<?php echo $row['BalanceAmt']; ?>'
        )">
        Pay
        </button>
    </td>

</tr>
<?php $i++; }} ?>

        </tbody>
    </table>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="paymentForm">
        <div class="modal-header">
          <h5 class="modal-title">Receive Customer Payment</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="customer_id" id="customer_id">
          <input type="hidden" name="pump_capacity_id" id="pump_capacity_id">

          <div class="form-group">
            <label>Pump Capacity</label>
            <input type="text" id="pump_capacity_name" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Total Amount</label>
            <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Total Paid Now</label>
            <input type="number" step="0.01" name="total_paid" id="total_paid" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Balance</label>
            <input type="number" step="0.01" name="balance_amount" id="balance_amount" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Payment Date</label>
            <input type="date" name="payment_date" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Payment Type</label>
            <select name="payment_type" class="form-control" required>
              <option value="Cash">Cash</option>
              <option value="UPI">UPI</option>
              <option value="Bank Transfer">Bank Transfer</option>
              <option value="Cheque">Cheque</option>
            </select>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit Payment</button>
        </div>
      </form>
    </div>
  </div>
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
function openPaymentModal(customerId, pumpCapacityId,pumpCapacityVal, totalAmt) {
    $('#customer_id').val(customerId);
    $('#pump_capacity_id').val(pumpCapacityId);
    $('#pump_capacity_name').val(pumpCapacityVal);
    $('#total_amount').val(totalAmt);
    $('#balance_amount').val(totalAmt);
    $('#paymentModal').modal('show');
}

$('#total_paid').on('input', function(){
    var total = parseFloat($('#total_amount').val()) || 0;
    var paid = parseFloat($(this).val()) || 0;
    $('#balance_amount').val((total - paid).toFixed(2));
});

$(document).ready(function () {

    $('#paymentForm').on('submit', function(e){
        e.preventDefault();
        //alert("Form Submit Triggered"); // test alert

        $.ajax({
            url: 'save_customer_payment.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                alert("Payment Saved Successfully");
                $('#paymentModal').modal('hide');
                location.reload();
            },
            error: function(xhr){
                alert("AJAX Error: " + xhr.responseText);
            }
        });
    });

});



function search(){
    var PumpCapacity = $('#PumpCapacity').val();
    var StateId = $('#StateId').val();
    var District = $('#District').val();
    var Village = $('#Village').val();
    var Search = $('#Search').val();
    var CoordinatorStatus = $('#CoordinatorStatus').val();
    window.location.href="pending-mp-payments.php?PumpCapacity="+PumpCapacity+"&StateId="+StateId+"&District="+District+"&Village="+Village+"&Search="+Search+"&CoordinatorStatus="+CoordinatorStatus;
}
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
