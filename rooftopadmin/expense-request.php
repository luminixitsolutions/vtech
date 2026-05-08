<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Expense-Request";
$Page = "Expense-Request";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> </title>
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
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_expenses WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="expense-request.php";
    </script>
<?php } 

if($_REQUEST['action'] == 'changestatus'){
    $id = $_REQUEST["id"];
    $val = $_REQUEST["val"];
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');
    $sql3 = "SELECT * FROM tbl_expenses WHERE id = '$id'";
    $row3 = getRecord($sql3);
    $UserId = $row3['UserId'];
    $Amount = $row3['Amount'];
    if($val == 0){
        $sql = "UPDATE tbl_expenses SET Status=1 WHERE id='$id'";
        $conn->query($sql);
        $sql2 = "INSERT INTO wallet SET ExpId='$id',UserId='$UserId',Amount='$Amount',Narration='Expense Amount Approved',Status='Dr',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
        $conn->query($sql2);
    }
    else{
        $sql = "UPDATE tbl_expenses SET Status=0 WHERE id='$id'";
        $conn->query($sql);
        $sql2 = "DELETE FROM wallet WHERE ExpId='$id'";
        $conn->query($sql2);
    }
 ?>
    <script type="text/javascript">
      alert("Record Saved Successfully");
      window.location.href="expense-request.php";
    </script>
<?php   
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Expense Request
  
</h4>

<div class="card" style="padding: 10px;">
    <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">




<div class="form-group col-md-3">
<label class="form-label"> Employee<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="CreatedBy" id="CreatedBy">
    <option selected="" value="all">All</option>
    <?php
        
        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll NOT IN(1,3,4,5,9,10,8,11,34,35,36,37)";
       
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["CreatedBy"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo $result['Fname']; ?></option>
        <?php } ?>
</select>

<div class="clearfix"></div>
</div> 

<div class="form-group col-md-2">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Status" name="Status" required="">
                                               <option selected="" value="all">All</option>
                                                <option value="1" <?php if($_REQUEST["Status"]=='1') {?> selected
                                                    <?php } ?>>Approved</option>
                                                <option value="0" <?php if($_REQUEST["Status"]=='0') {?> selected
                                                    <?php } ?>>Pending</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

<div class="form-group col-md-3">
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
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Photo</th>
                <th>Employee Name</th>
                
                <th>Amount</th>
                <th>PaymentMode</th>
                <th>Narration</th>
                 <th>Receipt</th>
                <th>Status</th>
                <th>Expense Date</th>
               
                 <th>Action</th>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql = "SELECT te.*,tu.Fname,tu.Lname,tu.Photo AS Uphoto FROM tbl_expenses te LEFT JOIN tbl_users tu ON tu.id=te.CreatedBy WHERE 1";
            
            if($_POST['CreatedBy']){
                $CreatedBy = $_POST['CreatedBy'];
                if($CreatedBy == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND te.CreatedBy='$CreatedBy'";
                }
            }
            
            if($_POST['Status']){
                $Status = $_POST['Status'];
                if($Status == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND te.Status='$Status'";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND te.CreatedDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND te.CreatedDate<='$ToDate'";
            }
            $sql.= " ORDER BY te.CreatedDate DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td> <?php if($row["Uphoto"] == '') {?>
                  <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Uphoto"])){?>
                 <img src="../uploads/<?php echo $row["Uphoto"];?>" class="d-block ui-w-40 rounded-circle" alt="" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle" style="width: 40px;height: 40px;"> 
             <?php } ?></td>
               <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
              
                
                <td><?php echo $row['Amount']; ?></td>
               <td><?php echo $row['PaymentMode']; ?></td>
                  <td><?php echo $row['Narration']; ?></td>
              <td><?php if($row["Photo"] == '') {?>
                  <span style="color:red;">No Receipt Found</span>
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <a href="../uploads/<?php echo $row["Photo"];?>" target="_blank">View Receipt</a>
                  <?php }  else{?>
                <span style="color:red;">No Receipt Found</span>
             <?php } ?></td>
                     
                 <td><?php if($row['Status']=='1'){echo "<span style='color:green;'>Approved</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>
              
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['ExpenseDate']))); ?></td>
            <td><label class="switcher switcher-success">
                                        <input type="checkbox" class="switcher-input" onchange="changeStatus(<?php echo $row['id']; ?>,<?php echo $row['Status']; ?>)" value="<?php echo $row['Status']; ?>" <?php if($row['Status']=='1'){?> checked="" <?php } ?>>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">Approve/Pendig</span>
                                    </label></td>
            </tr>
           <?php } ?>
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
 function changeStatus(id,val){
     window.location.href='expense-request.php?action=changestatus&id='+id+'&status='+val;
 }
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
