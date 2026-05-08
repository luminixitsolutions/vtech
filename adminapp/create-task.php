<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Expenses";
$Page = "Add-Expenses";

?>
<!doctype html>
<html lang="en" class="h-100">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

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

<style>
    .custom-control {
  line-height: 24px;
  padding-top: 5px;
}
</style>
<style>
            .dataTables_filter, .dataTables_info { display: none; }
        </style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">







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
<h4 class="font-weight-bold py-3 mb-0">Create Task</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
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
 <script>

  $(document).ready(function() {
           $(document).on("change", "#CatId", function(event) {
            var val = this.value;
            var action = "getBrands";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#BrandId').html(data);
                  
                }
            });

        });

            
            });

        //CKEDITOR.replace( 'editor1');
</script>
</body>
</html>