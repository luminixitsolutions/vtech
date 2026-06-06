<?php
$msedclSmartCan = function ($optionId) {
    return msedclSmartCanAccessOption($optionId);
};
?>
            <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
                <div class="app-brand demo">
                    <span class="app-brand-logo demo">
                        <a href="../dashboard.php"><img src="../logo.jpg" alt="Brand Logo" class="img-fluid" style="width: 185px;"></a>
                    </span>
                    <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
                        <i class="ion ion-md-menu align-middle"></i>
                    </a>
                </div>
                <div class="sidenav-divider mt-0"></div>
                <ul class="sidenav-inner">
                    <li class="sidenav-item">
                        <a href="../dashboard.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-user"></i>
                            <div><?php echo htmlspecialchars(trim(($row77['Fname'] ?? '') . ' ' . ($row77['Lname'] ?? ''))); ?></div>
                        </a>
                    </li>
                    <li class="sidenav-item">
                        <a href="../dashboard.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-home"></i>
                            <div>Home</div>
                        </a>
                    </li>
                    <?php if ($msedclSmartCan(MSEDCL_SMART_OPT_DASHBOARD)) { ?>
                    <li class="sidenav-item <?php if ($Page === 'MSEDCL-Smart-Dashboard') { ?> active <?php } ?>">
                        <a href="dashboard.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-activity"></i>
                            <div>Dashboard</div>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($msedclSmartCan(MSEDCL_SMART_OPT_PMSGY)) { ?>
                    <li class="sidenav-item <?php if ($Page === 'MSEDCL-Smart-PMSGY') { ?> active <?php } ?>">
                        <a href="pmsgy.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-upload-cloud"></i>
                            <div>Applications on PMSGY Portal</div>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($msedclSmartCan(MSEDCL_SMART_OPT_MAHADISCOM)) { ?>
                    <li class="sidenav-item <?php if ($Page === 'MSEDCL-Smart-Mahadiscom') { ?> active <?php } ?>">
                        <a href="mahadiscom.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-file-text"></i>
                            <div>Applications on Mahadiscom Portal</div>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($msedclSmartCan(MSEDCL_SMART_OPT_PAYMENT)) { ?>
                    <li class="sidenav-item <?php if ($Page === 'MSEDCL-Smart-Payment') { ?> active <?php } ?>">
                        <a href="payment.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-check-circle"></i>
                            <div>Payment Done by Customers</div>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($msedclSmartCan(MSEDCL_SMART_OPT_SURVEY_PENDING)) { ?>
                    <li class="sidenav-item <?php if ($Page === 'MSEDCL-Smart-Survey-Pending') { ?> active <?php } ?>">
                        <a href="survey-pending.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-clock"></i>
                            <div>Survey Pending</div>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($msedclSmartCan(MSEDCL_SMART_OPT_ABSTRACT)) { ?>
                    <li class="sidenav-item <?php if ($Page === 'MSEDCL-Smart-Abstract') { ?> active <?php } ?>">
                        <a href="abstract.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-map"></i>
                            <div>MSEDCL SMART PROJECT ABSTRACT</div>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
