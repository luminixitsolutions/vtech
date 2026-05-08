<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Attendance-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Attendance Report</title>
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









<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Attendance Report</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

  

  <div class="form-group col-md-5">
                                            <label class="form-label">Executive</label>
                                            <select class="select2-demo form-control" name="ExeId" id="ExeId">
                                                <option selected="" value="all">All</option>
                                                <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=6";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
                                                <option <?php if($_REQUEST['ExeId']==$result['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $result['id']; ?>"><?php echo $result['Fname']; ?></option>
                                                <?php } ?>
                                            </select>
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
<div class="form-group col-md-1" style="padding-top:30px;">
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
               <th>Executive Name</th>
               <?php 
               $sql = "SELECT DISTINCT(CreatedDate) AS CreatedDate FROM tbl_attendance WHERE Status IN (1,0)";
               if($_POST['UserId']){
                $UserId = $_POST['UserId'];
                if($UserId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND UserId='$UserId'";
                }
            }
               if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND CreatedDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql.= " AND CreatedDate<='$ToDate'";
                }

                $sql.= " ORDER BY CreatedDate";
               $row = getList($sql);
               foreach($row as $result){
               ?>
                <th><?php echo date("d", strtotime(str_replace('-', '/',$result['CreatedDate']))); ?></th> 
               <?php } ?>
             
                <th>Total</th>
                <th>Sundays</th>
                <th>Late Marks</th>
              <th>Net Days</th>
              
               
            </tr>
        </thead>
        <tbody>
            <?php 
           
            $i=1;
            $sql = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=6
                    ";
            $sql.=" ORDER BY Fname";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
                $sql3 = "SELECT SUM(Status) As TotPresent,SUM(Latemark) AS Latemark FROM tbl_attendance WHERE UserId='".$row['id']."' AND Status=1";
                if($_POST['UserId']){
                $UserId = $_POST['UserId'];
                if($UserId == 'all'){
                    $sql3.= " ";
                }
                else{
                $sql3.= " AND UserId='$UserId'";
                }
            }
                if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql3.= " AND CreatedDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql3.= " AND CreatedDate<='$ToDate'";
                }
                $row33 = getRecord($sql3);


                $sql4 = "SELECT MIN(CreatedDate) AS StartDate,MAX(CreatedDate) AS EndDate FROM tbl_attendance WHERE UserId='".$row['id']."'";
                if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql4.= " AND CreatedDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql4.= " AND CreatedDate<='$ToDate'";
                }
                $row4 = getRecord($sql4);
                $StartDate = $row4['StartDate'];
                $EndDate = $row4['EndDate'];

                $sundays=0; for($i=$StartDate;$i<=$EndDate;$i++) { $day=date("N",strtotime($i)); if($day==7) { $sundays++; } } 


             ?>
            <tr>
              
               <td><?php echo $row['Fname']; ?></td> 
                <?php 
                 $sql2 = "SELECT DISTINCT(CreatedDate) AS CreatedDate FROM tbl_attendance WHERE Status IN (1,0)";
                 if($_POST['UserId']){
                $UserId = $_POST['UserId'];
                if($UserId == 'all'){
                    $sql2.= " ";
                }
                else{
                $sql2.= " AND UserId='$UserId'";
                }
            }
               if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql2.= " AND CreatedDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql2.= " AND CreatedDate<='$ToDate'";
                }

                $sql2.= " ORDER BY CreatedDate";
                 $row2 = getList($sql2);
               foreach($row2 as $result){
                $sql3 = "SELECT * FROM tbl_attendance WHERE UserId='".$row['id']."' AND CreatedDate='".$result['CreatedDate']."'";
                if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql2.= " AND CreatedDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql2.= " AND CreatedDate<='$ToDate'";
                }
               $rncnt3 = getRow($sql3);
               if($rncnt3 > 0){ 
                $row3 = getRecord($sql3);
               ?>
                 <td><?php echo $row3['Status'];?></td>
              <?php }  else{ ?>
                <td>0</td>
              <?php } } ?>
            <td><?php echo $row33['TotPresent'];?></td>
            <td><?php echo $sundays;?></td>
              <td><?php echo $row33['Latemark'];?></td>
            <td><?php echo $sundays+$row33['TotPresent'];?></td>
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
