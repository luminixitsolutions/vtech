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
        <!--  <li class="sidenav-item">
            <a href="prev-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Previous Dashboard</div>
                
            </a>
        </li> -->
    
     <?php  if(in_array("44", $Options) || in_array("45", $Options) || in_array("46", $Options) || in_array("47", $Options) || in_array("48", $Options) || in_array("49", $Options) || in_array("50", $Options) || in_array("51", $Options) || in_array("52", $Options) || in_array("63", $Options)) { ?>
     
        <li class="sidenav-item">
            <a href="lead-management-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Lead Management</div>
                
            </a>
        </li>
 <?php }  if(in_array("1", $Options) || in_array("2", $Options) || in_array("3", $Options) || in_array("4", $Options) || in_array("5", $Options) || in_array("6", $Options) || in_array("7", $Options) || in_array("8", $Options) || in_array("9", $Options) || in_array("12", $Options) || in_array("13", $Options) || in_array("15", $Options) || in_array("16", $Options) || in_array("53", $Options) || in_array("54", $Options)) { ?>
        <li class="sidenav-item">
            <a href="masters-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Master Management</div>
                
            </a>
        </li>
<?php } ?>

 <?php if(in_array("24", $Options)) {?>
        <li class="sidenav-item">
            <a href="product-managment-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Product Management</div>
                
            </a>
        </li>
<?php } if(in_array("18", $Options) || in_array("19", $Options) || in_array("20", $Options) || in_array("21", $Options) || in_array("22", $Options) || in_array("23", $Options)) {?>
        <li class="sidenav-item">
            <a href="account-managment-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>User Account Management</div>
                
            </a>
        </li>

<?php } if(in_array("55", $Options)) {?>
        <li class="sidenav-item">
<a href="assign-customers-to-co-ordinator.php" class="sidenav-link">
     <i class="sidenav-icon feather icon-home"></i>
<div> Assign Customers To Co-ordinator</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>       


<?php if(in_array("27", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Quotation') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Performa Invoice (PI)</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("56", $Options)) {?>
<li class="sidenav-item">
<a href="view-quotation-products.php" class="sidenav-link">
<div> PI/Quotation Products</div>
<?php if($Page=='Quotation-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="add-quotation.php" class="sidenav-link">
<div> Create PI</div>
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
<div> View PI</div>
<?php if($Page=='View-Quotation') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } ?>

<?php if(in_array("57", $Options)) {?>
 <li class="sidenav-item">
            <a href="view-bill-amount-status.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Bill Amount Status</div>
                <?php if($Page=='Bill-Amount-Status') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>


<?php } if(in_array("58", $Options)) {?>
         <li class="sidenav-item">
            <a href="assign-to-store-incharge.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Assign To Store Incharge</div>
                <?php if($Page=='Assign-Store-Incharge') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php } if(in_array("59", $Options)) {?>
        <li class="sidenav-item">
            <a href="approve-store-incharge.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Approve By Store Incharge</div>
                <?php if($Page=='Approve-Store-Incharge') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
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

<?php } if(in_array("60", $Options)) {?>

 <li class="sidenav-item">
            <a href="assign-to-dispatch-officer.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Assign Order To Dispatch Officer</div>
                <?php if($Page=='Assign-Dispatch-Officer') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
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
<?php } ?>

<?php if(in_array("42", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Delivery-Products') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Delivery Products</div>
</a>
<ul class="sidenav-menu">
  
<li class="sidenav-item">
<a href="delivery-customers.php" class="sidenav-link">
<div> Delivery Customers</div>
<?php if($Page=='Delivery-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="pending-customers.php" class="sidenav-link">
<div> Pending Customers</div>
<?php if($Page=='Pending-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="completed-customers.php" class="sidenav-link">
<div> Completed Customers</div>
<?php if($Page=='Completed-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>

<?php } if(in_array("67", $Options) || in_array("68", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Installation') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Installation</div>
</a>
<ul class="sidenav-menu">
  <?php if(in_array("67", $Options)) {?>
<li class="sidenav-item">
<a href="rooftop-installation.php" class="sidenav-link">
<div> Rooftop Installation</div>
<?php if($Page=='Rooftop-Installation') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("68", $Options)) {?>
<li class="sidenav-item">
<a href="pump-installation.php" class="sidenav-link">
<div> Pump Installation</div>
<?php if($Page=='Pump-Installation') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
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



<?php }  if(in_array("61", $Options)) {?>

<li class="sidenav-item">
            <a href="warranty-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Warranty Management</div>
                
            </a>
        </li>
   
<?php }  if(in_array("69", $Options)) {?>

<li class="sidenav-item">
            <a href="dealer-commission.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Dealer Commission</div>
                <?php if($Page=='Dealer-Commission') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
            </a>
        </li>

<?php } if(in_array("37", $Options)) {?>
        <li class="sidenav-item">
            <a href="ecommerce-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>E-Commerce Management</div>
                
            </a>
        </li>
 

<?php } if(in_array("29", $Options) || in_array("30", $Options) || in_array("31", $Options) || in_array("38", $Options) || in_array("40", $Options) || in_array("65", $Options)) {?>
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
<?php } if(in_array("38", $Options)) {?>

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
<?php } if(in_array("39", $Options)) {?>
 <li class="sidenav-item">
<a href="daily-record-report.php" class="sidenav-link">
<div> Daily Record Report</div>
<?php if($Page=='Daily-Record-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } if(in_array("40", $Options)) {?>
 <li class="sidenav-item">
<a href="attendance-report.php" class="sidenav-link">
<div> Attendance Report</div>
<?php if($Page=='Attendance-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 

<?php } if(in_array("65", $Options)) {?>
<li class="sidenav-item">
<a href="dealer-report.php" class="sidenav-link">
<div> Dealer Report</div>
<?php if($Page=='Dealer-Report') {?>
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