<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Stock-Report2";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Expense Report</title>
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


<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_expenses WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-expenses.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Expense Report
   
</h4>

<div class="card" style="padding: 10px;">

      <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       

  <div class="form-group col-md-3">
<label class="form-label">Branch</label>
 <select class="select2-demo form-control" name="BranchId" id="BranchId">
  <?php 
 if($Roll == 1 || $Roll == 7){?>
<option selected="" value="all">All</option>
 <?php }
 if($Roll == 1 || $Roll == 7){
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
}
else{
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id='$BranchId'";
}
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["BranchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label"> Account Head<span class="text-danger">*</span></label>
 <select class="form-control" name="AccHeadId" id="AccHeadId" required>
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_account_head WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["AccHeadId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>


<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
                <th>Branch</th> 
               <th>Account Head</th> 
               
                <th>Amount</th>
                <th>Date</th>
                <th>Payment Mode</th>
            <th>Narration</th>
               
                
               
                
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tb.Name As BranchName,tah.Name As AccountHead FROM tbl_expenses tp 
                    LEFT JOIN tbl_branch tb ON tb.id=tp.BranchId 
                    LEFT JOIN tbl_account_head tah ON tah.id=tp.AccHeadId WHERE tp.Status=1 ";

            if($_POST['BranchId']){
                $BranchId = $_POST['BranchId'];
                if($BranchId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tp.BranchId='$BranchId'";
                }
            }
            if($_POST['AccHeadId']){
                $AccHeadId = $_POST['AccHeadId'];
                if($AccHeadId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tp.AccHeadId='$AccHeadId'";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND tp.ExpenseDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND tp.ExpenseDate<='$ToDate'";
            }
            $sql.="
            ORDER BY tp.ExpenseDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $TotalAmt+=$row['Amount'];
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['BranchName']; ?></td> 
              
               
                  <td><?php echo $row['AccountHead']; ?></td>
              
                    
                <td><?php echo $row['Amount']; ?></td>
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['ExpenseDate']))); ?></td>
            <td><?php echo $row['PaymentMode']; ?></td>
            <td><?php echo $row['Narration']; ?></td>
          
        
              
            </tr>
           <?php $i++;} ?>

           <tr>
                <td><?php echo $i;?></td>
                <td></td>
                <th>Total</th>
                <th><?php echo $TotalAmt;?></th>
                <td></td>
                <td></td>
                <td></td>
                
           </tr>
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
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        "pageLength":500,
        dom: 'Bfrtip',
        order: [[0, 'asc']],
        buttons: [
            'excelHtml5'
        ]
    });

 $(document).on("change", "#ModelNo", function(event) {
            var val = this.value;
            var action = "getModelNo";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#ProductNo').html(data);
                  
                }
            });

        });
    
});
</script>
</body>
</html>
