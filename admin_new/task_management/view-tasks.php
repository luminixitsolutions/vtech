<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Task";
$Page = "View-Task";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Task List</title>
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

<?php include_once 'task-sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once '../top_header.php'; ?>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_tasks WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-tasks.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Task List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="create-task.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?>
</h4>

<div class="card" style="padding: 10px;">

     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

 <div class="form-group col-md-3">
                                                    <label class="form-label">Task Status <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="ClainStatus" name="ClainStatus" required="">
                                                        <option value="all" selected>All</option>
                                                        <option value="Pending" <?php if ($_REQUEST["ClainStatus"] == 'Pending') { ?> selected
                                                            <?php } ?>>Pending</option>
                                                            
                                                        <option value="In Process" <?php if ($_REQUEST["ClainStatus"] == 'In Process') { ?> selected
                                                            <?php } ?>>In Process</option>
                                                        
                                                        <option value="Closed" <?php if ($_REQUEST["ClainStatus"] == 'Closed') { ?> selected
                                                            <?php } ?>>Completed/Closed</option>
                                                        
                                                        <option value="Cancelled" <?php if ($_REQUEST["ClainStatus"] == 'Cancelled') { ?> selected
                                                            <?php } ?>>Cancelled</option>
                                                    </select>
                                                    <div class="clearfix"></div>
                                                </div>
                                                
                                                 <div class="form-group col-md-3">
                                                    <label class="form-label">Project Head / Department <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="DeptId" name="DeptId" required="">
                                                        <option value="all" selected>All</option>
                                                       <?php 
  $sql12 = "SELECT * FROM tbl_department WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["DeptId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
                                                    </select>
                                                    <div class="clearfix"></div>
                                                </div>
                                                
<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1">
    <label class="form-label">&nbsp;</label>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
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

<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
              <th>Project Head / Department</th> 
                <th>Task</th> 
                <th>Task Date</th> 
                 <th>Task Due Date</th> 
                 <th>Allocate To Employee</th> 
                 <th>Closing Task To Employee</th> 
                <th>Task Status</th> 
              
                 <?php if(in_array("10", $Options) || in_array("11", $Options)) {
                    if($Roll == 1){
                 ?>
               <th>Action</th>
               <?php } } ?>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tc.Fname,td.Name FROM tbl_tasks tp 
            LEFT JOIN tbl_users tc ON tc.id=tp.CloseEmpId 
            LEFT JOIN tbl_department td ON td.id=tp.DeptId WHERE tp.Status=1";
            if($Roll != 1){
                $sql.=" AND FIND_IN_SET('$user_id', tp.ExeId) > 0";
            }
            if($_REQUEST['ClainStatus']){
                $ClainStatus = $_REQUEST['ClainStatus'];
                if($ClainStatus == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tp.ClainStatus='$ClainStatus'";
                }
            }
            if($_REQUEST['DeptId']){
                $DeptId = $_REQUEST['DeptId'];
                if($DeptId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tp.DeptId='$DeptId'";
                }
            }
           if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND tp.TaskDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND tp.TaskDate<='$ToDate'";
            }
            $sql.= " ORDER BY tp.id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $ExeId = $row['ExeId'];
                if($ExeId!=''){
                    $sql2 = "SELECT GROUP_CONCAT(Fname SEPARATOR ', ') AS AllocateEmpName FROM tbl_users WHERE id IN ($ExeId)";
                    $row2 = getRecord($sql2);
                    $AllocateEmpName = ucwords(strtolower($row2['AllocateEmpName']));
                }
                else{
                    $AllocateEmpName = "NA";
                }
               
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['Name']; ?></td> 
              <td><?php echo $row['TaskDetails']; ?></td> 
              <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['TaskDate']))); ?></td>
              <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['DueDate']))); ?></td>
              <td><?php echo $AllocateEmpName;?></td>
              <td><?php echo ucwords(strtolower($row['Fname']));?></td>
                 <!--<td><?php if($row['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>-->
               
           <td><?php echo $row['ClainStatus'];?></td>
           <?php if(in_array("10", $Options) || in_array("11", $Options)) {
           if($Roll == 1){?>
            <td>
              <?php if(in_array("10", $Options)){?>
              <a href="create-task.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){
               
                ?>
              &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a>
             <?php } ?>
            </td> <?php } } ?>
        
              
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
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX":true
    });
});
</script>
</body>
</html>
