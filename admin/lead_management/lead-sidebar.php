<?php 
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$UserCat = $row77['CatId'];
$Options = explode(',',$row77['Options']);
?>

            <!-- [ Layout navbar ( Header ) ] End -->
            <!-- [ Layout sidenav ] Start -->
            <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
     <div class="app-brand demo">
                    <span class="app-brand-logo demo">
                        <a href="../dashboard.php"><img src="../logo.jpg" alt="Brand Logo" class="img-fluid" style="width: 185px;"></a>
                    </span>
                   <!-- <a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2"><?php echo $Proj_Title; ?></a>-->
                    <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
                        <i class="ion ion-md-menu align-middle"></i>
                    </a>
                </div>
                <div class="sidenav-divider mt-0"></div>
    <ul class="sidenav-inner">
        <li class="sidenav-item">
            <a href="../dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-user"></i>
                <div><?php echo $row77['Fname']." ".$row77['Lname']; ?></div>
                
            </a>
        </li> 
        <li class="sidenav-item">
            <a href="../dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li>
        <li class="sidenav-item">
            <a href="lead-management-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Lead Dashboard</div>
                
            </a>
        </li>
        <?php if(in_array("64", $Options)) {?>
    <li class="sidenav-item">
            <a href="upload-excel.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Upload Excel</div>
               
            </a>
        </li>
        <?php } if(in_array("44", $Options)) {?>
        <li class="sidenav-item">
            <a href="add-irrigation-leads.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Lead Irrigation</div>
                
            </a>
        </li>
        <li class="sidenav-item">
            <a href="add-lead.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Lead Creation</div>
                
            </a>
        </li>
        <?php } if(in_array("45", $Options)) {?>
        <li class="sidenav-item">
            <a href="view-leads.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>View Leads</div>
                
            </a>
        </li>
        <?php } if(in_array("46", $Options)) {?>
        <li class="sidenav-item">
            <a href="assign-leads.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Lead Assign</div>
                
            </a>
        </li>
        <?php } if(in_array("47", $Options)) {?>
         <li class="sidenav-item">
            <a href="view-leads-calling.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>To do Activity</div>
               
            </a>
        </li>
        <?php } if(in_array("48", $Options)) {?>
        <!-- <li class="sidenav-item">
            <a href="appointment-scheduling.php" class="sidenav-link">
                
                <div>Appointment Scheduling</div>
                
            </a>
        </li> -->
        <?php } if(in_array("63", $Options)) {?>
       
        <li class="sidenav-item">
            <a href="lead-completed-customers.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Prospects Customers</div>
               
            </a>
        </li>
        <?php } if(in_array("50", $Options)) {?>
        <!-- <li class="sidenav-item">
            <a href="lead-quotation.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Quotation</div>
               
            </a>
        </li> -->
<?php } if(in_array("49", $Options)) {?>
        <!-- <li class="sidenav-item">
            <a href="opportunity.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Opportunity</div>
               
            </a>
        </li> -->
<?php } if(in_array("51", $Options)) {?>
        <li class="sidenav-item">
            <a href="opportunity-convert-to-order.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Opportunity Convert to Order</div>
               
            </a>
        </li>

       
       <?php } if(in_array("52", $Options)) {?>
 <!-- <li class="sidenav-item">
            <a href="social-media-marketing.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Social Media Marketing</div>
               
            </a>
        </li> -->
        

         
          <?php } if(in_array("149", $Options)) {?>
         <li class="sidenav-item <?php if($MainPage=='Assign-Pump-Customers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Pump Application Form</div>
</a>
<ul class="sidenav-menu">
  
  <li class="sidenav-item">
<a href="upload-application-excel.php" class="sidenav-link">
<div> Upload Excel</div>
<?php if($Page=='Upload-Excel') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

  <li class="sidenav-item">
<a href="view-application-form.php" class="sidenav-link">
<div> All Application Form</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>


<li class="sidenav-item">
<a href="pending-application-form.php" class="sidenav-link">
<div> Pending Application Form</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

        <li class="sidenav-item">
<a href="approve-application-form.php" class="sidenav-link">
<div> Approve Application Form</div>
<?php if($Page=='Assign-Customers-Field-Survey') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>  

<li class="sidenav-item">
<a href="reject-application-form.php" class="sidenav-link">
<div> Reject Application Form</div>
<?php if($Page=='Assign-Customers-Field-Survey') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>  

</ul>
</li>
 <?php } if(in_array("150", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Assign-Pump-Customers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Assign Application Form</div>
</a>
<ul class="sidenav-menu">
  
  <li class="sidenav-item">
<a href="assign-applications.php" class="sidenav-link">
<div> Assign Application Form</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>


<li class="sidenav-item">
<a href="assigned-applications.php" class="sidenav-link">
<div> Assigned Application Form</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>
 <?php } if(in_array("151", $Options) || in_array("152", $Options) || in_array("153", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Assign-Pump-Customers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Activity On Application Form</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("151", $Options)) {?>
  <li class="sidenav-item">
<a href="view-application-calling.php" class="sidenav-link">
<div> To Do Activity</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("152", $Options)) {?>

<li class="sidenav-item">
<a href="application-completed-customers.php" class="sidenav-link">
<div> Prospects Applications</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("153", $Options)) {?>
<li class="sidenav-item">
<a href="applications-convert-to-order.php" class="sidenav-link">
<div> Applications Convert To Order</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
</ul>
</li>
<?php } ?>
       

    </ul>
</div>