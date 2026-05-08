<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Customer Account List</title>
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
        

        <div class="main-container" style="background-color: #f1f1f1;">






<div class="container">
  
     <div class="card mb-4">

                    <div class="card-body">
      <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                                <div class="form-row">
    <div class="form-group col-md-3 Pump col-6" id="hidediameter">
                                            <label class="form-label">
Village </label>
                                            <select class="form-control" id="Village" name="Village" >
<option selected="" value="">Select Village</option>
  <?php 
        $q = "select DISTINCT(Village) AS Village from tbl_users WHERE Village!=''";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['Village']==$rw['Village']){ ?> selected <?php } ?> value="<?php echo $rw['Village']; ?>"><?php echo $rw['Village']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                          <div class="form-group col-md-3 col-6 Pump" id="hidediameter">
                                            <label class="form-label">
District </label>
                                            <select class="form-control" id="District" name="District" >
<option selected="" value="">Select District</option>
  <?php 
        $q = "select DISTINCT(District) AS District from tbl_users WHERE District!=''";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['District']==$rw['District']){ ?> selected <?php } ?> value="<?php echo $rw['District']; ?>"><?php echo $rw['District']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3 col-6 Pump" id="hidediameter">
                                            <label class="form-label">
Taluka </label>
                                            <select class="form-control" id="Taluka" name="Taluka" >
<option selected="" value="">Select Taluka</option>
  <?php 
        $q = "select DISTINCT(Taluka) AS Taluka from tbl_users WHERE Taluka!=''";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['Taluka']==$rw['Taluka']){ ?> selected <?php } ?> value="<?php echo $rw['Taluka']; ?>"><?php echo $rw['Taluka']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-3 col-6 Pump" id="hidediameter" style="    padding-top: 32px;">
                                        <button type="submit" class="btn btn-success btn-finish">Search</button>
                                        </div>
                                        </div>
                                        </form>
                                         </div>
                                        </div>
  
                            <div class="card-body" style="padding: 0px;">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="tabhome125" role="tabpanel" aria-labelledby="tabhome125-tab">
                                        <?php 
           
            $sql = "SELECT tp.*,tc.Name As IssueName,tu.BeneficiaryId,tu.EmailId,tu.ProjectType FROM tbl_service_complaint tp
                    LEFT JOIN tbl_issues tc ON tc.id=tp.Issue 
                    LEFT JOIN tbl_users tu ON tu.id=tp.CustId WHERE tp.EnggAssignId='$user_id' AND tp.ClainStatus='Pending' AND tp.ServiceType='Insurance'";
            if($_REQUEST['Village']!=''){
                $sql.=" AND tp.Village='".$_REQUEST['Village']."'";
            }
            if($_REQUEST['Taluka']!=''){
                $sql.=" AND tp.Taluka='".$_REQUEST['Taluka']."'";
            }
            if($_REQUEST['District']!=''){
                $sql.=" AND tp.District='".$_REQUEST['District']."'";
            }
            $sql.= " ORDER BY tp.id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
                    
             ?>
<div class="card mb-4" id="bgcolor<?php echo $row['id'];?>">

                    <div class="card-body">
                        <h6 style="margin-bottom: 1px;font-size: 15px;"><?php echo $row['CustName']; ?></h6>
                      
                         <p style="margin-bottom: 1px;"><strong>(<?php echo $row['BeneficiaryId']; ?>)</strong></p>
                        <p style="margin-bottom: 1px;"><strong>Contact No :</strong> <?php echo $row['CellNo']; ?> </p>
                        <?php if($row['EmailId']!=''){?>
                        <p style="margin-bottom: 1px;"><strong>Email Id :</strong> <?php echo $row['EmailId']; ?></p>
                    <?php } if($row['Address']!=''){?>
                      <p style="margin-bottom: 1px;"><strong>Address :</strong> <?php echo $row['Address']; ?> </p>
                  <?php } ?>

                      <p style="margin-bottom: 1px;"><strong>Project Type :</strong>  <?php if($row["ProjectType"]=='1') {?> <span style="color:red;font-weight:bold;font-size: 17px;">Pump Projects</span> 
                        <?php } else { ?> <span style="color:#2000ff;font-weight:bold;font-size: 17px;">Rooftop Projects </span><?php } ?></p>
                       
                   <p style="margin-bottom: 1px;"><strong>Service Related Issue :</strong> <?php echo $row['RelatedIssue']; ?> </p>
                   <p style="margin-bottom: 1px;"><strong>Issue :</strong> <?php echo $row['IssueName']; ?> </p>
                   <p style="margin-bottom: 1px;"><strong>Service Type :</strong> <?php echo $row['ServiceType']; ?> </p>
                   <p style="margin-bottom: 1px;">
                     <a href="take-insurance-complaint-action.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-finish">Take Action</a>
                   </p>
                                                       
                    </div>
                </div>
                <?php }  ?>

                

                                    </div>
                                
                                   
                                </div>
                            </div>

   
               
                </div>

 
<br><br>




<?php include_once 'footer.php'; ?>

</div>

</main>
<br><br>
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
         paging: false,
    ordering: false,
    info: false,
    });
});
</script>
</body>
</html>
