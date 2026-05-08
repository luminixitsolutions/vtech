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
                 <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li>

          <li class="sidenav-item">
            <a href="report-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Report Dashboard</div>
                
            </a>
        </li> 
          <?php  if(in_array("142", $Options)) {?>
        <li class="sidenav-item">
<a href="contractor-commision-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Contractor Commision Report</div>
<?php if($Page=='Contractor-Commision-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

        <?php } if(in_array("29", $Options)) {?>
 <li class="sidenav-item">
<a href="sell-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Delivery Challan Report</div>
<?php if($Page=='Sell-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } if(in_array("120", $Options)) {?>
 <!--<li class="sidenav-item">
<a href="dispatch-material-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Material Dispatch Report</div>
<?php if($Page=='Material-Dispatch-Reports') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } if(in_array("115", $Options)) {?>
 <li class="sidenav-item">
<a href="trip-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Trip Report</div>
<?php if($Page=='Trip-Reports') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } if(in_array("30", $Options)) {?>
 <li class="sidenav-item">
<a href="stock-report2.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Stock Report</div>
<?php if($Page=='Stock-Report2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } if(in_array("31", $Options)) {?>
 <li class="sidenav-item">
<a href="stock-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Outstanding Stock Report</div>
<?php if($Page=='Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } if(in_array("38", $Options)) {?>
<li class="sidenav-item">
<a href="all-customer-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Customer Report</div>
<?php if($Page=='Customer-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("39", $Options)) {?>
 <!--<li class="sidenav-item">
<a href="daily-record-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Daily Record Report</div>
<?php if($Page=='Daily-Record-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } if(in_array("99", $Options)) {?>
 <li class="sidenav-item">
<a href="attendance-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Attendance Report</div>
<?php if($Page=='Attendance-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
 <li class="sidenav-item">
<a href="attendance-report-2.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Attendance Report 2</div>
<?php if($Page=='Attendance-Report-2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("100", $Options)) {?>
 <li class="sidenav-item">
<a href="vehical-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Vehical Report</div>
<?php if($Page=='Vehical-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("65", $Options)) {?>
<li class="sidenav-item">
<a href="dealer-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Dealer Report</div>
<?php if($Page=='Dealer-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("101", $Options)) {?>
<li class="sidenav-item">
<a href="store-stock-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Store Stock Report</div>
<?php if($Page=='Store-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="store-stock-report-2.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Store Stock Report 2</div>
<?php if($Page=='Store-Stock-Report-2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("102", $Options)) {?>
<li class="sidenav-item">
<a href="store-item-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Store Incharge Stock Report</div>
<?php if($Page=='Store-Incharge-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("103", $Options)) {?>
<li class="sidenav-item">
<a href="dispatch-officer-stock-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Dispatch Officer Stock Report</div>
<?php if($Page=='Dispatch-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<!--<li class="sidenav-item">
<a href="dispatch-stock-report.php" class="sidenav-link">
<div> Dispatch Officer Stock Report</div>
<?php if($Page=='Dispatch-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->
<?php } if(in_array("104", $Options)) {?>
<li class="sidenav-item">
<a href="field-survey-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Field Survey Report</div>
<?php if($Page=='Field-Survey-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("105", $Options)) {?>
<li class="sidenav-item">
<a href="dispatch-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Dispatch Report</div>
<?php if($Page=='Dispatch-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("106", $Options)) {?>
<li class="sidenav-item">
<a href="installation-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Installation Report</div>
<?php if($Page=='Installation-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("107", $Options)) {?>
<li class="sidenav-item">
<a href="inspection-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Inspection Report</div>
<?php if($Page=='Inspection-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("108", $Options)) {?>
<li class="sidenav-item">
<a href="site-engineer-report.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Site Engineer Report</div>
<?php if($Page=='Site-Engineer-Reports') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("109", $Options)) {?>
<li class="sidenav-item">
<a href="dispatch-calling-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Dispatch Calling Report</div>
<?php if($Page=='Dispatch-Calling-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("110", $Options)) {?>
<li class="sidenav-item">
<a href="before-installation-calling-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Before Installation Calling Report</div>
<?php if($Page=='Before-Installation-Calling-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("111", $Options)) {?>
<li class="sidenav-item">
<a href="after-installation-calling-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> After Installation Calling Report</div>
<?php if($Page=='After-Installation-Calling-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("112", $Options)) {?>
<li class="sidenav-item">
<a href="before-inspection-calling-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Before Inspection Calling Report</div>
<?php if($Page=='Before-Inspection-Calling-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("119", $Options)) {?>
<li class="sidenav-item">
<a href="beneficiary-selection-calling-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-activity"></i>
<div> Beneficiary Selection Calling Report</div>
<?php if($Page=='Beneficiary-Selection-Calling-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
        
        
    </ul>
</div>