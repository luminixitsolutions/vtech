<?php 
	$user_id = $_SESSION['Admin']['id'];
	 $sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
	$row77 = getRecord($sql77);
	$Roll = $row77['Roll'];
	$UserCat = $row77['CatId'];
	$Options = explode(',',$row77['Options']);
	$BranchId = $row77['BranchId'];
 ?>
<div class="page-loader">
    <div class="bg-primary"></div>
</div>
 <?php if($Roll == 1 || $Roll == 7){?>
 <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
     <div class="app-brand demo">
                    <span class="app-brand-logo demo">
                        <a href="dashboard.php"><img src="logo.jpg" alt="Brand Logo" class="img-fluid" style="width: 185px;"></a>
                    </span>
                   <!-- <a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2"><?php echo $Proj_Title; ?></a>-->
                    <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
                        <i class="ion ion-md-menu align-middle"></i>
                    </a>
                </div>
                <div class="sidenav-divider mt-0"></div>
    <ul class="sidenav-inner">
        <li class="sidenav-item">
            <a href="dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li>
        <li class="sidenav-item">
            <a href="lead-management-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Lead Dashboard</div>
                
            </a>
        </li>
        <?php if(in_array("64", $Options)) {?>
    <li class="sidenav-item">
            <a href="upload-excel.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Upload Excel</div>
               
            </a>
        </li>
        <?php } if(in_array("44", $Options)) {?>
        <li class="sidenav-item">
            <a href="add-lead.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Lead Creation</div>
                
            </a>
        </li>
        <?php } if(in_array("45", $Options)) {?>
        <li class="sidenav-item">
            <a href="view-leads.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>View Leads</div>
                
            </a>
        </li>
        <?php } if(in_array("46", $Options)) {?>
        <li class="sidenav-item">
            <a href="assign-leads.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Lead Assign</div>
                
            </a>
        </li>
        <?php } if(in_array("47", $Options)) {?>
         <li class="sidenav-item">
            <a href="view-leads-calling.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>To do Activity</div>
               
            </a>
        </li>
        <?php } if(in_array("48", $Options)) {?>
        <!-- <li class="sidenav-item">
            <a href="appointment-scheduling.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Appointment Scheduling</div>
                
            </a>
        </li> -->
        <?php } if(in_array("63", $Options)) {?>
       
        <li class="sidenav-item">
            <a href="lead-completed-customers.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Prospects Customers</div>
               
            </a>
        </li>
        <?php } if(in_array("50", $Options)) {?>
        <li class="sidenav-item">
            <a href="lead-quotation.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Quotation</div>
               
            </a>
        </li>
<?php } if(in_array("49", $Options)) {?>
        <li class="sidenav-item">
            <a href="opportunity.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Opportunity</div>
               
            </a>
        </li>
<?php } if(in_array("51", $Options)) {?>
        <li class="sidenav-item">
            <a href="opportunity-convert-to-order.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Opportunity Convert to Order</div>
               
            </a>
        </li>

       
       <?php } if(in_array("52", $Options)) {?>
 <li class="sidenav-item">
            <a href="social-media-marketing.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Social Media Marketing</div>
               
            </a>
        </li>
        
         <?php } ?>
    </ul>
</div>
<?php } else { 
    include 'employee-sidebar.php';
} ?>