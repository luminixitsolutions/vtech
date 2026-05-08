<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Task";
$Page = "Add-Task";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add <?php } ?> Raw Stock
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
    <style type="text/css">
        .password-tog-info {
            display: inline-block;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            position: absolute;
            right: 50px;
            top: 30px;
            text-transform: uppercase;
            z-index: 2;
        }
    </style>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'task-sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once '../top_header.php'; ?>

                <?php
                $id = $_GET['id'];
                $sql7 = "SELECT * FROM tbl_tasks WHERE id='$id'";
                $row7 = getRecord($sql7);
                $row7['ExeId'] = explode(',', $row7['ExeId']);

                if (isset($_POST['submit'])) {
                    $DeptId = addslashes(trim($_POST["DeptId"]));
                    $CloseEmpId = addslashes(trim($_POST["CloseEmpId"]));
                    $TaskDate = addslashes(trim($_POST["TaskDate"]));
                    $TaskName = addslashes(trim($_POST["TaskName"]));
                    $TaskDetails = addslashes(trim($_POST["TaskDetails"]));
                    $DueDate = addslashes(trim($_POST["DueDate"]));
                    $Status = addslashes(trim($_POST["Status"]));
                    $CreatedDate = date('Y-m-d');
                    $ModifiedDate = date('Y-m-d');
                    if($_POST['ExeId']!=''){
                    $ExeId = implode(",", $_POST['ExeId']);
                    }
                    else{
                       $ExeId = 0; 
                    }


                    if ($_GET['id'] == '') {
                        $qx = "INSERT INTO tbl_tasks SET DeptId='$DeptId',ClainStatus='Pending',ExeId='$ExeId',TaskDate='$TaskDate',TaskName = '$TaskName',Status='$Status',CreatedDate='$CreatedDate',CreatedBy='$user_id',TaskDetails='$TaskDetails',DueDate='$DueDate',CloseEmpId='$CloseEmpId'";
                        $conn->query($qx);
                        echo "<script>alert('Task Created Successfully!');window.location.href='view-tasks.php';</script>";
                    } else {
                        //$TicketNo= "#".rand(1000,9999);
                        $query2 = "UPDATE tbl_tasks SET DeptId='$DeptId',ExeId='$ExeId',TaskDate='$TaskDate',TaskName = '$TaskName',Status='$Status',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',TaskDetails='$TaskDetails',DueDate='$DueDate',CloseEmpId='$CloseEmpId' WHERE id = '$id'";
                        $conn->query($query2);
                        echo "<script>alert('Task Updated Successfully!');window.location.href='view-tasks.php';</script>";
                    }
                    //header('Location:courses.php'); 

                }
                ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if ($_GET['id']) { ?>Edit <?php } else { ?> Create
                        <?php } ?> Task</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="validation-form" method="post" autocomplete="off">
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <div id="alert_message"></div>

                                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                            <input type="hidden" name="action" value="Save" id="action">
                                            <div class="form-row">

<div class="form-group col-md-12">
<label class="form-label"> Project Head / Department <span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="DeptId" id="DeptId" required  style="width: 100%">
     <option value="" selected>Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_department WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["DeptId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

                                                <div class="form-group col-md-12">
                                                    <label class="form-label">Task Details <span class="text-danger">*</span></label>
                                                    <textarea name="TaskDetails" class="form-control"
                                                        placeholder="" required><?php echo $row7["TaskDetails"]; ?></textarea>
                                                    <div class="clearfix"></div>
                                                </div>

  <div class="form-group col-md-12">
<label class="form-label"> Allocate Task To Employees<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ExeId[]" id="ExeId" required  multiple style="width: 100%">
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll NOT IN(1,3,4,5,9,10,8,11,34,35,36,37,39,40,26,27,42)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if (in_array($result["id"], $row7['ExeId'])) { ?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label"> Closing Task To Employee <span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CloseEmpId" id="CloseEmpId" required  style="width: 100%">
     <option value="" selected>Select Employee</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll NOT IN(1,3,4,5,9,10,8,11,34,35,36,37,39,40,26,27,42)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CloseEmpId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Task Date <span class="text-danger">*</span></label>
                                                    <input type="date" name="TaskDate" class="form-control"
                                                        placeholder="" value="<?php echo $row7["TaskDate"]; ?>" required>
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Task Due Date <span class="text-danger">*</span></label>
                                                    <input type="date" name="DueDate" class="form-control"
                                                        placeholder="" value="<?php echo $row7["DueDate"]; ?>" required>
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="Status" name="Status" required="">

                                                        <option value="1" <?php if ($row7["Status"] == '1') { ?> selected
                                                            <?php } ?>>Active</option>
                                                        <option value="0" <?php if ($row7["Status"] == '0') { ?> selected
                                                            <?php } ?>>Inctive</option>
                                                    </select>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-2">
                                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </form>
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
</body>
</html>