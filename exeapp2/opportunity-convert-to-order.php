<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Feedback";
$Page = "Product-Feedback";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>
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

 <?php include_once 'back-header.php'; ?> 






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
<?php } 

if(isset($_POST['submit'])){
   $number = count($_POST['CheckId']);
   $ExeId = $_POST['ExeId'];
   $CreatedDate = date('Y-m-d');
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                $LeadId = addslashes(trim($_POST['LeadId'][$i]));
                $CustName = addslashes(trim($_POST['CustName'][$i]));
                $CellNo = addslashes(trim($_POST['CellNo'][$i]));
                $Address = addslashes(trim($_POST['Address'][$i]));
                $sql = "UPDATE tbl_lead_quotation SET OppConverted='1' WHERE id='$LeadId'";
                $conn->query($sql);

                $sql2 = "SELECT tlq.CustId,tl.ClainReason,tl.customer_id FROM tbl_lead_quotation tlq 
                         LEFT JOIN tbl_leads tl ON tlq.CustId=tl.id WHERE tlq.id='$LeadId'";
                $row2 = getRecord($sql2);
                $CustId = $row2['CustId'];
                $ClainReason = $row2['ClainReason'];
                $customer_id = $row2['customer_id'];
                $sql44 = "SELECT * FROM tbl_users WHERE id='$customer_id'";
                $rncnt44 = getRow($sql44);
                if($rncnt44 > 0){
                    $sql22 = "UPDATE tbl_users SET DoneBy='$ClainReason',DoneByUserId='$CustId',LeadCust=0,LeadId='$LeadId' WHERE id='$customer_id'";
                    $conn->query($sql22);
                }
                else{
                $sql22 = "INSERT INTO tbl_users SET Fname='$CustName',Phone='$CellNo',Address='$Address',Password='12345',Status=1,Roll=5,CreatedDate='$CreatedDate',DoneBy='$ClainReason',DoneByUserId='$CustId',LeadId='$LeadId',LeadCust=0";
                $conn->query($sql22);
                }
                }
              }
            }
        }

        echo "<script>alert('Opportunity Converted To Order Successfully');window.location.href='opportunity-convert-to-order.php';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Opportunity Convert To Order
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

       

 <div class="form-group col-lg-4">
<label class="form-label"> Lead Source<span class="text-danger">*</span></label>
 <select class="form-control" name="ClainReason" id="ClainReason" required>
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=10";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST['ClainReason'] == $result['Name']){?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2 col-6">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2 col-6">
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
   <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
              
               
                <th>Customer Name</th>
                <th>Contact No</th>
                 <th>Source</th>
             
                <th>Status</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            if($Roll == 1 || $Roll == 7){
            $sql = "SELECT ts.*,tl.ClainReason,tu.Fname FROM tbl_lead_quotation ts 
                         LEFT JOIN tbl_leads tl ON ts.CustId=tl.id 
                         LEFT JOIN tbl_users tu ON tl.CustId=tu.id WHERE ts.Status=1 AND ts.ClainStatus='Completed'";
        }
        else{
            $sql = "SELECT ts.*,tl.ClainReason,tu.Fname FROM tbl_lead_quotation ts 
                         LEFT JOIN tbl_leads tl ON ts.CustId=tl.id 
                         LEFT JOIN tbl_users tu ON tl.CustId=tu.id WHERE ts.Status='1' AND ts.ClainStatus='Completed' AND tl.AllocateId='$user_id'";
        }
             if($_POST['CustId']){
                $CustId = $_POST['CustId'];
                if($CustId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.id='$CustId'";
                }
            }

            if($_POST['ClainReason']){
                $ClainReason = $_POST['ClainReason'];
                if($ClainReason == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tl.ClainReason='$ClainReason'";
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
            $sql.=" ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
                $sql22 = "SELECT * FROM tbl_lead_quotation WHERE OppConverted=1 AND id='".$row['id']."'";
                $rncnt22 = getRow($sql22);
                if($rncnt22 > 0){
                     $bcolor = "background-color: #b9efb9;";
                }
                else{
                    $bcolor = "";
                }
                

             ?>
            <tr style="<?php echo $bcolor;?>">
                <td><?php if($rncnt22 > 0){} else{?>
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?> </td>
                 <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                 <input type="hidden" value="<?php echo $row['id']; ?>" name="LeadId[]">
               <input type="hidden" value="<?php echo $row['CustName']; ?>" name="CustName[]">
               <input type="hidden" value="<?php echo $row['CellNo']; ?>" name="CellNo[]">
               <input type="hidden" value="<?php echo $row['Address']; ?>" name="Address[]">
               
              <td><?php echo $row['CustName']; ?></td>
              <td><?php echo $row['CellNo']; ?></td>
              <td><?php echo $row['ClainReason']." ".$row['Fname']; ?></td>
             
              <td><?php echo $row['ClainStatus']; ?></td>
             
          
           
              
            </tr>
           <?php  $i++;} ?>
        </tbody>
    </table>
</div>

<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</div>
</form>
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
     function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }
 function getFeedback(id){
    setTimeout(function() {
        window.open(
            'take-lead-action-2.php?qid=' + id, 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=800,left=250,top=50,right=50'
        );
    }, 1);
 }
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
