<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Feedback";
$Page = "Product-Feedback";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Sell List</title>
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
<body>

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
  $sql11 = "DELETE FROM tbl_sell WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-sells.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Today Calling List
<!-- <span style="float: right;">
<a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New Sell</a></span> -->
</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       

  <div class="form-group col-md-3">
<label class="form-label">Customers</label>
 <select class="select2-demo form-control" name="CustId" id="CustId">
<option selected="" value="all">All</option>
 <?php 
 if($Roll == 1 || $Roll == 7){
  $sql12 = "SELECT * FROM tbl_users WHERE Roll = '5' AND Status='1'";
}
else{
   $sql12 = "SELECT * FROM tbl_users WHERE Roll = '5' AND Status='1' AND UnderUser='$user_id'"; 
}
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<!--  <div class="form-group col-md-4">
<label class="form-label"> Customer Type</label>

 <select class="form-control" name="UserType" id="UserType">
    <option selected="" value="all">All</option>
       <?php 
           $q = "select * from tbl_user_type WHERE Status=1";
            $r = $conn->query($q);
          while($rw = $r->fetch_assoc())
        {
  ?>
    <option <?php if($_POST['UserType']==$rw['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
     <?php } ?>
</select>
<div class="clearfix"></div>
</div> -->
<?php 
 if($Roll == 1 || $Roll == 7){?>
<div class="form-group col-md-3">
<label class="form-label">Telecaller</label>
 <select class="select2-demo form-control" name="CreatedBy" id="CreatedBy">
<option selected="" value="all">All</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Roll = '2' AND Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CreatedBy"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." ".$result['Lname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>
<?php } ?>


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
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               
               <th>Telecaller Name</th>
                <th>Customer Name</th>
                
                <th>Contact No</th>
              
               <th>Conversation</th>
               <th>Call After Date</th>
               <th>Time</th>
                <th>Feedback</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            if($Roll == 1 || $Roll == 7){
            $sql = "SELECT ts.*,tu.Fname,tu.Lname FROM tbl_purchase_feedback ts LEFT JOIN tbl_users tu ON ts.CreatedBy=tu.id WHERE ts.Status=1";
            }
        else{
$sql = "SELECT ts.*,tu.Fname,tu.Lname FROM tbl_purchase_feedback ts LEFT JOIN tbl_users tu ON ts.CreatedBy=tu.id WHERE ts.Status=1 AND tu.UnderUser='$user_id'";
        }
             if($_POST['CustId']){
                $CustId = $_POST['CustId'];
                if($CustId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.CustId='$CustId'";
                }
            }

             if($_POST['UserType']){
                $UserType = $_POST['UserType'];
                if($UserType == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.UserType='$UserType'";
                }
            }
            
            if($_POST['CreatedBy']){
                $CreatedBy = $_POST['CreatedBy'];
                if($CreatedBy == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.CreatedBy='$CreatedBy'";
                }
            }
            
            
           
            if($_POST['FromDate'] != ''){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ts.NextDate>='$FromDate'";
            }
            if($_POST['FromDate'] == ''){
                $FromDate = date('Y-m-d');
                $sql.= " AND ts.NextDate='$FromDate'";
            }
            if($_POST['ToDate'] != ''){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND ts.NextDate<='$ToDate'";
            }
            if($_POST['ToDate'] == ''){
                $ToDate = date('Y-m-d');
                $sql.= " AND ts.NextDate='$ToDate'";
            }
            $sql.=" ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
               $sql3 = "SELECT tpf.*,tu.Fname,tu.Lname FROM tbl_purchase_feedback tpf LEFT JOIN tbl_users tu ON tpf.CreatedBy=tu.id WHERE tpf.CustId='".$row['CustId']."' AND tpf.CreatedDate='".date('Y-m-d')."' ORDER BY tpf.id DESC LIMIT 1";
                $rncnt3 = getRow($sql3);
                $row3 = getRecord($sql3);
                if($rncnt3 > 0){
                    $bcolor = "background-color: antiquewhite;";
                }
                else{
                    $bcolor = "";
                }

             ?>
            <tr style="<?php echo $bcolor;?>">
               <td><?php echo $i; ?></td>
               
                <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
              <td><?php echo $row['CustName']; ?></td>
              <td><?php echo $row['Phone']; ?></td>
          
            
              <td><?php echo $row['Details']; ?></td>
              <td><?php echo $row['NextDate']; ?></td>
              <td><?php echo $row['NextTime']; ?></td>
               <td>
               
                <a href="javascript:void(0)" onclick="getFeedback(<?php echo $row['CustId']; ?>)" class="btn btn-primary btn-finish" style="padding: 0.5px 1rem">Open</a>
          
            </td>
                    
               
          
           
              
            </tr>
           <?php $i++;} ?>
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
 function getFeedback(id){
    setTimeout(function() {
        window.open(
            'add-purchase-feedback.php?id=' + id, 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=800,left=250,top=50,right=50'
        );
    }, 1);
 }
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
    });
});
</script>
</body>
</html>
