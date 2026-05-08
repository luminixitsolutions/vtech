<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Lead";
$Page = "Assign-Lead";
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

 <?php include_once 'back-header.php'; ?> 






<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_leads WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-leads.php";
    </script>
<?php } 


if(isset($_POST['submit'])){
   $number = count($_POST['CheckId']);
   $ExeId = $_POST['ExeId'];
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                $LeadId = addslashes(trim($_POST['LeadId'][$i]));
                $sql = "UPDATE tbl_leads SET AllocateId='$ExeId' WHERE id='$LeadId'";
                $conn->query($sql);
                }
              }
            }
        }

        echo "<script>alert('Lead Assigned successfully to telecaller');window.location.href='assign-leads.php';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Lead List
  
</h4>

<div class="card" style="padding: 10px;">
    <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">
 <div class="form-group col-lg-4">
<label class="form-label"> Telecaller<span class="text-danger">*</span></label>
 <select class="form-control" name="ExeId" id="ExeId" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=2";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>



</div>

<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
              
               <th>Ticket No</th> 
               <!-- <th>Allocate Lead</th> -->
                <th>Source</th> 
                <th>Customer Name</th> 
                <th>Contact No</th>
               
                <th>Address</th>
             
             
                <th>Lead Status</th>
                <th>Lead Date</th>
               
                 <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tb.Name As BranchName,tu.Fname FROM tbl_leads tp 
                    LEFT JOIN tbl_branch tb ON tp.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON tp.CustId=tu.id WHERE tp.AllocateId=0 ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td><label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label> </td>
                  <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                 <input type="hidden" value="<?php echo $row['id']; ?>" name="LeadId[]">
                 <td><a href="view-lead-action.php?id=<?php echo $row['id']; ?>"><?php echo $row['TicketNo']; ?></a></td> 
               
             
                  <td>By <?php echo $row['ClainReason']; ?> <?php echo $row['Fname']; ?> </td> 
               <td><?php echo $row['CustName']; ?></td> 
              
                <td><?php echo $row['CellNo']; ?></td>
                 <td><?php echo $row['Address']; ?></td>
                
                  <td><?php echo $row['ClainStatus']; ?></td>
            
             
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
           <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
              <?php if(in_array("10", $Options)){?>
              <a href="add-lead.php?id=<?php echo $row['id']; ?>" ><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){?>
              &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a>
             <?php } ?>
            </td> <?php } ?>
        
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>


<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Assign</button>
</div>
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
 function allocateLeads(allocateid,leadid){
     var action = "allocateLeads";
            $.ajax({
                url: "ajax_files/ajax_leads.php",
                method: "POST",
                data: {
                    action: action,
                    allocateid: allocateid,
                    leadid:leadid
                },
                success: function(data) {
                    alert("Lead Allocates to Telecaller.");
                  
                }
            });
 }
    $(document).ready(function() {
    $('#example').DataTable( {
        "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
      });
});
</script>
</body>
</html>
