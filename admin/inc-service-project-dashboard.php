<!-- ======================== -->
<!--     MAINTENANCE COMPLAINTS -->
<!-- ======================== -->

<h4 class="mb-3 mt-4" style="font-weight:600; color:#333; border-bottom:2px solid #ccc; padding-bottom:6px;">
    Maintenance Complaints
</h4>

<div class="row">

    <!-- Total Maintenance Complaints -->
    <div class="col-sm-6 col-xl-2">
        <a href="view-maintenance.php?ServiceType=Maintaince&Status=All&subheadid=<?php echo $_GET['id']; ?>&projid=<?php echo $_GET['prjid'];?>">
            <div class="card mb-4 bg-pattern-3-dark">
                <div class="card-body" style="padding:15px;">
                    <h6 class="mb-0" style="color:black;">Total Maintenance<br> Complaints</h6>
                    <div class="text-large">
                        <?php
                            $sqlM1 = "SELECT ts.* FROM tbl_service_complaint ts
                                      INNER JOIN tbl_users tu ON tu.id = ts.CustId
                                      WHERE ts.ServiceType='Maintaince'
                                      AND tu.ProjectSubHeadId='".$_GET['id']."'
                                      AND tu.ProjectType=1";
                            echo $cntM1 = getRow($sqlM1);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Resolved Maintenance Complaints -->
    <div class="col-sm-6 col-xl-2">
        <a href="view-maintenance.php?ServiceType=Maintaince&Status=Resolved&subheadid=<?php echo $_GET['id']; ?>&projid=<?php echo $_GET['prjid'];?>">
            <div class="card mb-4 bg-pattern-3-dark">
                <div class="card-body" style="padding:15px;">
                    <h6 class="mb-0" style="color:black;">Resolved Maintenance<br> Complaints</h6>
                    <div class="text-large">
                        <?php
                            $sqlM2 = "SELECT ts.* FROM tbl_service_complaint ts
                                      INNER JOIN tbl_users tu ON tu.id = ts.CustId
                                      WHERE ts.ServiceType='Maintaince'
                                      AND ts.ClainStatus='Close'
                                      AND tu.ProjectSubHeadId='".$_GET['id']."'
                                      AND tu.ProjectType=1";
                            echo $cntM2 = getRow($sqlM2);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Pending Maintenance Complaints -->
    <div class="col-sm-6 col-xl-2">
        <a href="view-maintenance.php?ServiceType=Maintaince&Status=Pending&subheadid=<?php echo $_GET['id']; ?>&projid=<?php echo $_GET['prjid'];?>">
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



<!-- ======================== -->
<!--      INSURANCE COMPLAINTS -->
<!-- ======================== -->

<h4 class="mb-3 mt-4" style="font-weight:600; color:#333; border-bottom:2px solid #ccc; padding-bottom:6px;">
    Insurance Complaints
</h4>

<div class="row">

    <!-- Total Insurance Complaints -->
    <div class="col-sm-6 col-xl-2">
        <a href="view-maintenance.php?ServiceType=Insurance&Status=All&subheadid=<?php echo $_GET['id']; ?>&projid=<?php echo $_GET['prjid'];?>">
            <div class="card mb-4 bg-pattern-3-dark">
                <div class="card-body" style="padding:15px;">
                    <h6 class="mb-0" style="color:black;">Total Insurance<br> Complaints</h6>
                    <div class="text-large">
                        <?php
                            $sqlI1 = "SELECT ts.* FROM tbl_service_complaint ts
                                      INNER JOIN tbl_users tu ON tu.id = ts.CustId
                                      WHERE ts.ServiceType='Insurance'
                                      AND tu.ProjectSubHeadId='".$_GET['id']."'
                                      AND tu.ProjectType=1";
                            echo $cntI1 = getRow($sqlI1);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Resolved Insurance Complaints -->
    <div class="col-sm-6 col-xl-2">
        <a href="view-maintenance.php?ServiceType=Insurance&Status=Resolved&subheadid=<?php echo $_GET['id']; ?>&projid=<?php echo $_GET['prjid'];?>">
            <div class="card mb-4 bg-pattern-3-dark">
                <div class="card-body" style="padding:15px;">
                    <h6 class="mb-0" style="color:black;">Resolved Insurance<br> Complaints</h6>
                    <div class="text-large">
                        <?php
                            $sqlI2 = "SELECT ts.* FROM tbl_service_complaint ts
                                      INNER JOIN tbl_users tu ON tu.id = ts.CustId
                                      WHERE ts.ServiceType='Insurance'
                                      AND ts.ClainStatus='Close'
                                      AND tu.ProjectSubHeadId='".$_GET['id']."'
                                      AND tu.ProjectType=1";
                            echo $cntI2 = getRow($sqlI2);
                        ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Pending Insurance Complaints -->
    <div class="col-sm-6 col-xl-2">
        <a href="view-maintenance.php?ServiceType=Insurance&Status=Pending&subheadid=<?php echo $_GET['id']; ?>&projid=<?php echo $_GET['prjid'];?>">
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
