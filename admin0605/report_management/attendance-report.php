<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Attendance-Report";

/*$sql2 = "SELECT UserId,CreatedDate,ApproveStatus,ApproveBy,ApproveDate,ApproveTime,ApproveLine FROM tbl_attendance WHERE Type='1' AND ApproveStatus=1";
    $row2 = getList($sql2);
    foreach($row2 as $result){
        $sql3 = "UPDATE tbl_attendance SET ApproveStatus='".$result['ApproveStatus']."',ApproveBy='".$result['ApproveBy']."',ApproveDate='".$result['ApproveDate']."',ApproveTime='".$result['ApproveTime']."',ApproveLine='".$result['ApproveLine']."' WHERE UserId='".$result['UserId']."' AND CreatedDate='".$result['CreatedDate']."' AND Type=2";
    $conn->query($sql3);
    }*/
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Attendance Report</title>
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
                                            <select class="select2-demo form-control" name="UserId" id="UserId">
                                                <option selected="" value="all">All</option>
                                                <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll NOT IN(1,3,4,5,9,10,8,11,34,35,36,37)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
                                                <option <?php if($_REQUEST['UserId']==$result['id']){ ?> selected <?php } ?>
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
   <?php if(isset($_POST['Search'])) {?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
         <thead>
            <tr>
               <th>Executive Name</th>
               <?php 
               $sql = "SELECT DISTINCT(CreatedDate) AS CreatedDate FROM tbl_attendance WHERE Status IN (1,0) AND Type=2 AND ApproveStatus=1";
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
                //echo $sql;
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
            $sql = "SELECT tu.id,tu.Fname FROM tbl_attendance ta INNER JOIN tbl_users tu ON tu.id=ta.UserId WHERE tu.Status=1 AND tu.Roll NOT IN(5) AND ta.ApproveStatus=1
                    ";
                     if($_POST['UserId']){
                $UserId = $_POST['UserId'];
                if($UserId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ta.UserId='$UserId'";
                }
            }
                    if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ta.CreatedDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql.= " AND ta.CreatedDate<='$ToDate'";
                }
                
            $sql.=" GROUP BY ta.UserId ORDER BY tu.Fname";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
               
            $sql3 = "SELECT SUM(Status) As TotPresent,SUM(Latemark) AS Latemark FROM tbl_attendance WHERE UserId='".$row['id']."' AND Status=1 AND Type=2 AND ApproveStatus=1";
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
                $sql3.=" ";
                //echo $sql3;
                $row33 = getRecord($sql3);
                if($row33['TotPresent']>0){
                    $TotPresent = $row33['TotPresent'];
                }
                else{
                    $TotPresent = 0;
                }
                if($row33['Latemark']>0){
                    $Latemark = $row33['Latemark'];
                }
                else{
                    $Latemark = 0;
                }
           
                

                $sql4 = "SELECT MIN(CreatedDate) AS StartDate,MAX(CreatedDate) AS EndDate FROM tbl_attendance WHERE UserId='".$row['id']."' AND Type=2 AND ApproveStatus=1";
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
 $sundays=0; /*for($i2=$StartDate;$i2<=$EndDate;$i2++) { $day=date("N",strtotime($i2)); if($day==7) { $sundays++; } } */
               
             ?>
            <tr>
              
               <td><?php echo $row['Fname']; ?></td> 
                <?php 
                 $sql2 = "SELECT DISTINCT(CreatedDate) AS CreatedDate FROM tbl_attendance WHERE Status IN (1,0) AND Type=2 AND ApproveStatus=1";
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
                $sql33 = "SELECT * FROM tbl_attendance WHERE UserId='".$row['id']."' AND CreatedDate='".$result['CreatedDate']."' AND Type=2 AND ApproveStatus=1";
                if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql33.= " AND CreatedDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql33.= " AND CreatedDate<='$ToDate'";
                }
               $rncnt3 = getRow($sql33);
               if($rncnt3 > 0){ 
                $row3 = getRecord($sql33);
               ?>
                 <td><?php echo $row3['Status'];?></td>
              <?php }  else{ ?>
                <td>0</td>
              <?php } } ?>
            <td><?php echo $TotPresent;?></td>
            <td><?php echo $sundays;?></td>
              <td><?php echo $Latemark;?></td>
            <td><?php echo $sundays+$TotPresent;?></td>
            </tr>
           <?php $i++;} ?>

          
        </tbody>
    </table>
</div>
<?php } ?>
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
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });

 
});
</script>
</body>
</html>
