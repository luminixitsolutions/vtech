<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Attendance";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId']; ?>
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
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_tasks WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-task.php";
    </script>
<?php } ?>


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">My Task
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="create-task.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add</a></span><?php } ?>
</h4>


<form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                                <div class="form-row">
  <div class="form-group col-md-3 col-8">
                                                        <label class="form-label">Project Head / Department </label>
                                                       <select class="form-control" name="DeptId" id="DeptId" required  style="width: 100%">
     <option value="" selected>Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_department WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["DeptId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
                                                    </div>
                                                    
                                                    <input type="hidden" name="Search" value="Search">
                                                    <div class="form-group col-md-3 col-3" style="padding-top: 32px;">
                                                        <button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
                                                    </div>
                                                     </div>
                                                     </form>
                



<?php
                                       $i=1;
                            $sql = "SELECT tp.*,td.Name FROM tbl_tasks tp 
                            LEFT JOIN tbl_department td ON td.id=tp.DeptId WHERE tp.Status=1";
                            if($Roll != 1){
                $sql.=" AND FIND_IN_SET('$user_id', tp.ExeId) > 0";
            }
            
            if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND tp.TaskDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND tp.TaskDate<='$ToDate'";
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
                            $sql .= " ORDER BY tp.CreatedDate DESC";
                                    $row = getList($sql);
                                    foreach ($row as $result) {
                                        $sql3 = "SELECT tpf.*,tu.Fname,tu.Lname FROM tbl_task_details tpf LEFT JOIN tbl_users tu ON tpf.CreatedBy=tu.id WHERE tpf.CompId='".$result['id']."' ORDER BY tpf.id DESC LIMIT 1";
                $rncnt3 = getRow($sql3);
                $row3 = getRecord($sql3);
                if($row3['ClainStatus'] == 'In Process'){
                    $bcolor = "background-color: #fbc889;";
                }
                else if($row3['ClainStatus'] == 'Closed'){
                    $bcolor = "background-color: #69f769;";
                }
                else if($row3['ClainStatus'] == 'Cancelled'){
                    $bcolor = "background-color: #f98d8d;";
                }
                else{
                    $bcolor = "";
                }
                                    ?>
                                    
<div class="card mb-4">

                    <div class="card-body">
                        <h6 style="margin-bottom: 1px;font-size: 15px;"><?php echo $result['Name']; ?></h6>
                        <p style="margin-bottom: 1px;"><?php echo $result['TaskDetails']; ?></p>
                        <p style="margin-bottom: 1px;"><strong>Task Date :</strong> <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['TaskDate']))); ?> </p>
                        <p style="margin-bottom: 1px;"><strong>Last Action Emp Name :</strong> <?php echo $row3['Fname']." ".$row3['Lname']; ?></p>
                        <p style="margin-bottom: 1px;"><strong>Last Action Date :</strong> <?php if($row3['CreatedDate']!=''){ echo date("d/m/Y", strtotime(str_replace('-', '/',$row3['CreatedDate']))); } else { echo "-";}?></p>
                   <p style="margin-bottom: 1px;"><strong>Last Action :</strong> <?php echo $row3['Message']; ?></p>
                                         <p style="margin-bottom: 1px;"><strong>Last Status :</strong> <?php echo $row3['ClainStatus']; ?></p>
                                         <a href="take-task-action.php?qid=<?php echo $result['id']; ?>" class="btn btn-primary btn-finish" style="padding: 0.5px 1rem">Open</a>
                                         <a href="create-task.php?id=<?php echo $result['id']; ?>" class="btn btn-success btn-finish" style="padding: 0.5px 1rem">Edit</a>
                    </div>
                </div>
                  <?php $i++;} ?>

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
        $(document).ready(function() {
        $('#example').DataTable({
          "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
        });
    });
</script>
</body>

</html>
