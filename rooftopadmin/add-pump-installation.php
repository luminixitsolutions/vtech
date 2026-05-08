<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page = "Pump-Installation";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add <?php } ?> Company Account
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
    <script src="ckeditor/ckeditor.js"></script>
</head>

<body>
    <style type="text/css">
        .password-tog-info {
            display: inline-block;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            position: absolute;
            right: 50px;
            top: 30px;
            text-transform: uppercase;
            z-index: 2;
        }
    </style>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'installation-sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

                <?php
                $id = $_GET['id'];

                $ProjectId = $_GET['ProjectId'];
                $ProjectSubHeadId = $_GET['ProjectSubHeadId'];
                $sql79 = "SELECT Name FROM tbl_common_master WHERE id='$ProjectId'";
                $row79 = getRecord($sql79);
                $projname = $row79['Name'];
                $sql7 = "SELECT * FROM tbl_installations WHERE id='$id'";
                $row7 = getRecord($sql7);
                $custid = $row7['CustId'];
                $sql2 = "SELECT Amount FROM tbl_general_ledger WHERE UserId='$custid' AND Type='CINV'";
        $row2 = getRecord($sql2);
        $sql = "SELECT COALESCE(SUM(Amount), 0) AS PaidAmt FROM tbl_general_ledger WHERE UserId='$custid' AND Type!='CINV' AND CrDr='dr'";
        $row = getRecord($sql);
        
        $TotalCost = $row2['Amount'];$TotPaidAmt = $row['PaidAmt'];
        $BalAmt = $TotalCost - $TotPaidAmt;

                if ($_REQUEST['action'] == 'deletephoto') {
                    $id = $_REQUEST['id'];
                    $value = $_REQUEST['value'];
                    $fieldname = "Photo" . $value;
                    $sql = "UPDATE tbl_installations SET $fieldname='' WHERE id='$id'";
                    $conn->query($sql);
                    echo "<script>alert('Photo Deleted Successfully');window.location.href='add-pump-installation.php?id=$id';</script>";
                }
                if (isset($_POST['submit'])) {
                    $CustId = addslashes(trim($_POST['CustId']));
                    $CellNo = addslashes(trim($_POST['CellNo']));
                    $CustName = addslashes(trim($_POST['CustName']));
                    $Address = addslashes(trim($_POST['Address']));
                    $Lattitude = addslashes(trim($_POST['Lattitude']));
                    $Longitude = addslashes(trim($_POST['Longitude']));

                    $NetMeterSanction = addslashes(trim($_POST['NetMeterSanction']));
                    $NetMeterSanctionDate = addslashes(trim($_POST['NetMeterSanctionDate']));
                    $LocationPhoto = addslashes(trim($_POST['LocationPhoto']));
                    $LocationPhotoDate = addslashes(trim($_POST['LocationPhotoDate']));
                    $MaterialDelPhoto = addslashes(trim($_POST['MaterialDelPhoto']));
                    $PoInspection = addslashes(trim($_POST['PoInspection']));
                    $PoInspectionDate = addslashes(trim($_POST['PoInspectionDate']));
                    $PoApproval = addslashes(trim($_POST['PoApproval']));
                    $PoApprovalDate = addslashes(trim($_POST['PoApprovalDate']));
                    $PaymentDone = addslashes(trim($_POST['PaymentDone']));
                    $PaymentDate = addslashes(trim($_POST['PaymentDate']));
                    $DriveLink = addslashes(trim($_POST['DriveLink']));
                    $InstallStatus = addslashes(trim($_POST['InstallStatus']));
                    $InstallationDate = addslashes(trim($_POST['InstallationDate']));

                    $MaterialDelPhotoDate = addslashes(trim($_POST['MaterialDelPhotoDate']));
                    $StructureInstallation = addslashes(trim($_POST['StructureInstallation']));
                    $StructureInstallationDate = addslashes(trim($_POST['StructureInstallationDate']));
                    $PanelInstallation = addslashes(trim($_POST['PanelInstallation']));

                    $PanelInstallationDate = addslashes(trim($_POST['PanelInstallationDate']));
                    $InverterInst = addslashes(trim($_POST['InverterInst']));
                    $InverterInstDate = addslashes(trim($_POST['InverterInstDate']));
                    $CablingPhotos = addslashes(trim($_POST['CablingPhotos']));

                    $CablingPhotosDate = addslashes(trim($_POST['CablingPhotosDate']));
                    $EarthingPhoto = addslashes(trim($_POST['EarthingPhoto']));
                    $EarthingPhotoDate = addslashes(trim($_POST['EarthingPhotoDate']));
                    $CompletedInst = addslashes(trim($_POST['CompletedInst']));
                    $CompletedInstDate = addslashes(trim($_POST['CompletedInstDate']));
                    $MeterSent = addslashes(trim($_POST['MeterSent']));
                    $MeterSentDate = addslashes(trim($_POST['MeterSentDate']));
                    $MeterInstDiscom = addslashes(trim($_POST['MeterInstDiscom']));
                    $MeterInstDiscomDate = addslashes(trim($_POST['MeterInstDiscomDate']));
                    $JointInsp = addslashes(trim($_POST['JointInsp']));
                    $JointInspDate = addslashes(trim($_POST['JointInspDate']));
                    $NetMeterPhoto = addslashes(trim($_POST['NetMeterPhoto']));
                    $NetMeterPhotoDate = addslashes(trim($_POST['NetMeterPhotoDate']));
                    $JointInspPhoto = addslashes(trim($_POST['JointInspPhoto']));

                    $JointInspPhotoDate = addslashes(trim($_POST['JointInspPhotoDate']));
                    $DataUploadNational = addslashes(trim($_POST['DataUploadNational']));
                    $DataUploadNationalDate = addslashes(trim($_POST['DataUploadNationalDate']));
                    $SubsidyRedeemed = addslashes(trim($_POST['SubsidyRedeemed']));
                    $SubsidyRedeemedDate = addslashes(trim($_POST['SubsidyRedeemedDate']));
                    $SubsidyAproved = addslashes(trim($_POST['SubsidyAproved']));
                    $SubsidyAprovedDate = addslashes(trim($_POST['SubsidyAprovedDate']));
                    $SubsidyDisbursed = addslashes(trim($_POST['SubsidyDisbursed']));
                    $SubsidyDisbursedDate = addslashes(trim($_POST['SubsidyDisbursedDate']));

                    $Payment90 = addslashes(trim($_POST['Payment90']));
                    $Payment90Amt = addslashes(trim($_POST['Payment90Amt']));
                    $Payment10 = addslashes(trim($_POST['Payment10']));
                    $Payment10Amt = addslashes(trim($_POST['Payment10Amt']));

                    $InspectionDiscrepancy = addslashes(trim($_POST['InspectionDiscrepancy']));
                    $InspectionDiscrepancyDate = addslashes(trim($_POST['InspectionDiscrepancyDate']));
                    $InspectionDiscrepancyRemark = addslashes(trim($_POST['InspectionDiscrepancyRemark']));
                    $WarrantyReg = addslashes(trim($_POST['WarrantyReg']));
                    $WarrantyRegDate = addslashes(trim($_POST['WarrantyRegDate']));

                    $DataUploadStatus = addslashes(trim($_POST['DataUploadStatus']));
                    $DataUploadDate = addslashes(trim($_POST['DataUploadDate']));
                    $Documentation = addslashes(trim($_POST['Documentation']));
                    $DocumentationContractorId = addslashes(trim($_POST['DocumentationContractorId']));
                    $Foundation = addslashes(trim($_POST['Foundation']));
                    $FoundationContractorId = addslashes(trim($_POST['FoundationContractorId']));
                    $FoundationDate = addslashes(trim($_POST['FoundationDate']));
                    $DocumentationDate = addslashes(trim($_POST['DocumentationDate']));
                    if ($Foundation == 'Yes') {
                        $sql = "SELECT PumpCapacity FROM tbl_users WHERE id='$CustId'";
                        $row = getRecord($sql);
                        $PumpCapacity = $row['PumpCapacity'];

                        $sql2 = "SELECT FoundationVal FROM tbl_rooftop_contractor_commision WHERE UserId='$FoundationContractorId' AND Capacity='$PumpCapacity'";
                        $row2 = getRecord($sql2);
                        $Amount = $row2['FoundationVal'];

                        $sql = "DELETE FROM tbl_rooftop_made_contractor_commision WHERE CustId='$CustId' AND Roll=4";
                        $conn->query($sql);

                        $sql = "INSERT INTO tbl_rooftop_made_contractor_commision SET ContractorId='$FoundationContractorId',CustId='$CustId',Capacity='$PumpCapacity',ScopeOfWork='Foundation',Amount='$Amount',CreatedDate='$FoundationDate',Roll=4";
                        $conn->query($sql);
                    }

                    if ($Documentation == 'Yes') {
                        $sql = "SELECT PumpCapacity FROM tbl_users WHERE id='$CustId'";
                        $row = getRecord($sql);
                        $PumpCapacity = $row['PumpCapacity'];

                        $sql2 = "SELECT DocumentationVal FROM tbl_rooftop_contractor_commision WHERE UserId='$DocumentationContractorId' AND Capacity='$PumpCapacity'";
                        $row2 = getRecord($sql2);
                        $Amount = $row2['DocumentationVal'];

                        $sql = "DELETE FROM tbl_rooftop_made_contractor_commision WHERE CustId='$CustId' AND Roll=7";
                        $conn->query($sql);

                        $sql = "INSERT INTO tbl_rooftop_made_contractor_commision SET ContractorId='$DocumentationContractorId',CustId='$CustId',Capacity='$PumpCapacity',ScopeOfWork='Documentation',Amount='$Amount',CreatedDate='$DocumentationDate',Roll=7";
                        $conn->query($sql);
                    }

                    

                    $CreatedDate = date('Y-m-d');
                    $InstStatus = $_POST['InstStatus'];
                   if ($_GET['id'] == '') {
                        $sql = "INSERT INTO tbl_installations SET DriveLink='$DriveLink',
                        PaymentDone='$PaymentDone',PaymentDate='$PaymentDate',CustId='$CustId',
                        CellNo='$CellNo',CustName='$CustName',Address='$Address',Lattitude='$Lattitude',
                        Longitude='$Longitude',Status='1',CreatedBy='$user_id',CreatedDate='$CreatedDate',
                        Type=1,NetMeterSanction='$NetMeterSanction',NetMeterSanctionDate='$NetMeterSanctionDate',
                        LocationPhoto='$LocationPhoto',
                        LocationPhotoDate='$LocationPhotoDate',MaterialDelPhoto='$MaterialDelPhoto',
                        PoInspection='$PoInspection',
                        PoInspectionDate='$PoInspectionDate',PoApproval='$PoApproval',
                        PoApprovalDate='$PoApprovalDate',InstStatus='$InstStatus',InstallStatus='$InstallStatus',
                        InstallationDate='$InstallationDate',MaterialDelPhotoDate='$MaterialDelPhotoDate',
                        StructureInstallation='$StructureInstallation',StructureInstallationDate='$StructureInstallationDate',
                        PanelInstallation='$PanelInstallation',PanelInstallationDate='$PanelInstallationDate',
                        InverterInst='$InverterInst',InverterInstDate='$InverterInstDate',
                        CablingPhotos='$CablingPhotos',CablingPhotosDate='$CablingPhotosDate',
                        EarthingPhoto='$EarthingPhoto',EarthingPhotoDate='$EarthingPhotoDate',
                        CompletedInst='$CompletedInst',CompletedInstDate='$CompletedInstDate',
                        MeterSent='$MeterSent',MeterSentDate='$MeterSentDate',
                        MeterInstDiscom='$MeterInstDiscom',MeterInstDiscomDate='$MeterInstDiscomDate',JointInsp='$JointInsp',
                        JointInspDate='$JointInspDate',NetMeterPhoto='$NetMeterPhoto',
                        NetMeterPhotoDate='$NetMeterPhotoDate',JointInspPhoto='$JointInspPhoto',
                        JointInspPhotoDate='$JointInspPhotoDate',DataUploadNational='$DataUploadNational',
                        DataUploadNationalDate='$DataUploadNationalDate',
                        SubsidyRedeemed='$SubsidyRedeemed',SubsidyRedeemedDate='$SubsidyRedeemedDate',
                        SubsidyAproved='$SubsidyAproved',SubsidyAprovedDate='$SubsidyAprovedDate',
                        SubsidyDisbursed='$SubsidyDisbursed',SubsidyDisbursedDate='$SubsidyDisbursedDate',Payment90='$Payment90',Payment90Amt='$Payment90Amt',
                        Payment10='$Payment10',Payment10Amt='$Payment10Amt',
                        InspectionDiscrepancy='$InspectionDiscrepancy',
                        InspectionDiscrepancyDate='$InspectionDiscrepancyDate',
                        InspectionDiscrepancyRemark='$InspectionDiscrepancyRemark',WarrantyReg='$WarrantyReg',
                        WarrantyRegDate='$WarrantyRegDate',DataUploadStatus='$DataUploadStatus',
                        DataUploadDate='$DataUploadDate',Foundation='$Foundation',
                        FoundationContractorId='$FoundationContractorId',FoundationDate='$FoundationDate',
                        Documentation='$Documentation',DocumentationContractorId='$DocumentationContractorId',
                        DocumentationDate='$DocumentationDate'";
                        $conn->query($sql);
                        echo "<script>alert('Record Saved Successfully');window.location.href='installation-project-sub-head-dashboard.php?id=$ProjectId&name=$projname';</script>";
                    } else {
                        $sql = "UPDATE tbl_installations SET DriveLink='$DriveLink',
                        PaymentDone='$PaymentDone',PaymentDate='$PaymentDate',CustId='$CustId',
                        CellNo='$CellNo',CustName='$CustName',Address='$Address',Lattitude='$Lattitude',
                        Longitude='$Longitude',Status='1',ModifiedBy='$user_id',ModifiedDate='$CreatedDate',
                        Type=1,NetMeterSanction='$NetMeterSanction',NetMeterSanctionDate='$NetMeterSanctionDate',
                        LocationPhoto='$LocationPhoto',
                        LocationPhotoDate='$LocationPhotoDate',MaterialDelPhoto='$MaterialDelPhoto',
                        PoInspection='$PoInspection',
                        PoInspectionDate='$PoInspectionDate',PoApproval='$PoApproval',
                        PoApprovalDate='$PoApprovalDate',InstStatus='$InstStatus',InstallStatus='$InstallStatus',
                        InstallationDate='$InstallationDate',MaterialDelPhotoDate='$MaterialDelPhotoDate',
                        StructureInstallation='$StructureInstallation',StructureInstallationDate='$StructureInstallationDate',
                        PanelInstallation='$PanelInstallation',PanelInstallationDate='$PanelInstallationDate',
                        InverterInst='$InverterInst',InverterInstDate='$InverterInstDate',
                        CablingPhotos='$CablingPhotos',CablingPhotosDate='$CablingPhotosDate',
                        EarthingPhoto='$EarthingPhoto',EarthingPhotoDate='$EarthingPhotoDate',
                        CompletedInst='$CompletedInst',CompletedInstDate='$CompletedInstDate',
                        MeterSent='$MeterSent',MeterSentDate='$MeterSentDate',
                        MeterInstDiscom='$MeterInstDiscom',MeterInstDiscomDate='$MeterInstDiscomDate',JointInsp='$JointInsp',
                        JointInspDate='$JointInspDate',NetMeterPhoto='$NetMeterPhoto',
                        NetMeterPhotoDate='$NetMeterPhotoDate',JointInspPhoto='$JointInspPhoto',
                        JointInspPhotoDate='$JointInspPhotoDate',DataUploadNational='$DataUploadNational',
                        DataUploadNationalDate='$DataUploadNationalDate',
                        SubsidyRedeemed='$SubsidyRedeemed',SubsidyRedeemedDate='$SubsidyRedeemedDate',
                        SubsidyAproved='$SubsidyAproved',SubsidyAprovedDate='$SubsidyAprovedDate',
                        SubsidyDisbursed='$SubsidyDisbursed',SubsidyDisbursedDate='$SubsidyDisbursedDate',Payment90='$Payment90',Payment90Amt='$Payment90Amt',
                        Payment10='$Payment10',Payment10Amt='$Payment10Amt',
                        InspectionDiscrepancy='$InspectionDiscrepancy',
                        InspectionDiscrepancyDate='$InspectionDiscrepancyDate',
                        InspectionDiscrepancyRemark='$InspectionDiscrepancyRemark',WarrantyReg='$WarrantyReg',
                        WarrantyRegDate='$WarrantyRegDate',DataUploadStatus='$DataUploadStatus',
                        DataUploadDate='$DataUploadDate',Foundation='$Foundation',
                        FoundationContractorId='$FoundationContractorId',FoundationDate='$FoundationDate',
                        Documentation='$Documentation',DocumentationContractorId='$DocumentationContractorId',
                        DocumentationDate='$DocumentationDate' WHERE id='$id'";
                        $conn->query($sql);
                        echo "<script>alert('Record Updated Successfully');window.location.href='installation-project-sub-head-dashboard.php?id=$ProjectId&name=$projname';</script>";
                    }
                }
                ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add
                        <?php } ?> Rooftop Installation</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off" action="" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">

                                      
<input type="hidden" name="CustId" id="CustId" value="<?php echo $row7["CustId"]; ?>">
                                        
                                        <div class="form-group col-md-8">
                                            <label class="form-label">Customer Name </label>
                                            <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row7["CustName"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Contact No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["CellNo"]; ?>"
                                                autocomplete="off" oninput="getUserDetails()" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="form-label">Address</label>
                                            <textarea name="Address" id="Address" class="form-control"><?php echo $row7['Address']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>



                                        <div class="form-group col-md-6">
                                            <label class="form-label">Lattitude </label>
                                            <input type="text" name="Lattitude" id="Lattitude" class="form-control"
                                                placeholder="" value="<?php echo $row7["Lattitude"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label">Longitude </label>
                                            <input type="text" name="Longitude" id="Longitude" class="form-control"
                                                placeholder="" value="<?php echo $row7["Longitude"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="form-label">Google Drive Link</label>
                                            <textarea name="DriveLink" id="DriveLink" class="form-control"><?php echo $row7['DriveLink']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-3">
                                            <label class="form-label">Net Meter Sanction <span class="text-danger">*</span></label>
                                            <select class="form-control" id="NetMeterSanction" name="NetMeterSanction" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->

                                                <option value="No" <?php if ($row7["NetMeterSanction"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["NetMeterSanction"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Net Meter Sanction Date </label>
                                            <input type="date" name="NetMeterSanctionDate" id="NetMeterSanctionDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["NetMeterSanctionDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Location Photo <span class="text-danger">*</span></label>
                                            <select class="form-control" id="LocationPhoto" name="LocationPhoto" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->

                                                <option value="No" <?php if ($row7["LocationPhoto"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["LocationPhoto"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="LocationPhotoDate" id="LocationPhotoDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["LocationPhotoDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Material Delivery Photos <span class="text-danger">*</span></label>
                                            <select class="form-control" id="MaterialDelPhoto" name="MaterialDelPhoto" required="">

                                                <option value="No" <?php if ($row7["MaterialDelPhoto"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["MaterialDelPhoto"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>

                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="MaterialDelPhotoDate" id="MaterialDelPhotoDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["MaterialDelPhotoDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-4">
                                            <label class="form-label">Structure Installation <span class="text-danger">*</span></label>
                                            <select class="form-control" id="StructureInstallation" name="StructureInstallation" required="">


                                                <option value="No" <?php if ($row7["StructureInstallation"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["StructureInstallation"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="StructureInstallationDate" id="StructureInstallationDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["StructureInstallationDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <!-- <div class="form-group col-md-4">
                                            <label class="form-label">Foundation <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Foundation" name="Foundation" required="">


                                                <option value="No" <?php if ($row7["Foundation"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["Foundation"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="FoundationDate" id="FoundationDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["FoundationDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> -->

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Panel Installation <span class="text-danger">*</span></label>
                                            <select class="form-control" id="PanelInstallation" name="PanelInstallation" required="">


                                                <option value="No" <?php if ($row7["PanelInstallation"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["PanelInstallation"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="PanelInstallationDate" id="PanelInstallationDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["PanelInstallationDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Inverter Installation ACDB,DCDB <span class="text-danger">*</span></label>
                                            <select class="form-control" id="InverterInst" name="InverterInst" required="">


                                                <option value="No" <?php if ($row7["InverterInst"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["InverterInst"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="InverterInstDate" id="InverterInstDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["InverterInstDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Cabling Photos <span class="text-danger">*</span></label>
                                            <select class="form-control" id="CablingPhotos" name="CablingPhotos" required="">


                                                <option value="No" <?php if ($row7["CablingPhotos"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["CablingPhotos"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="CablingPhotosDate" id="CablingPhotosDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["CablingPhotosDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Earthing Photos <span class="text-danger">*</span></label>
                                            <select class="form-control" id="EarthingPhoto" name="EarthingPhoto" required="">


                                                <option value="No" <?php if ($row7["EarthingPhoto"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["EarthingPhoto"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="EarthingPhotoDate" id="EarthingPhotoDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["EarthingPhotoDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Completed Installation with Consumer <span class="text-danger">*</span></label>
                                            <select class="form-control" id="CompletedInst" name="CompletedInst" required="">


                                                <option value="No" <?php if ($row7["CompletedInst"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["CompletedInst"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="CompletedInstDate" id="CompletedInstDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["CompletedInstDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Installation Done <span class="text-danger">*</span></label>
                                            <select class="form-control" id="InstallStatus" name="InstallStatus" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->

                                                <option value="No" <?php if ($row7["InstallStatus"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["InstallStatus"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Installation Date </label>
                                            <input type="date" name="InstallationDate" id="InstallationDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["InstallationDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>



                                        <div class="form-group col-md-3">
                                            <label class="form-label">PO Inspection <span class="text-danger">*</span></label>
                                            <select class="form-control" id="PoInspection" name="PoInspection" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->

                                                <option value="No" <?php if ($row7["PoInspection"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["PoInspection"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">PO Inspection Date </label>
                                            <input type="date" name="PoInspectionDate" id="PoInspectionDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["PoInspectionDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Inspection Discrepancy <span class="text-danger">*</span></label>
                                            <select class="form-control" id="InspectionDiscrepancy" name="InspectionDiscrepancy" required="">


                                                <option value="No" <?php if ($row7["InspectionDiscrepancy"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["InspectionDiscrepancy"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Inspection Discrepancy Date </label>
                                            <input type="date" name="InspectionDiscrepancyDate" id="InspectionDiscrepancyDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["InspectionDiscrepancyDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="form-label">Inspection Discrepancy Remark </label>
                                            <input type="text" name="InspectionDiscrepancyRemark" id="InspectionDiscrepancyRemark" class="form-control"
                                                placeholder="" value="<?php echo $row7["InspectionDiscrepancyRemark"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Meter Sent For Testing <span class="text-danger">*</span></label>
                                            <select class="form-control" id="MeterSent" name="MeterSent" required="">

                                                <option value="No" <?php if ($row7["MeterSent"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["MeterSent"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Meter Sent For Testing Date </label>
                                            <input type="date" name="MeterSentDate" id="MeterSentDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["MeterSentDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-3">
                                            <label class="form-label">Meter Installed By Discom <span class="text-danger">*</span></label>
                                            <select class="form-control" id="MeterInstDiscom" name="MeterInstDiscom" required="">

                                                <option value="No" <?php if ($row7["MeterInstDiscom"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["MeterInstDiscom"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Meter Installed By Discom Date </label>
                                            <input type="date" name="MeterInstDiscomDate" id="MeterInstDiscomDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["MeterInstDiscomDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Joint Inspection By Officer <span class="text-danger">*</span></label>
                                            <select class="form-control" id="JointInsp" name="JointInsp" required="">

                                                <option value="No" <?php if ($row7["JointInsp"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["JointInsp"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Joint Inspection By Officer Date </label>
                                            <input type="date" name="JointInspDate" id="JointInspDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["JointInspDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Net Meter Photo <span class="text-danger">*</span></label>
                                            <select class="form-control" id="NetMeterPhoto" name="NetMeterPhoto" required="">

                                                <option value="No" <?php if ($row7["NetMeterPhoto"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["NetMeterPhoto"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Net Meter Photo Date </label>
                                            <input type="date" name="NetMeterPhotoDate" id="NetMeterPhotoDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["NetMeterPhotoDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Joint Inspection Report Photo <span class="text-danger">*</span></label>
                                            <select class="form-control" id="JointInspPhoto" name="JointInspPhoto" required="">

                                                <option value="No" <?php if ($row7["JointInspPhoto"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["JointInspPhoto"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Joint Inspection Report Photo Date </label>
                                            <input type="date" name="JointInspPhotoDate" id="JointInspPhotoDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["JointInspPhotoDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Data updated on Discom Portal <span class="text-danger">*</span></label>
                                            <select class="form-control" id="DataUploadStatus" name="DataUploadStatus" required="">


                                                <option value="No" <?php if ($row7["DataUploadStatus"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["DataUploadStatus"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Data updated on Discom Portal Date </label>
                                            <input type="date" name="DataUploadDate" id="DataUploadDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["DataUploadDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Data updated on National Portal <span class="text-danger">*</span></label>
                                            <select class="form-control" id="DataUploadNational" name="DataUploadNational" required="">

                                                <option value="No" <?php if ($row7["DataUploadNational"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["DataUploadNational"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Data updated on National Portal Date </label>
                                            <input type="date" name="DataUploadNationalDate" id="DataUploadNationalDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["DataUploadNationalDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Subsidy Redeemed <span class="text-danger">*</span></label>
                                            <select class="form-control" id="SubsidyRedeemed" name="SubsidyRedeemed" required="">

                                                <option value="No" <?php if ($row7["SubsidyRedeemed"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["SubsidyRedeemed"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Subsidy Redeemed Date </label>
                                            <input type="date" name="SubsidyRedeemedDate" id="SubsidyRedeemedDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["SubsidyRedeemedDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Subsidy Aproved <span class="text-danger">*</span></label>
                                            <select class="form-control" id="SubsidyAproved" name="SubsidyAproved" required="">

                                                <option value="No" <?php if ($row7["SubsidyAproved"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["SubsidyAproved"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Subsidy Aproved Date </label>
                                            <input type="date" name="SubsidyAprovedDate" id="SubsidyAprovedDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["SubsidyAprovedDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Subsidy Disbursed <span class="text-danger">*</span></label>
                                            <select class="form-control" id="SubsidyDisbursed" name="SubsidyDisbursed" required="">

                                                <option value="No" <?php if ($row7["SubsidyDisbursed"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["SubsidyDisbursed"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Subsidy Disbursed Date </label>
                                            <input type="date" name="SubsidyDisbursedDate" id="SubsidyDisbursedDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["SubsidyDisbursedDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>


<div class="form-group col-md-3">
                                            <label class="form-label">Warranty Registration <span class="text-danger">*</span></label>
                                            <select class="form-control" id="WarrantyReg" name="WarrantyReg" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->

                                                <option value="No" <?php if ($row7["WarrantyReg"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["WarrantyReg"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> Warranty Till Date </label>
                                            <input type="date" name="WarrantyRegDate" id="WarrantyRegDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["WarrantyRegDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        

                                        <!--<div class="form-group col-md-4">
                                            <label class="form-label">Payment Released 90% <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Payment90" name="Payment90" required="">


                                                <option value="No" <?php if ($row7["Payment90"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["Payment90"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">90% Payment Amount </label>
                                            <input type="text" name="Payment90Amt" id="Payment90Amt" class="form-control"
                                                placeholder="" value="<?php echo $row7["Payment90Amt"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Payment Date </label>
                                            <input type="date" name="PaymentDate" id="PaymentDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["PaymentDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Payment Released 10% <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Payment10" name="Payment10" required="">
                                              

                                                <option value="No" <?php if ($row7["Payment10"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["Payment10"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">10% Payment Amount </label>
                                            <input type="text" name="Payment10Amt" id="Payment10Amt" class="form-control"
                                                placeholder="" value="<?php echo $row7["Payment10Amt"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Payment Date </label>
                                            <input type="date" name="PaymentDate" id="PaymentDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["PaymentDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>-->


                                        <div class="form-group col-md-4">
                                            <label class="form-label">Total Amount </label>
                                            <input type="text" class="form-control"
                                                placeholder="" value="<?php echo $TotalCost;?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Total Paid Amount </label>
                                            <input type="text" class="form-control"
                                                placeholder="" value="<?php echo $TotPaidAmt;?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Total Balance Amount </label>
                                            <input type="text" class="form-control"
                                                placeholder="" value="<?php echo $BalAmt;?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-4">
                                            <label class="form-label">Foundation Done <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Foundation" name="Foundation" required="">
                                                <option value="No" <?php if ($row7["Foundation"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["Foundation"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <label class="form-label"> Contractor<span class="text-danger">*</span></label>
                                            <select class="form-control" name="FoundationContractorId" id="FoundationContractorId">
                                                <option selected="" value="">No One</option>
                                                <?php
                                                $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(40) ORDER BY Fname";
                                                $row12 = getList($sql12);
                                                foreach ($row12 as $result) {
                                                ?>
                                                    <option value="<?php echo $result['id']; ?>" <?php if ($row7["FoundationContractorId"] == $result['id']) { ?> selected <?php } ?>>
                                                        <?php echo $result['Fname']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Foundation Date </label>
                                            <input type="date" name="FoundationDate" id="FoundationDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["FoundationDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-4">
                                            <label class="form-label">Documentation Done <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Documentation" name="Documentation" required="">
                                                <option value="No" <?php if ($row7["Documentation"] == 'No') { ?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if ($row7["Documentation"] == 'Yes') { ?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <label class="form-label"> Contractor<span class="text-danger">*</span></label>
                                            <select class="form-control" name="DocumentationContractorId" id="DocumentationContractorId">
                                                <option selected="" value="">No One</option>
                                                <?php
                                                $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(40) ORDER BY Fname";
                                                $row12 = getList($sql12);
                                                foreach ($row12 as $result) {
                                                ?>
                                                    <option value="<?php echo $result['id']; ?>" <?php if ($row7["DocumentationContractorId"] == $result['id']) { ?> selected <?php } ?>>
                                                        <?php echo $result['Fname']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Documentation Date </label>
                                            <input type="date" name="DocumentationDate" id="DocumentationDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["DocumentationDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>



                                        


                                    </div>
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                </form>
                            </div>
                        </div>






                    </div>


                    <?php include_once 'footer.php'; ?>
                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>

    <script type="text/javascript">
        function myFunction2() {

            var x = document.getElementById("Password");
            if (x.type === "password") {
                x.type = "text";
                $('.show2').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
            } else {
                x.type = "password";
                $('.show2').html('<i class="fa fa-eye" aria-hidden="true"></i>');
            }
        }

        function error_toast() {
            var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
            $.growl.error({
                title: 'Error',
                message: 'Email Id / Phone No Already Exists',
                location: isRtl ? 'tl' : 'tr'
            });
        }

        function success_toast() {
            var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
            $.growl.success({
                title: 'Success',
                message: 'Saved Successfully...',
                location: isRtl ? 'tl' : 'tr'
            });
        }
        $(document).ready(function() {
            $(document).on("change", "#CustId", function(event) {
                var val = this.value;
                var action = "getUserDetails";
                $.ajax({
                    url: "ajax_files/ajax_vendor.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    dataType: "json",
                    success: function(data) {

                        $('#Address').val(data.Address);
                        $('#Taluka').val(data.Taluka);
                        $('#Village').val(data.Village);
                        $('#District').val(data.District);
                        $('#CustName').val(data.Fname);
                        $('#CellNo').val(data.Phone);
                        $('#Gname').val(data.Gname);
                        $('#Gphone').val(data.Gphone);
                        $('#Gname2').val(data.Gname2);
                        $('#Gphone2').val(data.Gphone2);
                        $('#AgentName').val(data.AgentName);
                        $('#ComissioningDate').val(data.CommissioningDate);
                        $('#AcDc').val(data.AcDc);
                        $('#Source').val(data.Source);

                    }
                });

            });
        });
    </script>

</body>

</html>