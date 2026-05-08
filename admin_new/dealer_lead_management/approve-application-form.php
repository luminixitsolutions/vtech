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
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Approve Application Form
                         </h5>
<br>


                    <div class="card mb-4">
                             <div class="card-datatable table-responsive">
                              <table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Sr No</th>
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
            <th>Documents</th>
            <th>Created Date</th>
           
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        $sql = "SELECT * FROM tbl_mp_pump_applications WHERE status=1 ORDER BY created_at DESC";
        $res = $conn->query($sql);
        while($row = $res->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
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
            <td><?php if($row["documents"] == '') {?>
                  <span style="color:red;">No Document Found</span>
                 <?php } else if(file_exists('../uploads/'.$row["documents"])){?>
                 <a href="../uploads/<?php echo $row["documents"];?>" target="_blank">View Receipt</a>
                  <?php }  else{?>
                <span style="color:red;">No Document Found</span>
             <?php } ?></td>
            
            <td><?php echo date("d/m/Y", strtotime($row['created_at'])); ?></td>
            
        </tr>
        <?php } ?>
    </tbody>
</table>
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
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery.min.js"></script>
   <script type="text/javascript" src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>

    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>


    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>

    <script>


       $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true

    });
});
        </script>
</body>

</html>
