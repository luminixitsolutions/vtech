<div class="row text-center mt-4">
    <div class="col-6 col-md-3">
    <a href="project-abstract.php?projid=103&subheadid=<?php echo $_GET['id']; ?>&title=<?php echo $_GET['name']; ?>" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center d-flex align-items-center justify-content-center" style="min-height: 100px;">
                <div class="project-name">Project Abstract</div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="project-abstract-of-abstract.php?projid=103&subheadid=<?php echo $_GET['id']; ?>&title=MEDA" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center d-flex align-items-center justify-content-center" style="min-height: 100px;">
                <div class="project-name">Project Abstract of Abstracts</div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Total Selection</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='103' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND ProjectType=1";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Total JSR Done</div>
                <div class="project-count">
                    <?php  
                        $sql47 = "SELECT * FROM tbl_users WHERE ProjectId='103' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=1 AND ProjectType=1";
                        echo $rncnt47 = getRow($sql47);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Total JSR Pending</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='103' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=0 AND ProjectType=1";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Total Cancellation</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='103' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=2 AND ProjectType=1";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Material Dispatch Done</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_sell tdo 
                                 LEFT JOIN tbl_users tu ON tdo.CustId=tu.id 
                                 WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND tu.ProjectType=1";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Material Dispatch Pending</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_sell tdo 
                                 LEFT JOIN tbl_users tu ON tdo.CustId=tu.id 
                                 WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND tu.ProjectType=1";
                        $rncnt4 = getRow($sql4);
                        echo $rncnt47 - $rncnt4;
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Installation Done</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_installations ti 
                                 LEFT JOIN tbl_users tu ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.InstallStatus='Yes' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Installation Pending</div>
                <div class="project-count">
                    <?php  
                        echo $rncnt47 - $rncnt4;
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">ICR Sign Done</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_installations ti 
                                 LEFT JOIN tbl_users tu ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.PoInspection='Yes' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">ICR Sign Pending</div>
                <div class="project-count">
                    <?php echo $rncnt47 - $rncnt4; ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Files Sent to HO Done</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users tu 
                                 LEFT JOIN tbl_installations ti ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SentToHo='Yes' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Files Sent to HO Pending</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users tu 
                                 LEFT JOIN tbl_installations ti ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SentToHo='No' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">File In Hand Done</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users tu 
                                 LEFT JOIN tbl_installations ti ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.FileInHand='Yes' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">File In Hand Pending</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users tu 
                                 LEFT JOIN tbl_installations ti ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.FileInHand='No' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Bill Submitted to Circle Office</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users tu 
                                 LEFT JOIN tbl_installations ti ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' 
                                 AND tu.Roll=5 AND ti.CircleOfficeStatus='Yes' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">RMS Integrated</div>
                <div class="project-count text-primary">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users tu 
                                 LEFT JOIN tbl_installations ti ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' 
                                 AND tu.Roll=5 AND ti.RmsIntegrationStatus='Yes' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Insurance Done</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users tu 
                                 LEFT JOIN tbl_installations ti ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' 
                                 AND tu.Roll=5 AND ti.InsuranceApproval='Yes' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Insurance Pending</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users tu 
                                 LEFT JOIN tbl_installations ti ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' 
                                 AND tu.Roll=5 AND ti.InsuranceApproval='No' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Site Payment Received</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users tu 
                                 LEFT JOIN tbl_installations ti ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' 
                                 AND tu.Roll=5 AND ti.PaymentDone='Yes' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-6 col-md-3">
    <a href="#" class="project-card">
        <div class="card custom-card mb-4">
            <div class="card-body text-center">
                <div class="project-name">Site Payment Pending</div>
                <div class="project-count">
                    <?php  
                        $sql4 = "SELECT * FROM tbl_users tu 
                                 LEFT JOIN tbl_installations ti ON ti.CustId=tu.id 
                                 WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' 
                                 AND tu.Roll=5 AND ti.PaymentDone='No' AND ti.Type=2";
                        echo $rncnt4 = getRow($sql4);
                    ?>
                </div>
            </div>
        </div>
    </a>
</div>


</div>