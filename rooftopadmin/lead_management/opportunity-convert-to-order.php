<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Feedback";
$Page = "Product-Feedback";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title;?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    
   <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/datatables/datatables.css">
    
  
</head>

<body>
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] Ebd -->
    <!-- [ Layout wrapper ] Start -->
     <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'lead-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once '../top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Opportunity Convert To Order</h5>
                        
 <style>
    .flex-wrap {
            margin-bottom: -35px;
    }
        
    div.dataTables_wrapper div.dataTables_paginate {
            margin-top: 1px;
    }
    

</style>
 <?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_rooftop_sell WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-sells.php";
    </script>
<?php } 

if(isset($_POST['submit'])){
    $ProjectType = $_POST['ProjectType'];
   $number = count($_POST['CheckId']);
   $ExeId = $_POST['ExeId'];
   $CreatedDate = date('Y-m-d');
   $CreatedTime = date('h:i a');
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
                $sql = "UPDATE tbl_rooftop_lead_quotation SET OppConverted='1' WHERE id='$LeadId'";
                $conn->query($sql);

                $sql2 = "SELECT tl.CustId,tl.ClainReason,tl.customer_id,tl.id FROM tbl_rooftop_lead_quotation tlq 
                         LEFT JOIN tbl_rooftop_leads tl ON tlq.CustId=tl.id WHERE tlq.id='$LeadId'";
                $row2 = getRecord($sql2);
                $CustId = $row2['CustId'];
                $ClainReason = $row2['ClainReason'];
                $customer_id = $row2['customer_id'];
                $lead_id = $row2['id'];
                $sql44 = "SELECT * FROM tbl_users WHERE id='$customer_id'";
                $rncnt44 = getRow($sql44);
                if($rncnt44 > 0){
                    $sql22 = "UPDATE tbl_users SET DoneBy='$ClainReason',DoneByUserId='$CustId',LeadCust=0,LeadId='$LeadId',ProjectType='$ProjectType' WHERE id='$customer_id'";
                    $conn->query($sql22);
                    $cust_id = $customer_id;
                }
                else{
                $sql22 = "INSERT INTO tbl_users SET Fname='$CustName',Phone='$CellNo',Address='$Address',Password='12345',Status=1,Roll=5,CreatedDate='$CreatedDate',DoneBy='$ClainReason',DoneByUserId='$CustId',LeadId='$LeadId',LeadCust=0,ProjectType='$ProjectType'";
                $conn->query($sql22);
                $cust_id = mysqli_insert_id($conn);
                }
                
                  $Steps = "Customer Convert To Order";
  
  $sql = "INSERT INTO tbl_steps SET CustId='$cust_id',Steps='$Steps',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',CustName='$CustName',Address='$Address',Phone='$CellNo',LeadId='$lead_id'";
  $conn->query($sql);
  
  $sql = "UPDATE tbl_steps SET CustId='$cust_id' WHERE LeadId='$lead_id'";
  $conn->query($sql);
  
                }
              }
            }
        }

        echo "<script>alert('Opportunity Converted To Order Successfully');window.location.href='opportunity-convert-to-order.php';</script>";
}
?>
<br>
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
<button type="submit" class="btn btn-primary btn-finish">Search</button>
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
            $sql = "SELECT ts.*,tl.ClainReason,tu.Fname FROM tbl_rooftop_lead_quotation ts 
                         LEFT JOIN tbl_rooftop_leads tl ON ts.CustId=tl.id 
                         LEFT JOIN tbl_users tu ON tl.CustId=tu.id WHERE ts.Status=1 AND ts.ClainStatus='Completed'";
        }
        else{
            $sql = "SELECT ts.*,tl.ClainReason,tu.Fname FROM tbl_rooftop_lead_quotation ts 
                         LEFT JOIN tbl_rooftop_leads tl ON ts.CustId=tl.id 
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
               
                $sql22 = "SELECT * FROM tbl_rooftop_lead_quotation WHERE OppConverted=1 AND id='".$row['id']."'";
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

<div class="row">
<div class="form-group col-md-3">
                                            <label class="form-label">Project Type <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ProjectType" name="ProjectType" required="">
                                             
                                              
                                                 <option value="2" >Rooftop Projects</option> 
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>


<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</div>
</div>
</form>
</div>
                        



					</div>
                    <!-- [ content ] End -->
                    <!-- [ Layout footer ] Start -->
                    
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $SiteUrl;?>/assets/js/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?php echo $SiteUrl;?>/assets/js/vfs_fonts.js"></script>
   <script type="text/javascript" src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>

    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>


    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>

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
        "scrollX": true
    });
});
</script>
</body>

</html>
