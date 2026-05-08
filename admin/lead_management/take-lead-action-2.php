<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
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
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/flot/flot.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/select2/select2.css">

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

            <?php //include_once 'lead-sidebar.php'; ?>


            <div class="layout-container">

              <?php //include_once '../top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Take
                            <?php } ?> Action On Lead</h5>
                        
  <?php 
$id = $_GET['id'];
$CompId = $_GET['qid'];
$sql7 = "SELECT * FROM tbl_lead_details WHERE id='$id'";
$row7 = getRecord($sql7);

$sql77 = "SELECT * FROM tbl_leads WHERE id='$CompId'";
$row77 = getRecord($sql77);
$CustName = $row77['CustName'];
$CellNo = $row77['CellNo'];
$Address = $row77['Address'];

if(isset($_POST['submit'])){
    $CustId = addslashes(trim($_POST["CustId"]));
     //$CellNo = addslashes(trim($_POST["CellNo"]));
    //$CustName = addslashes(trim($_POST["CustName"]));
$Status = 1;
//$Address = addslashes(trim($_POST["Address"]));
$DocumentsStatus = addslashes(trim($_POST['DocumentsStatus']));
$ClainReason = addslashes(trim($_POST["ClainReason"]));
$ClainStatus = addslashes(trim($_POST["ClainStatus"]));
$Message = addslashes(trim($_POST["Message"]));
$NextDate = addslashes(trim($_POST["NextDate"]));
$NextTime = addslashes(trim($_POST["NextTime"]));
$DoneBy = addslashes(trim($_POST["DoneBy"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

if($_GET['id']==''){
     $qx = "INSERT INTO tbl_lead_details SET CompId='$CompId',Message='$Message',ClainStatus='$ClainStatus',CreatedDate='$CreatedDate',CreatedBy='$user_id',NextDate='$NextDate',NextTime='$NextTime',DoneBy='$DoneBy'";
  $conn->query($qx);
 $PostId = mysqli_insert_id($conn);
  $sql = "UPDATE tbl_leads SET ClainStatus='$ClainStatus',NextDate='$NextDate',NextTime='$NextTime',DoneBy='$DoneBy' WHERE id='$CompId'";
  $conn->query($sql);
  
   //$Steps = "Customer Leads In ".$ClainStatus;
   
   /*$Steps = "Lead ".$ClainStatus;
  
  $sql = "INSERT INTO tbl_steps SET CustId='0',Steps='$Steps',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',CustName='$CustName',Address='$Address',Phone='$CellNo',LeadId='$CompId',LeadActId='$PostId'";
  $conn->query($sql);*/
   

  echo "<script>alert('Lead Record Saved Successfully!');
  window.close();
  window.opener.location.reload(true);</script>";
}
else{
 
    $query2 = "UPDATE tbl_lead_details SET CompId='$CompId',Message='$Message',ClainStatus='$ClainStatus',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',NextDate='$NextDate',NextTime='$NextTime',DoneBy='$DoneBy' WHERE id = '$id'";
  $conn->query($query2);

   $sql = "UPDATE tbl_leads SET ClainStatus='$ClainStatus',NextDate='$NextDate',NextTime='$NextTime',DoneBy='$DoneBy' WHERE id='$CompId'";
  $conn->query($sql);
  
  //$Steps = "Customer Leads In ".$ClainStatus;
  /*$Steps = "Lead ".$ClainStatus;

  $sql = "UPDATE tbl_steps SET CustId='0',Steps='$Steps',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',CustName='$CustName',Address='$Address',Phone='$CellNo',LeadId='$CompId',LeadActId='$PostId' WHERE LeadActId='$id'";
  $conn->query($sql);*/
   

  echo "<script>alert('Lead Record Updated Successfully!');window.close();window.opener.location.reload(true);</script>";

}
    //header('Location:courses.php'); 

  }
?>

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
   <label class="form-label">Customer Name </label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row77["CustName"]; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div> 

<div class="form-group col-md-12">
                                            <label class="form-label">Contact No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $row77["CellNo"]; ?>"
                                                autocomplete="off" oninput="getUserDetails()" disabled>
                                            <div class="clearfix"></div>
                                        </div>


 <div class="form-group col-md-12">
   <label class="form-label">Address</label>
     <textarea name="Address" id="Address" class="form-control"  disabled
                                                ><?php echo $row77['Address']; ?></textarea>
    <div class="clearfix"></div>
 </div>   


  




<div class="form-group col-lg-4">
<label class="form-label">Call After Date</label>
<input type="date" name="NextDate" class="form-control" id="NextDate" placeholder="" value="">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-4">
<label class="form-label">Time</label>
<input type="text" name="NextTime" class="form-control" id="NextTime" placeholder="" value="">
<div class="clearfix"></div>
</div>


<div class="form-group col-md-12">
   <label class="form-label">Message</label>
     <textarea  type="text" name="Message" id="Message" class="form-control"><?php echo $row7['Message']; ?></textarea>
    <div class="clearfix"></div>
 </div>   

<div class="form-group col-lg-4">
<label class="form-label"> Lead Status<span class="text-danger">*</span></label>
 <select class="form-control" name="ClainStatus" id="ClainStatus" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=11";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['ClainStatus'] == $result['Name']){?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>
 
 <div class="form-group col-md-12">
<label class="form-label"> Selection Done By </label>
 <select class="select2-demo form-control" name="DoneBy" id="DoneBy">
     <option selected="" value="">Select </option>
  

<optgroup label="Employee">
     <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(2,6,7,12)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["DoneBy"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</optgroup>



</select>
<div class="clearfix"></div>
</div>

</div>
<br>

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
                        

<div class="card mb-4" id="Conversation">
                                    <div class="card-body">
                                        <div class="row help-desk">
                            <div class="col-xl-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <nav class="navbar justify-content-between p-0 align-items-center shadow-none">
                                            <h5 class="my-2">Conversation</h5>
                                            
                                        </nav>
                                    </div>
                                </div>
                                

     <div class="container-fluid flex-grow-1 container-p-y">
                      
                        
                        <div class="row help-desk">
                            <div class="col-xl-12 col-lg-12">
                                
                            

                          
                                
                                
                                <?php 
$i=2;
$id = $_GET['qid'];
  $sql2 = "SELECT tp.*,tu.Fname,tu.Lname FROM tbl_lead_details tp 
          LEFT JOIN tbl_users tu ON tp.CreatedBy=tu.id WHERE tp.CompId='$id'
          ORDER BY tp.id DESC"; 

    $res2 = $conn->query($sql2);
    while($row = $res2->fetch_assoc()){ ?>

                                <div class="ticket-block">
                                    <div class="row">
                                     
                                        <div class="col">
                                            <div class="card example-popover" data-toggle="modal" data-target="#modals-slide" data-toggle="popover" data-placement="right" data-html="true"
                                                title="<img src='assets/img/user/avatar-1.jpg' class='wid-20 rounded mr-1 img-fluid'><p class='d-inline-block mb-0 ml-2'>You replied</p>" data-content="hello Yogen dra,you need to create "
                                                toolbar-options="div only once in a page in your code, this div fill found every 'td' ...">
                                                <div class="row no-gutters row-bordered row-border-light h-100">
                                                    <div class="d-flex col">
                                                        <div class="card-body">
                                                            <h5 class="mb-0"><?php echo $row['Fname']." ".$row['Lname'];?></h5>
                                                            <p class="my-1 text-muted"><i class="feather icon-lock mr-1 f-14"></i>Telecaller</p>
                                                            <ul class="list-inline mt-2 mb-0 hid-sm">
                                                                
                                                                <li class="list-inline-item my-1"><i class="feather icon-calendar mr-1 f-14"></i>Conversation at <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?> <?php echo $row['CreatedTime']; ?></li>
                                                                
                                                            </ul>
                                                            <div class="card bg-light my-3 p-3 hid-md">
                                                                <h6><img src="assets/img/user/avatar-5.jpg" alt="" class="wid-20 avatar mr-2 rounded">Last comment from <a href="#"><?php echo $row['CustName'];?>:</a></h6>
                                                                <p class="mb-0"><?php echo $row['Message']; ?></p>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <?php $i++;} ?>
                                
                                
                            </div>


                            
                            
                        </div>
                    </div>
                    
                                
                                
                                </div>
                                        </div>
                                    </div>
                                    
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
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/popper/popper.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
   <script src="<?php echo $SiteUrl;?>/assets/libs/select2/select2.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.js"></script>
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/analytics.js"></script>
     <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>
</body>

</html>
