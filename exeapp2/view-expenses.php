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
<h4 class="font-weight-bold py-3 mb-0">My Expenses
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-expenses.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add</a></span><?php } ?>
</h4>


<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
              <th>Date</th>
                <th>Amount</th>
               
                <th>Payment Mode</th>
            <th>Narration</th>
               
                
               <th>Status</th>
                 <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
             $sql = "SELECT tp.*,tb.Name As BranchName,tah.Name As AccountHead FROM tbl_expenses tp 
                    LEFT JOIN tbl_branch tb ON tb.id=tp.BranchId 
                    LEFT JOIN tbl_account_head tah ON tah.id=tp.AccHeadId WHERE tp.CreatedBy='$user_id'";

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
              
              
                      <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['ExpenseDate']))); ?></td>
                <td><?php echo $row['Amount']; ?></td>
               
          
            <td><?php echo $row['PaymentMode']; ?></td>
            <td><?php echo $row['Narration']; ?></td>
            <td><?php if($row['Status']=='1'){echo "<span style='color:green;'>Approved</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>
           <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
              <?php if(in_array("10", $Options)){?>
              <a href="add-expenses.php?id=<?php echo $row['id']; ?>" ><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){?>
              &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a>
             <?php } ?>
            </td> <?php } ?>
        
              
            </tr>
           <?php $i++;} ?>

           <tr>
                <td><?php echo $i;?></td>
                 <td>Total</td>
              
                <th><?php echo $TotalAmt;?></th>
              
                <td></td>
                <td></td>
                <td></td>
                <td></td>
           </tr>
        </tbody>
    </table>
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
