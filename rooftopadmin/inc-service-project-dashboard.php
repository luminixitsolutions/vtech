<!-- ======================== -->
<!--     MAINTENANCE COMPLAINTS -->
<!-- ======================== -->

<h4 class="mb-3 mt-4" style="font-weight:600; color:#333; border-bottom:2px solid #ccc; padding-bottom:6px;">
    Maintenance Complaints
</h4>

<div class="row">

    <div class="col-sm-6 col-xl-2">
        <a href="view-service-module.php?ServiceType=Maintaince&Status=All&subheadid=<?php echo (int) $_GET['id']; ?>&projid=<?php echo (int) $_GET['prjid']; ?>">
            <div class="card mb-4 bg-pattern-3-dark">
                <div class="card-body" style="padding:15px;">
                    <h6 class="mb-0" style="color:black;">Total Maintenance<br> Complaints</h6>
                    <div class="text-large">
                        <?php
                            $sqlM1 = "SELECT ts.* FROM tbl_rooftop_service_complaint ts
                                      INNER JOIN tbl_users tu ON tu.id = ts.CustId
                                      WHERE ts.ServiceType='Maintaince'
                                      AND tu.ProjectSubHeadId='" . (int) $_GET['id'] . "'
                                      AND tu.ProjectType=2 AND tu.Roll=5";
                            echo $cntM1 = getRow($sqlM1);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-2">
        <a href="view-service-module.php?ServiceType=Maintaince&Status=Resolved&subheadid=<?php echo (int) $_GET['id']; ?>&projid=<?php echo (int) $_GET['prjid']; ?>">
            <div class="card mb-4 bg-pattern-3-dark">
                <div class="card-body" style="padding:15px;">
                    <h6 class="mb-0" style="color:black;">Resolved Maintenance<br> Complaints</h6>
                    <div class="text-large">
                        <?php
                            $sqlM2 = "SELECT ts.* FROM tbl_rooftop_service_complaint ts
                                      INNER JOIN tbl_users tu ON tu.id = ts.CustId
                                      WHERE ts.ServiceType='Maintaince'
                                      AND ts.ClainStatus='Close'
                                      AND tu.ProjectSubHeadId='" . (int) $_GET['id'] . "'
                                      AND tu.ProjectType=2 AND tu.Roll=5";
                            echo $cntM2 = getRow($sqlM2);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-2">
        <a href="view-service-module.php?ServiceType=Maintaince&Status=Pending&subheadid=<?php echo (int) $_GET['id']; ?>&projid=<?php echo (int) $_GET['prjid']; ?>">
            <div class="card mb-4 bg-pattern-3-dark">
                <div class="card-body" style="padding:15px;">
                    <h6 class="mb-0" style="color:black;">Pending Maintenance<br> Complaints</h6>
                    <div class="text-large">
                        <?php echo $cntM1 - $cntM2; ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>

<h4 class="mb-3 mt-4" style="font-weight:600; color:#333; border-bottom:2px solid #ccc; padding-bottom:6px;">
    Insurance Complaints
</h4>

<div class="row">

    <div class="col-sm-6 col-xl-2">
        <a href="view-service-module.php?ServiceType=Insurance&Status=All&subheadid=<?php echo (int) $_GET['id']; ?>&projid=<?php echo (int) $_GET['prjid']; ?>">
            <div class="card mb-4 bg-pattern-3-dark">
                <div class="card-body" style="padding:15px;">
                    <h6 class="mb-0" style="color:black;">Total Insurance<br> Complaints</h6>
                    <div class="text-large">
                        <?php
                            $sqlI1 = "SELECT ts.* FROM tbl_rooftop_service_complaint ts
                                      INNER JOIN tbl_users tu ON tu.id = ts.CustId
                                      WHERE ts.ServiceType='Insurance'
                                      AND tu.ProjectSubHeadId='" . (int) $_GET['id'] . "'
                                      AND tu.ProjectType=2 AND tu.Roll=5";
                            echo $cntI1 = getRow($sqlI1);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-2">
        <a href="view-service-module.php?ServiceType=Insurance&Status=Resolved&subheadid=<?php echo (int) $_GET['id']; ?>&projid=<?php echo (int) $_GET['prjid']; ?>">
            <div class="card mb-4 bg-pattern-3-dark">
                <div class="card-body" style="padding:15px;">
                    <h6 class="mb-0" style="color:black;">Resolved Insurance<br> Complaints</h6>
                    <div class="text-large">
                        <?php
                            $sqlI2 = "SELECT ts.* FROM tbl_rooftop_service_complaint ts
                                      INNER JOIN tbl_users tu ON tu.id = ts.CustId
                                      WHERE ts.ServiceType='Insurance'
                                      AND ts.ClainStatus='Close'
                                      AND tu.ProjectSubHeadId='" . (int) $_GET['id'] . "'
                                      AND tu.ProjectType=2 AND tu.Roll=5";
                            echo $cntI2 = getRow($sqlI2);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-2">
        <a href="view-service-module.php?ServiceType=Insurance&Status=Pending&subheadid=<?php echo (int) $_GET['id']; ?>&projid=<?php echo (int) $_GET['prjid']; ?>">
            <div class="card mb-4 bg-pattern-3-dark">
                <div class="card-body" style="padding:15px;">
                    <h6 class="mb-0" style="color:black;">Pending Insurance<br> Complaints</h6>
                    <div class="text-large">
                        <?php echo $cntI1 - $cntI2; ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>
