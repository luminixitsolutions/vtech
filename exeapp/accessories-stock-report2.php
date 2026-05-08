<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Accessories-Stock-Report2";
$sessionid = session_id();
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Accessories Stock Report</title>
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
<h4 class="font-weight-bold py-3 mb-0">Accessories Stock Report

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
                                            <label class="form-label">Accessories</label>
                                            <select class="select2-demo form-control" name="ProductId" id="ProductId">
                                                <option selected="" value="all">All</option>
                                                <?php 
  $sql12 = "SELECT * FROM tbl_accessories WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
                                                <option <?php if($_REQUEST['ProductId']==$result['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $result['id']; ?>"><?php echo $result['AccName']; ?></option>
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
               <th>#</th>
               <th>Branch</th>
                <th>Accessories Name</th>
                <th>Narration</th>
                <th>Date</th>
                <th>Credit Stock</th>
                <th>Debit Stock</th>
                
             
            </tr>
            
        </thead>
       <tbody>
              <?php 
            $i=1;
            $sql = "SELECT ts.*,tb.Name As Branch,tp.AccName AS Product_Name FROM tbl_accessories_stock ts 
                    LEFT JOIN tbl_accessories tp ON ts.AccId=tp.id 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id WHERE ts.Status=1 
                    ";
             

            if($_POST['BranchId']){
                $BranchId = $_POST['BranchId'];
                if($BranchId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.BranchId='$BranchId'";
                }
            }
            
            if($_POST['ProductId']){
                $ProductId = $_POST['ProductId'];
                if($ProductId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.AccId='$ProductId'";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ts.CreatedDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND ts.CreatedDate<='$ToDate'";
            }
            $sql.=" ORDER BY ts.CreatedDate DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                if($row['CrDr'] == 'cr'){
                    $CreditStock = $row['Qty'];
                    $DebitStock = "";
                    $TotCreditStock+=$row['Qty'];
                }
                else{
                    $DebitStock = $row['Qty'];
                    $CreditStock = "";
                    $TotDebitStock+=$row['Qty'];
                }

                /*$sql2 = "SELECT SUM(Qty) AS TotStock FROM tbl_stocks WHERE BranchId='".$row['BranchId']."' AND ProductId='".$row['ProductId']."' AND CrDr='cr'";
                $row2 = getRecord($sql2);

                $sql3 = "SELECT SUM(Qty) AS SellStock FROM tbl_stocks WHERE BranchId='".$row['BranchId']."' AND ProductId='".$row['ProductId']."' AND CrDr='dr'";
                $row3 = getRecord($sql3);*/

                $TotStock+=$row2['TotStock'];
                $SellStock+=$row3['SellStock'];
                $BalStock+=$row2['TotStock'] - $row3['SellStock'];


             ?>
            <tr>
               <td><?php echo $i; ?></td>
           
               <td><?php echo $row['Branch']; ?></td>
          
               <td><?php echo $row['Product_Name']; ?></td>
            
                <td><?php echo $row['Narration']; ?></td>
                  <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
               <td><?php echo $CreditStock; ?></td>
               <td><?php echo $DebitStock; ?></td>
                 
            
          
          
            </tr>
           <?php $i++;} ?>

           <tr>
               <td><?php echo $i; ?></td>
               
              
               <td></td>
               <td></td>
               <td></td>
               <th>Total</th>
               <th><?php echo $TotCreditStock;?></th>
               <th><?php echo $TotDebitStock;?></th>
               
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
        "scrollX": true
    });
});
</script>
</body>
</html>
