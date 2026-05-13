<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Lead";
$Page = "View-Lead";
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

            <?php include_once 'lead-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once '../top_header.php'; ?>
              
              <?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_mp_pump_applications WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-application-form.php";
    </script>
<?php } ?>

<?php

if(isset($_POST['submit'])){

   $number = count($_POST['CheckId']);

   $ExeId = $_POST['ExeId'];
   $CreatedDate = date('Y-m-d H:i:s');
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                $CustId = addslashes(trim($_POST['CustId'][$i]));
                $sql = "UPDATE tbl_mp_pump_applications SET CoordinatorStatus='1',CoordinatorId='$ExeId',CoordinatorDate='$CreatedDate' WHERE id='$CustId'";
                $conn->query($sql);

                }
              }
            }
        }
        
  
        echo "<script>alert('Customer Assign To Co-ordinator');window.location.href='assign-applications.php';</script>";
}
?>

                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Assign Application Form
                        </h5>
<br>


                    <div class="card mb-4">
                           <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                             <div class="card-datatable table-responsive">
                              <table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Sr No</th>
            <th></th>
            <th>Status</th>
            <th>Document Submitted</th>
            <th>Applicant Name</th>
            <th>Father's Name</th>
            <th>District</th>
            <th>Tehsil</th>
            <th>Village</th>
            <th>Lok Sabha</th>
            <th>Vidhan Sabha</th>
            <th>Pincode</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Aadhaar</th>
            <th>Account Holder</th>
            <th>Account Number</th>
            <th>IFSC</th>
            <th>Bank Name</th>
            <th>Branch Name</th>
            <th>Samagra ID</th>
            <th>Family Samagra ID</th>
            <th>Caste</th>
            <th>Khasra Number</th>
            <th>Water Source</th>
            <th>Micro Irrigation</th>
            <th>Land Area</th>
            <th>Borewell Depth</th>
            <th>Water Requirement</th>
            <th>Distance to Panel</th>
            <th>Ground Water Depth</th>
            <th>Created Date</th>
           
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        $sql = "SELECT * FROM tbl_mp_pump_applications WHERE CoordinatorStatus=0 ORDER BY created_at DESC";
        $res = $conn->query($sql);
        while($row = $res->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
              <td><?php if($rncnt22 > 0){} else{?>
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?> </td>
                 <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                   <input type="hidden" value="<?php echo $row['id']; ?>" name="CustId[]">
                   
            <td><?php if($row['status']=='1'){echo "<span style='color:green;'>Approved</span>";} else if($row['status']=='2'){echo "<span style='color:red;'>Rejected</span>";} else { echo "<span style='color:orange;'>Pending</span>";} ?></td>
            <td><?php if($row['document_submitted']=='Yes'){echo "<span style='color:green;'>Yes</span>";} else { echo "<span style='color:red;'>No</span>";} ?></td>
            <td><?php echo $row['applicant_name']; ?></td>
            <td><?php echo $row['father_name']; ?></td>
            <td><?php echo $row['district']; ?></td>
            <td><?php echo $row['tehsil']; ?></td>
            <td><?php echo $row['village']; ?></td>
            <td><?php echo $row['lok_sabha']; ?></td>
            <td><?php echo $row['vidhan_sabha']; ?></td>
            <td><?php echo $row['pincode']; ?></td>
            <td><?php echo $row['mobile']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['gender']; ?></td>
            <td><?php echo $row['aadhaar']; ?></td>
            <td><?php echo $row['account_holder']; ?></td>
            <td><?php echo $row['account_number']; ?></td>
            <td><?php echo $row['ifsc']; ?></td>
            <td><?php echo $row['bank_name']; ?></td>
            <td><?php echo $row['branch_name']; ?></td>
            <td><?php echo $row['samagra_id']; ?></td>
            <td><?php echo $row['family_samagra_id']; ?></td>
            <td><?php echo $row['caste']; ?></td>
            <td><?php echo $row['khasra_number']; ?></td>
            <td><?php echo $row['water_source']; ?></td>
            <td><?php echo $row['micro_irrigation']; ?></td>
            <td><?php echo $row['land_area']; ?></td>
            <td><?php echo $row['borewell_depth']; ?></td>
            <td><?php echo $row['water_requirement']; ?></td>
            <td><?php echo $row['distance_to_panel']; ?></td>
            <td><?php echo $row['ground_water_depth']; ?></td>
            <!--<td><?php if($row["documents"] == '') {?>
                  <span style="color:red;">No Document Found</span>
                 <?php } else if(file_exists('../uploads/'.$row["documents"])){?>
                 <a href="../uploads/<?php echo $row["documents"];?>" target="_blank">View Receipt</a>
                  <?php }  else{?>
                <span style="color:red;">No Document Found</span>
             <?php } ?></td>
            -->
            <td><?php echo date("d/m/Y", strtotime($row['created_at'])); ?></td>
            <?php if(in_array("10", $Options) || in_array("11", $Options)) { ?>
           <!-- <td>
                <?php if(in_array("10", $Options)) { ?>
                    <a href="add-application-form.php?id=<?php echo $row['id']; ?>" title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>
                <?php } if(in_array("11", $Options)) { ?>
                    <a onclick="return confirm('Are you sure you want to delete this record?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" title="Delete"><i class="lnr lnr-trash text-danger"></i></a>
                <?php } ?>
            </td>-->
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
                            </div>
                            
                            
<div class="form-row" style="padding-left: 20px;">

 <div class="form-group col-lg-4">
<label class="form-label"> Telecaller<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ExeId" id="ExeId" required>
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

<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Assign</button>
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
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery.min.js"></script>
   <script type="text/javascript" src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>

    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>


    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
 <script src="<?php echo $SiteUrl;?>/assets/libs/select2/select2.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.js"></script>
        <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>
    <script>

function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }
       $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
    });
});
        </script>
</body>

</html>
