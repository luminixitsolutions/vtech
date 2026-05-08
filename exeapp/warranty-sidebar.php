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
            <a href="warranty-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Warranty Dashboard</div>
                
            </a>
        </li>
        <!-- <li class="sidenav-item">
            <a href="#" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Customer Management</div>
                
            </a>
        </li>
        <li class="sidenav-item">
            <a href="#" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Serialized Product Registration</div>
                
            </a>
        </li> -->
        <li class="sidenav-item">
            <a href="view-warranty-registration.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Warranty Registration</div>
                
            </a>
        </li>

        <li class="sidenav-item">
            <a href="warranty-customers.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Warranty Customers</div>
                
            </a>
        </li>

        <li class="sidenav-item">
            <a href="no-warranty-customers.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>No Warranty Customers</div>
                
            </a>
        </li>
        
        
    </ul>
</div>