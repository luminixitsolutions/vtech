<?php
$spdSubHeadId = (int) ($_GET['id'] ?? 0);
$spdProjectId = (int) ($_GET['prjid'] ?? 0);

$sqlM1 = "SELECT ts.* FROM tbl_service_complaint ts
    INNER JOIN tbl_users tu ON tu.id = ts.CustId
    WHERE ts.ServiceType='Maintaince'
    AND tu.ProjectSubHeadId='".$spdSubHeadId."'
    AND tu.ProjectType=1";
$cntM1 = getRow($sqlM1);

$sqlM2 = "SELECT ts.* FROM tbl_service_complaint ts
    INNER JOIN tbl_users tu ON tu.id = ts.CustId
    WHERE ts.ServiceType='Maintaince'
    AND ts.ClainStatus='Close'
    AND tu.ProjectSubHeadId='".$spdSubHeadId."'
    AND tu.ProjectType=1";
$cntM2 = getRow($sqlM2);
$cntMPending = (int) $cntM1 - (int) $cntM2;

$sqlI1 = "SELECT ts.* FROM tbl_service_complaint ts
    INNER JOIN tbl_users tu ON tu.id = ts.CustId
    WHERE ts.ServiceType='Insurance'
    AND tu.ProjectSubHeadId='".$spdSubHeadId."'
    AND tu.ProjectType=1";
$cntI1 = getRow($sqlI1);

$sqlI2 = "SELECT ts.* FROM tbl_service_complaint ts
    INNER JOIN tbl_users tu ON tu.id = ts.CustId
    WHERE ts.ServiceType='Insurance'
    AND ts.ClainStatus='Close'
    AND tu.ProjectSubHeadId='".$spdSubHeadId."'
    AND tu.ProjectType=1";
$cntI2 = getRow($sqlI2);
$cntIPending = (int) $cntI1 - (int) $cntI2;
?>

<section class="ipd-section">
    <h4 class="ipd-section-title">
        <i class="feather icon-tool" aria-hidden="true"></i>
        Maintenance Complaints
    </h4>
    <div class="ipd-metric-grid">
        <a href="view-maintenance.php?ServiceType=Maintaince&amp;Status=All&amp;subheadid=<?php echo $spdSubHeadId; ?>&amp;projid=<?php echo $spdProjectId; ?>" class="ipd-stat-link">
            <div class="ipd-stat-card ipd-accent-total">
                <h6 class="ipd-stat-label">Total Maintenance Complaints</h6>
                <div class="ipd-stat-meta">
                    <span class="ipd-stat-count"><?php echo (int) $cntM1; ?></span>
                    <span class="ipd-stat-badge ipd-badge-total">
                        <i class="feather icon-file-text" aria-hidden="true"></i>
                        Total
                    </span>
                </div>
            </div>
        </a>
        <a href="view-maintenance.php?ServiceType=Maintaince&amp;Status=Resolved&amp;subheadid=<?php echo $spdSubHeadId; ?>&amp;projid=<?php echo $spdProjectId; ?>" class="ipd-stat-link">
            <div class="ipd-stat-card ipd-accent-resolved">
                <h6 class="ipd-stat-label">Resolved Maintenance Complaints</h6>
                <div class="ipd-stat-meta">
                    <span class="ipd-stat-count"><?php echo (int) $cntM2; ?></span>
                    <span class="ipd-stat-badge ipd-badge-resolved">
                        <i class="feather icon-check-circle" aria-hidden="true"></i>
                        Resolved
                    </span>
                </div>
            </div>
        </a>
        <a href="view-maintenance.php?ServiceType=Maintaince&amp;Status=Pending&amp;subheadid=<?php echo $spdSubHeadId; ?>&amp;projid=<?php echo $spdProjectId; ?>" class="ipd-stat-link">
            <div class="ipd-stat-card ipd-accent-pending">
                <h6 class="ipd-stat-label">Pending Maintenance Complaints</h6>
                <div class="ipd-stat-meta">
                    <span class="ipd-stat-count"><?php echo $cntMPending; ?></span>
                    <span class="ipd-stat-badge ipd-badge-pending">
                        <i class="feather icon-clock" aria-hidden="true"></i>
                        Pending
                    </span>
                </div>
            </div>
        </a>
    </div>
</section>

<section class="ipd-section">
    <h4 class="ipd-section-title">
        <i class="feather icon-shield" aria-hidden="true"></i>
        Insurance Complaints
    </h4>
    <div class="ipd-metric-grid">
        <a href="view-maintenance.php?ServiceType=Insurance&amp;Status=All&amp;subheadid=<?php echo $spdSubHeadId; ?>&amp;projid=<?php echo $spdProjectId; ?>" class="ipd-stat-link">
            <div class="ipd-stat-card ipd-accent-total">
                <h6 class="ipd-stat-label">Total Insurance Complaints</h6>
                <div class="ipd-stat-meta">
                    <span class="ipd-stat-count"><?php echo (int) $cntI1; ?></span>
                    <span class="ipd-stat-badge ipd-badge-total">
                        <i class="feather icon-file-text" aria-hidden="true"></i>
                        Total
                    </span>
                </div>
            </div>
        </a>
        <a href="view-maintenance.php?ServiceType=Insurance&amp;Status=Resolved&amp;subheadid=<?php echo $spdSubHeadId; ?>&amp;projid=<?php echo $spdProjectId; ?>" class="ipd-stat-link">
            <div class="ipd-stat-card ipd-accent-resolved">
                <h6 class="ipd-stat-label">Resolved Insurance Complaints</h6>
                <div class="ipd-stat-meta">
                    <span class="ipd-stat-count"><?php echo (int) $cntI2; ?></span>
                    <span class="ipd-stat-badge ipd-badge-resolved">
                        <i class="feather icon-check-circle" aria-hidden="true"></i>
                        Resolved
                    </span>
                </div>
            </div>
        </a>
        <a href="view-maintenance.php?ServiceType=Insurance&amp;Status=Pending&amp;subheadid=<?php echo $spdSubHeadId; ?>&amp;projid=<?php echo $spdProjectId; ?>" class="ipd-stat-link">
            <div class="ipd-stat-card ipd-accent-pending">
                <h6 class="ipd-stat-label">Pending Insurance Complaints</h6>
                <div class="ipd-stat-meta">
                    <span class="ipd-stat-count"><?php echo $cntIPending; ?></span>
                    <span class="ipd-stat-badge ipd-badge-pending">
                        <i class="feather icon-clock" aria-hidden="true"></i>
                        Pending
                    </span>
                </div>
            </div>
        </a>
    </div>
</section>
