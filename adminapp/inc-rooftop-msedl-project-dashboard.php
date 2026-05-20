<div class="row text-center mt-4">
<?php
$projid     = $_GET['prjid'];
$subheadid = $_GET['id'];
$title     = $_GET['name'];
?>

<!-- ================= Project Abstract ================= -->
<div class="col-6 col-md-3">
<a href="rooftop-project-abstract.php?projid=<?php echo $projid; ?>&subheadid=<?php echo $subheadid; ?>&title=<?php echo $title; ?>" class="project-card">
<div class="card custom-card mb-4">
<div class="card-body d-flex align-items-center justify-content-center" style="min-height:100px;">
<div class="project-name">Project Abstract</div>
</div>
</div>
</a>
</div>

<!-- ================= Total Applications ================= -->
<div class="col-6 col-md-3">
<a href="#" class="project-card">
<div class="card custom-card mb-4">
<div class="card-body text-center">
<div class="project-name">Total Applications</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='".$_GET['prjid']."' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?>
</div>
</div>
</div>
</a>
</div>

<!-- ================= Total Survey Done ================= -->
<div class="col-6 col-md-3">
<a href="#" class="project-card">
<div class="card custom-card mb-4">
<div class="card-body text-center">
<div class="project-name">Total Survey Done</div>
<div class="project-count">
<?php  
                                                            $sql47 = "SELECT * FROM tbl_users WHERE ProjectId='".$_GET['prjid']."' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=1 AND ProjectType=2";
                                                            echo $rncnt47 = getRow($sql47);

                                                        ?>
</div>
</div>
</div>
</a>
</div>

<!-- ================= Total Survey Pending ================= -->
<div class="col-6 col-md-3">
<a href="#" class="project-card">
<div class="card custom-card mb-4">
<div class="card-body text-center">
<div class="project-name">Total Survey Pending</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='".$_GET['prjid']."' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=0 AND ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?>
</div>
</div>
</div>
</a>
</div>

<!-- ================= Total Cancellation ================= -->
<div class="col-6 col-md-3">
<a href="#" class="project-card">
<div class="card custom-card mb-4">
<div class="card-body text-center">
<div class="project-name">Total Cancellation</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='".$_GET['prjid']."' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=2 AND ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?>
</div>
</div>
</div>
</a>
</div>

<!-- ================= Material Dispatch Done ================= -->
<div class="col-6 col-md-3">
<a href="#" class="project-card">
<div class="card custom-card mb-4">
<div class="card-body text-center">
<div class="project-name">Material Dispatch Done</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_rooftop_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?>
</div>
</div>
</div>
</a>
</div>

<!-- ================= Material Dispatch Pending ================= -->
<div class="col-6 col-md-3">
<a href="#" class="project-card">
<div class="card custom-card mb-4">
<div class="card-body text-center">
<div class="project-name">Material Dispatch Pending</div>
<div class="project-count">
<?php  echo $rncnt47 - $rncnt4;?>
</div>
</div>
</div>
</a>
</div>


<div class="col-6 col-md-3">
<a href="#" class="project-card">
<div class="card custom-card mb-4">
<div class="card-body text-center">
<div class="project-name">Total Installation Done</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.InstallStatus='Yes' AND tu.ProjectType=2";
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
<div class="project-name">Total Installation<br> Pending</div>
<div class="project-count">
<?php  echo $rncnt47 - $rncnt4;?>
</div>
</div>
</div>
</a>
</div>

<div class="col-6 col-md-3">
<a href="#" class="project-card">
<div class="card custom-card mb-4">
<div class="card-body text-center">
<div class="project-name">Total Inspection Done</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.PoInspection='Yes' AND tu.ProjectType=2";
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
<div class="project-name">Total Inspection Pending</div>
<div class="project-count">
<?php echo $rncnt47 - $rncnt4;?>
</div>
</div>
</div>
</a>
</div>

<div class="col-6 col-md-3">
<a href="#" class="project-card">
<div class="card custom-card mb-4">
<div class="card-body text-center">
<div class="project-name">DISCOM Approval Done</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.DataUploadStatus='Yes' AND tu.ProjectType=2";
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
<div class="project-name">DISCOM Approval Pending</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.DataUploadStatus='No' AND tu.ProjectType=2";
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
<div class="project-name">Data Updated On National Portal Done</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.DataUploadNational='Yes' AND tu.ProjectType=2";
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
<div class="project-name">Data Updated On National Portal Pending</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.DataUploadNational='No' AND tu.ProjectType=2";
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
<div class="project-name">Subsidy Redeemed <br>Done</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyRedeemed='Yes' AND tu.ProjectType=2";
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
<div class="project-name">Subsidy Redeemed Pending</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyRedeemed='No' AND tu.ProjectType=2";
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
<div class="project-name">Subsidy Approved<br> Done</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyAproved='Yes' AND tu.ProjectType=2";
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
<div class="project-name">Subsidy Approved<br> Pending</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyAproved='No' AND tu.ProjectType=2";
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
<div class="project-name">Subsidy Disbursed<br> Done</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyDisbursed='Yes' AND tu.ProjectType=2";
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
<div class="project-name">Subsidy Disbursed<br> Pending</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyDisbursed='No' AND tu.ProjectType=2";
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
<div class="project-name">Total Site Complete Payment Received</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.PaymentStatus='2' AND tu.ProjectType=2";
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
<div class="project-name">Total Site Parital Payment<br>Received</div>
<div class="project-count">
<?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.PaymentStatus='1' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?>
</div>
</div>
</div>
</a>
</div>



