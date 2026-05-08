
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
                <div>Dashboard</div>
                <?php if($Page=='Dashboard') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>





<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-aperture"></i>
                <div>Masters</div>
            </a>
            <ul class="sidenav-menu">


	<?php if(in_array("1", $Options)) {?>
	<li class="sidenav-item <?php if($Page=='Location') {?> open active <?php } ?>">
	<a href="javascript:" class="sidenav-link sidenav-toggle">
	<div>Locations</div>
	</a>
	<ul class="sidenav-menu">
	<li class="sidenav-item">
	<a href="country.php" class="sidenav-link">
	<div>Country</div>
	<?php if($Page2=='Country') {?>
	<div class="pl-1 ml-auto">
	<span class="badge badge-dot badge-primary"></span>
	</div>
	<?php } ?>	
	</a>
	</li>
	<li class="sidenav-item">
	<a href="state.php" class="sidenav-link">
	<div>State</div>
	<?php if($Page2=='State') {?>
	<div class="pl-1 ml-auto">
	<span class="badge badge-dot badge-primary"></span>
	</div>
	<?php } ?>	
	</a>
	</li>
	<li class="sidenav-item">
	<a href="city.php" class="sidenav-link">
	<div>City</div>
	<?php if($Page2=='City') {?>
	<div class="pl-1 ml-auto">
	<span class="badge badge-dot badge-primary"></span>
	</div>
	<?php } ?>	
	</a>
	</li>
<!--	<li class="sidenav-item">
	<a href="area.php" class="sidenav-link">
	<div>Area</div>
	<?php if($Page2=='Area') {?>
	<div class="pl-1 ml-auto">
	<span class="badge badge-dot badge-primary"></span>
	</div>
	<?php } ?>	
	</a>
	</li>-->
	</ul>
	</li>
	<?php } if(in_array("2", $Options)) {?>
	
<li class="sidenav-item">
<a href="branches.php" class="sidenav-link">

<div> Branches</div>
<?php if($Page=='Branch') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("3", $Options)) {?>
<li class="sidenav-item">
<a href="issues.php" class="sidenav-link">
<div> Issues</div>
<?php if($Page=='Branch') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("4", $Options)) {?>
<li class="sidenav-item">
<a href="scheme.php" class="sidenav-link">
<div> Scheme</div>
<?php if($Page=='Scheme') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("5", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="user-type.php" class="sidenav-link">
<div>User Type </div> 
<?php if($Page=='UserType') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("6", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=1" class="sidenav-link">
<div>Pump Head </div> 
<?php if($Page=='PumpHead') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("7", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=2" class="sidenav-link">
<div>Pump Capacity </div> 
<?php if($Page=='PumpCapacity') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("8", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=3" class="sidenav-link">
<div>Water Source </div> 
<?php if($Page=='WaterSource') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("9", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=4" class="sidenav-link">
<div>Surface </div> 
<?php if($Page=='Surface') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("12", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=7" class="sidenav-link">
<div>Bore Dia </div> 
<?php if($Page=='Bore-Dia') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("13", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=8" class="sidenav-link">
<div>Customer Type </div> 
<?php if($Page=='Customer-Type') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("34", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=9" class="sidenav-link">
<div>Insurance Agency</div> 
<?php if($Page=='Insurance-Agency') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("15", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=5" class="sidenav-link">
<div>Insurance Claim Reason</div> 
<?php if($Page=='Insurance-Reason') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("16", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=6" class="sidenav-link">
<div>Insurance Claim Status</div> 
<?php if($Page=='Insurance-Status') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>


</ul>
        </li>

<?php  if(in_array("17", $Options)) {?>
 <li class="sidenav-item">
            <a href="product-specification.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Product Specification</div>
                <?php if($Page=='Product-Specification') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php } if(in_array("18", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Customers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Customers</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>   
<li class="sidenav-item">
<a href="add-customer.php" class="sidenav-link">
<div> Add Customer</div>
<?php if($Page=='Add-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-customers.php" class="sidenav-link">
<div> View Customers</div>
<?php if($Page=='View-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>




</ul>
</li>



<?php } if(in_array("19", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Manufacture') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Manufacture</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>   
<li class="sidenav-item">
<a href="add-manufacture.php" class="sidenav-link">
<div> Add Manufacture</div>
<?php if($Page=='Add-Manufacture') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-manufacture.php" class="sidenav-link">
<div> View Manufactures</div>
<?php if($Page=='View-Manufacture') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>

<?php } if(in_array("20", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Company') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Company</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>   
<li class="sidenav-item">
<a href="add-company.php" class="sidenav-link">
<div> Add Company</div>
<?php if($Page=='Add-Company') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-company.php" class="sidenav-link">
<div> View Company</div>
<?php if($Page=='View-Company') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>
<?php } if(in_array("21", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Employee') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Employee</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-employee.php" class="sidenav-link">
<div> Add Employee</div>
<?php if($Page=='Add-Employee') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-employee.php" class="sidenav-link">
<div> View Employee</div>
<?php if($Page=='View-Employee') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>

<?php } if(in_array("22", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Dealer') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Dealer</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-dealer.php" class="sidenav-link">
<div> Add Dealer</div>
<?php if($Page=='Add-Dealer') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-dealer.php" class="sidenav-link">
<div> View Dealer</div>
<?php if($Page=='View-Dealer') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>
<?php } if(in_array("23", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Agency') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Agency</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-agency.php" class="sidenav-link">
<div> Add Agency</div>
<?php if($Page=='Add-Agency') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-agency.php" class="sidenav-link">
<div> View Agency</div>
<?php if($Page=='View-Agency') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>


<!-- <li class="sidenav-item <?php if($MainPage=='Executive') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Executive</div>
</a>
<ul class="sidenav-menu">

<li class="sidenav-item">
<a href="add-executive.php" class="sidenav-link">
<div> Add Executive</div>
<?php if($Page=='Add-Executive') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="view-executive.php" class="sidenav-link">
<div> View Executive</div>
<?php if($Page=='View-Executive') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li> -->
<?php } if(in_array("24", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Products') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-box"></i>
<div>Products</div>
</a>
<ul class="sidenav-menu">

<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="add-product.php" class="sidenav-link">
<div> Add Product</div>
<?php if($Page=='Add-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-products.php" class="sidenav-link">
<div> View Products</div>
<?php if($Page=='View-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>





<?php } if(in_array("25", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Purchase-Order') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
    <i class="sidenav-icon feather icon-save"></i>
<div>Purchase Order</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="add-purchase-order.php" class="sidenav-link">
<div> Add Purchase Order</div>
<?php if($Page=='Add-Purchase-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-purchase-order.php" class="sidenav-link">
<div> View Purchase Order</div>
<?php if($Page=='View-Purchase-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>

<?php } if(in_array("26", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Sell') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-printer"></i>
<div>Delivery Challan</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="add-sell.php" class="sidenav-link">
<div> Add Delivery Challan</div>
<?php if($Page=='Add-Sell') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-sells.php" class="sidenav-link">
<div> View Delivery Challan</div>
<?php if($Page=='View-Sell') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("27", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Quotation') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Quotation</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="add-quotation.php" class="sidenav-link">
<div> Add Quotation</div>
<?php if($Page=='Add-Quotation') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-quotation.php" class="sidenav-link">
<div> View Quotation</div>
<?php if($Page=='View-Quotation') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>


<?php } if(in_array("35", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Work-Order') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Work Order</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="add-work-order.php" class="sidenav-link">
<div> Add Work Order</div>
<?php if($Page=='Add-Work-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-work-order.php" class="sidenav-link">
<div> View Work Order</div>
<?php if($Page=='View-Work-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("28", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='service') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Service Complaint</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="service-module.php" class="sidenav-link">
<div> Add Service Complaint</div>
<?php if($Page=='Add-Sell') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-service-module.php" class="sidenav-link">
<div> View Service Complaint</div>
<?php if($Page=='View-Sell') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>

<?php } ?>
<li class="sidenav-item <?php if($MainPage=='Report') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-bar-chart"></i>
<div>Report</div>
</a>
<ul class="sidenav-menu"> 
 <?php  if(in_array("29", $Options)) {?>
<li class="sidenav-item">
<a href="sell-report.php" class="sidenav-link">
<div> Delivery Challan Report</div>
<?php if($Page=='Sell-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>


<?php } if(in_array("30", $Options)) {?>
<li class="sidenav-item">
<a href="stock-report2.php" class="sidenav-link">
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
<div> Outstanding Stock Report</div>
<?php if($Page=='Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>

<li class="sidenav-item">
<a href="all-customer-report.php" class="sidenav-link">
<div> Customer Report</div>
<?php if($Page=='Customer-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li> 

<!--<li class="sidenav-item <?php if($MainPage=='Users') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-users"></i>
<div>User Account</div>
</a>
<ul class="sidenav-menu">

<li class="sidenav-item">
<a href="add-user.php" class="sidenav-link">
<div> Add User</div>
<?php if($Page=='Add-Users') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="view-users.php" class="sidenav-link">
<div> View Users</div>
<?php if($Page=='View-Users') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>-->
<?php  if(in_array("32", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Insurance') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Insurance Claim</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="add-insurance-claim.php" class="sidenav-link">
<div> Add Insurance Claim</div>
<?php if($Page=='Add-Insurance-Claim') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-insurance-claim.php" class="sidenav-link">
<div> View Insurance Claim</div>
<?php if($Page=='View-Insurance-Claim') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>

<?php } if(in_array("33", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Feedback') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Calling</div>
</a>
<ul class="sidenav-menu"> 
<li class="sidenav-item">
<a href="view-purchase-feedback.php" class="sidenav-link">
<div> Calling Customer List</div>
<?php if($Page=='Product-Feedback') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-next-call-list.php" class="sidenav-link">
<div>  Today Calling List</div>
<?php if($Page=='Product-Feedback-Calling') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-completed-list.php" class="sidenav-link">
<div>  Completed Customer List</div>
<?php if($Page=='Completed-Customer-List') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li> 
<?php } ?>

        <li class="sidenav-item <?php if($MainPage=='Account') {?> open active <?php } ?>">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-settings"></i>
                <div><?php echo $row77['Fname']." ".$row77['Lname']; ?></div>
            </a>
            <ul class="sidenav-menu">
<?php 
 if($Roll == 1){?>
 <li class="sidenav-item ">
                    <a href="company-information.php" class="sidenav-link">
                        <div><i class="feather icon-unlock text-muted"></i> Company Profile</div>
                        <?php if($Page=='Company-Profile') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
            <?php } ?>
                <li class="sidenav-item ">
                    <a href="change-password.php" class="sidenav-link">
                        <div><i class="feather icon-unlock text-muted"></i> Change Password</div>
                        <?php if($Page=='Change-Password') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                <li class="sidenav-item">
                    <a href="logout.php" class="sidenav-link">
                        <div><i class="feather icon-power text-danger"></i> Log Out</div>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</div>