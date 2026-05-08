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
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Company Account
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

if($_REQUEST['action'] == 'deletephoto'){
    $id = $_REQUEST['id'];
    $value = $_REQUEST['value'];
    $fieldname = "Photo".$value;
    $sql = "UPDATE tbl_installations SET $fieldname='' WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Photo Deleted Successfully');window.location.href='add-pump-installation.php?id=$id';</script>";
}
if(isset($_POST['submit'])){
    $CustId = addslashes(trim($_POST['CustId']));
    $CellNo = addslashes(trim($_POST['CellNo']));
    $CustName = addslashes(trim($_POST['CustName']));
    $Address = addslashes(trim($_POST['Address']));
    $Lattitude = addslashes(trim($_POST['Lattitude']));
    $Longitude = addslashes(trim($_POST['Longitude']));

    $WaterOutflow = addslashes(trim($_POST['WaterOutflow']));
    $FarmerNoc = addslashes(trim($_POST['FarmerNoc']));
    $FarmerNocDate = addslashes(trim($_POST['FarmerNocDate']));
    $FarmerVideo = addslashes(trim($_POST['FarmerVideo']));
    $FarmerVideoDate = addslashes(trim($_POST['FarmerVideoDate']));
    $PoInspection = addslashes(trim($_POST['PoInspection']));
    $PoInspectionDate = addslashes(trim($_POST['PoInspectionDate']));
    $PoApproval = addslashes(trim($_POST['PoApproval']));
    $PoApprovalDate = addslashes(trim($_POST['PoApprovalDate']));
    $PaymentDone = addslashes(trim($_POST['PaymentDone']));
    $PaymentDate = addslashes(trim($_POST['PaymentDate']));
    $DriveLink = addslashes(trim($_POST['DriveLink']));
    $InstallStatus = addslashes(trim($_POST['InstallStatus']));
    $InstallationDate = addslashes(trim($_POST['InstallationDate']));

    $DgmApproval = addslashes(trim($_POST['DgmApproval']));
    $DgmApprovalDate = addslashes(trim($_POST['DgmApprovalDate']));
    $InsuranceApproval = addslashes(trim($_POST['InsuranceApproval']));
    $InsuranceApprovalDate = addslashes(trim($_POST['InsuranceApprovalDate']));

    $CircleOfficeStatus = addslashes(trim($_POST['CircleOfficeStatus']));
    $CircleOfficeDate = addslashes(trim($_POST['CircleOfficeDate']));
    $RmsIntegrationStatus = addslashes(trim($_POST['RmsIntegrationStatus']));
    $RmsIntegrationDate = addslashes(trim($_POST['RmsIntegrationDate']));

    $IcrSignDoOffice = addslashes(trim($_POST['IcrSignDoOffice']));
    $IcrSignDoOfficeDate = addslashes(trim($_POST['IcrSignDoOfficeDate']));
    $BillForward = addslashes(trim($_POST['BillForward']));
    $BillForwardDate = addslashes(trim($_POST['BillForwardDate']));
    $RoToRoAccts = addslashes(trim($_POST['RoToRoAccts']));
    $RoToRoAcctsDate = addslashes(trim($_POST['RoToRoAcctsDate']));
    $RoAcctsToZo = addslashes(trim($_POST['RoAcctsToZo']));
    $RoAcctsToZoDate = addslashes(trim($_POST['RoAcctsToZoDate']));
    $ZoToHo = addslashes(trim($_POST['ZoToHo']));
    $ZoToHoDate = addslashes(trim($_POST['ZoToHoDate']));
    $RmsIntegratHoToHoAcctsionStatus = addslashes(trim($_POST['HoToHoAccts']));
    $HoToHoAcctsDate = addslashes(trim($_POST['HoToHoAcctsDate']));
    $ForwardToPayment = addslashes(trim($_POST['ForwardToPayment']));
    $ForwardToPaymentDate = addslashes(trim($_POST['ForwardToPaymentDate']));
    
    $SentToHo = addslashes(trim($_POST['SentToHo']));
    $SentToHoDate = addslashes(trim($_POST['SentToHoDate']));
    $FileInHand = addslashes(trim($_POST['FileInHand']));
    $FileInHandDate = addslashes(trim($_POST['FileInHandDate']));
    
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
    if($Foundation == 'Yes'){
        $sql = "SELECT PumpCapacity FROM tbl_users WHERE id='$CustId'";
        $row = getRecord($sql);
        $PumpCapacity = $row['PumpCapacity'];

        $sql2 = "SELECT FoundationVal FROM tbl_contractor_commision WHERE UserId='$FoundationContractorId' AND Capacity='$PumpCapacity'";
        $row2 = getRecord($sql2);
        $Amount = $row2['FoundationVal'];

        $sql = "DELETE FROM tbl_made_contractor_commision WHERE CustId='$CustId' AND Roll=4";
        $conn->query($sql);

        $sql = "INSERT INTO tbl_made_contractor_commision SET ContractorId='$FoundationContractorId',CustId='$CustId',Capacity='$PumpCapacity',ScopeOfWork='Foundation',Amount='$Amount',CreatedDate='$FoundationDate',Roll=4";
        $conn->query($sql);
    }

    if($Documentation == 'Yes'){
        $sql = "SELECT PumpCapacity FROM tbl_users WHERE id='$CustId'";
        $row = getRecord($sql);
        $PumpCapacity = $row['PumpCapacity'];

        $sql2 = "SELECT DocumentationVal FROM tbl_contractor_commision WHERE UserId='$DocumentationContractorId' AND Capacity='$PumpCapacity'";
        $row2 = getRecord($sql2);
        $Amount = $row2['DocumentationVal'];

        $sql = "DELETE FROM tbl_made_contractor_commision WHERE CustId='$CustId' AND Roll=7";
        $conn->query($sql);

        $sql = "INSERT INTO tbl_made_contractor_commision SET ContractorId='$DocumentationContractorId',CustId='$CustId',Capacity='$PumpCapacity',ScopeOfWork='Documentation',Amount='$Amount',CreatedDate='$DocumentationDate',Roll=7";
        $conn->query($sql);
    }

    $randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
    $Photo = $_POST['OldPhoto'];
}

$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
$dest2 = '../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
} 
else{
  $Photo2 = $_POST['OldPhoto2'];
}


$randno3 = rand(1,100);
$src3 = $_FILES['Photo3']['tmp_name'];
$fnm3 = substr($_FILES["Photo3"]["name"], 0,strrpos($_FILES["Photo3"]["name"],'.')); 
$fnm3 = str_replace(" ","_",$fnm3);
$ext3 = substr($_FILES["Photo3"]["name"],strpos($_FILES["Photo3"]["name"],"."));
$dest3 = '../uploads/'. $randno3 . "_".$fnm3 . $ext3;
$imagepath3 =  $randno3 . "_".$fnm3 . $ext3;
if(move_uploaded_file($src3, $dest3))
{
$Photo3 = $imagepath3 ;
} 
else{
  $Photo3 = $_POST['OldPhoto3'];
}

$randno4 = rand(1,100);
$src4 = $_FILES['Photo4']['tmp_name'];
$fnm4 = substr($_FILES["Photo4"]["name"], 0,strrpos($_FILES["Photo4"]["name"],'.')); 
$fnm4 = str_replace(" ","_",$fnm4);
$ext4 = substr($_FILES["Photo4"]["name"],strpos($_FILES["Photo4"]["name"],"."));
$dest4 = '../uploads/'. $randno4 . "_".$fnm4 . $ext4;
$imagepath4 =  $randno4 . "_".$fnm4 . $ext4;
if(move_uploaded_file($src4, $dest4))
{
$Photo4 = $imagepath4 ;
} 
else{
    $Photo4 = $_POST['OldPhoto4'];
}

$randno5 = rand(1,100);
$src5 = $_FILES['Photo5']['tmp_name'];
$fnm5 = substr($_FILES["Photo5"]["name"], 0,strrpos($_FILES["Photo5"]["name"],'.')); 
$fnm5 = str_replace(" ","_",$fnm5);
$ext5 = substr($_FILES["Photo5"]["name"],strpos($_FILES["Photo5"]["name"],"."));
$dest5 = '../uploads/'. $randno5 . "_".$fnm5 . $ext5;
$imagepath5 =  $randno5 . "_".$fnm5 . $ext5;
if(move_uploaded_file($src5, $dest5))
{
$Photo5 = $imagepath5 ;
} 
else{
    $Photo5 = $_POST['OldPhoto5'];
}

$randno6 = rand(1,100);
$src6 = $_FILES['Photo6']['tmp_name'];
$fnm6 = substr($_FILES["Photo6"]["name"], 0,strrpos($_FILES["Photo6"]["name"],'.')); 
$fnm6 = str_replace(" ","_",$fnm6);
$ext6 = substr($_FILES["Photo6"]["name"],strpos($_FILES["Photo6"]["name"],"."));
$dest6 = '../uploads/'. $randno6 . "_".$fnm6 . $ext6;
$imagepath6 =  $randno6 . "_".$fnm6 . $ext6;
if(move_uploaded_file($src6, $dest6))
{
$Photo6 = $imagepath6 ;
} 
else{
    $Photo6 = $_POST['OldPhoto6'];
}

$randno7 = rand(1,100);
$src7 = $_FILES['Photo7']['tmp_name'];
$fnm7 = substr($_FILES["Photo7"]["name"], 0,strrpos($_FILES["Photo7"]["name"],'.')); 
$fnm7 = str_replace(" ","_",$fnm7);
$ext7 = substr($_FILES["Photo7"]["name"],strpos($_FILES["Photo7"]["name"],"."));
$dest7 = '../uploads/'. $randno7 . "_".$fnm7 . $ext7;
$imagepath7 =  $randno7 . "_".$fnm7 . $ext7;
if(move_uploaded_file($src7, $dest7))
{
$Photo7 = $imagepath7 ;
} 
else{
    $Photo7 = $_POST['OldPhoto7'];
}

$randno8 = rand(1,100);
$src8 = $_FILES['Photo8']['tmp_name'];
$fnm8 = substr($_FILES["Photo8"]["name"], 0,strrpos($_FILES["Photo8"]["name"],'.')); 
$fnm8 = str_replace(" ","_",$fnm8);
$ext8 = substr($_FILES["Photo8"]["name"],strpos($_FILES["Photo8"]["name"],"."));
$dest8 = '../uploads/'. $randno8 . "_".$fnm8 . $ext8;
$imagepath8 =  $randno8 . "_".$fnm8. $ext8;
if(move_uploaded_file($src8, $dest8))
{
$Photo8 = $imagepath8 ;
} 
else{
    $Photo8 = $_POST['OldPhoto8'];
}

$randno9 = rand(1,100);
$src9 = $_FILES['Photo9']['tmp_name'];
$fnm9 = substr($_FILES["Photo9"]["name"], 0,strrpos($_FILES["Photo9"]["name"],'.')); 
$fnm9 = str_replace(" ","_",$fnm9);
$ext9 = substr($_FILES["Photo9"]["name"],strpos($_FILES["Photo9"]["name"],"."));
$dest9 = '../uploads/'. $randno9 . "_".$fnm9 . $ext9;
$imagepath9 =  $randno9 . "_".$fnm9 . $ext9;
if(move_uploaded_file($src9, $dest9))
{
$Photo9 = $imagepath9 ;
} 
else{
    $Photo9 = $_POST['OldPhoto9'];
}

$randno10 = rand(1,100);
$src10 = $_FILES['Photo10']['tmp_name'];
$fnm10 = substr($_FILES["Photo10"]["name"], 0,strrpos($_FILES["Photo10"]["name"],'.')); 
$fnm10 = str_replace(" ","_",$fnm10);
$ext10 = substr($_FILES["Photo10"]["name"],strpos($_FILES["Photo10"]["name"],"."));
$dest10 = '../uploads/'. $randno10 . "_".$fnm10 . $ext10;
$imagepath10 =  $randno10 . "_".$fnm10 . $ext10;
if(move_uploaded_file($src10, $dest10))
{
$Photo10 = $imagepath10 ;
} 
else{
    $Photo10 = $_POST['OldPhoto10'];
}

$randno11 = rand(1,100);
$src11 = $_FILES['Photo11']['tmp_name'];
$fnm11 = substr($_FILES["Photo11"]["name"], 0,strrpos($_FILES["Photo11"]["name"],'.')); 
$fnm11 = str_replace(" ","_",$fnm11);
$ext11 = substr($_FILES["Photo11"]["name"],strpos($_FILES["Photo11"]["name"],"."));
$dest11 = '../uploads/'. $randno11 . "_".$fnm11 . $ext11;
$imagepath11 =  $randno11 . "_".$fnm11 . $ext11;
if(move_uploaded_file($src11, $dest11))
{
$Photo11 = $imagepath11 ;
} 
else{
    $Photo11 = $_POST['OldPhoto11'];
}

$randno12 = rand(1,100);
$src12 = $_FILES['Photo12']['tmp_name'];
$fnm12 = substr($_FILES["Photo12"]["name"], 0,strrpos($_FILES["Photo12"]["name"],'.')); 
$fnm12 = str_replace(" ","_",$fnm12);
$ext12 = substr($_FILES["Photo12"]["name"],strpos($_FILES["Photo12"]["name"],"."));
$dest12 = '../uploads/'. $randno12 . "_".$fnm12 . $ext12;
$imagepath12 =  $randno12 . "_".$fnm12 . $ext12;
if(move_uploaded_file($src12, $dest12))
{
$Photo12 = $imagepath12 ;
} 
else{
    $Photo12 = $_POST['OldPhoto12'];
}

$randno13 = rand(1,100);
$src13 = $_FILES['Photo13']['tmp_name'];
$fnm13 = substr($_FILES["Photo13"]["name"], 0,strrpos($_FILES["Photo13"]["name"],'.')); 
$fnm13 = str_replace(" ","_",$fnm13);
$ext13 = substr($_FILES["Photo13"]["name"],strpos($_FILES["Photo13"]["name"],"."));
$dest13 = '../uploads/'. $randno13 . "_".$fnm13 . $ext13;
$imagepath13 =  $randno13 . "_".$fnm13 . $ext13;
if(move_uploaded_file($src13, $dest13))
{
$Photo13 = $imagepath13 ;
} 
else{
    $Photo13 = $_POST['OldPhoto13'];
}

$randno14 = rand(1,100);
$src14 = $_FILES['Photo14']['tmp_name'];
$fnm14 = substr($_FILES["Photo14"]["name"], 0,strrpos($_FILES["Photo14"]["name"],'.')); 
$fnm14 = str_replace(" ","_",$fnm14);
$ext14 = substr($_FILES["Photo14"]["name"],strpos($_FILES["Photo14"]["name"],"."));
$dest14 = '../uploads/'. $randno14 . "_".$fnm14 . $ext14;
$imagepath14 =  $randno14 . "_".$fnm14 . $ext14;
if(move_uploaded_file($src14, $dest14))
{
$Photo14 = $imagepath14 ;
} 
else{
    $Photo14 = $_POST['OldPhoto14'];
}

$randno15 = rand(1,100);
$src15 = $_FILES['Photo15']['tmp_name'];
$fnm15 = substr($_FILES["Photo15"]["name"], 0,strrpos($_FILES["Photo15"]["name"],'.')); 
$fnm15 = str_replace(" ","_",$fnm15);
$ext15 = substr($_FILES["Photo15"]["name"],strpos($_FILES["Photo15"]["name"],"."));
$dest15 = '../uploads/'. $randno15 . "_".$fnm15 . $ext15;
$imagepath15 =  $randno15 . "_".$fnm15 . $ext15;
if(move_uploaded_file($src15, $dest15))
{
$Photo15 = $imagepath15 ;
} 
else{
    $Photo15 = $_POST['OldPhoto15'];
}

$randno16 = rand(1,100);
$src16 = $_FILES['Photo16']['tmp_name'];
$fnm16 = substr($_FILES["Photo16"]["name"], 0,strrpos($_FILES["Photo16"]["name"],'.')); 
$fnm16 = str_replace(" ","_",$fnm16);
$ext16 = substr($_FILES["Photo16"]["name"],strpos($_FILES["Photo16"]["name"],"."));
$dest16 = '../uploads/'. $randno16 . "_".$fnm16 . $ext16;
$imagepath16 =  $randno16 . "_".$fnm16 . $ext16;
if(move_uploaded_file($src16, $dest16))
{
$Photo16 = $imagepath16 ;
} 
else{
    $Photo16 = $_POST['OldPhoto16'];
}

$randno17 = rand(1,100);
$src17 = $_FILES['Photo17']['tmp_name'];
$fnm17 = substr($_FILES["Photo17"]["name"], 0,strrpos($_FILES["Photo17"]["name"],'.')); 
$fnm17 = str_replace(" ","_",$fnm17);
$ext17 = substr($_FILES["Photo17"]["name"],strpos($_FILES["Photo17"]["name"],"."));
$dest17 = '../uploads/'. $randno17 . "_".$fnm17 . $ext17;
$imagepath17 =  $randno17 . "_".$fnm17 . $ext17;
if(move_uploaded_file($src17, $dest17))
{
$Photo17 = $imagepath17 ;
} 
else{
    $Photo17 = $_POST['OldPhoto17'];
}

$randno18 = rand(1,100);
$src18 = $_FILES['Photo18']['tmp_name'];
$fnm18 = substr($_FILES["Photo18"]["name"], 0,strrpos($_FILES["Photo18"]["name"],'.')); 
$fnm18 = str_replace(" ","_",$fnm18);
$ext18 = substr($_FILES["Photo18"]["name"],strpos($_FILES["Photo18"]["name"],"."));
$dest18 = '../uploads/'. $randno17 . "_".$fnm18 . $ext18;
$imagepath18 =  $randno18 . "_".$fnm18 . $ext18;
if(move_uploaded_file($src18, $dest18))
{
$Photo18 = $imagepath18 ;
} 
else{
    $Photo18 = $_POST['OldPhoto18'];
}

$CreatedDate = date('Y-m-d');
$InstStatus = $_POST['InstStatus'];
if($_GET['id']==''){
    $sql = "INSERT INTO tbl_installations SET DriveLink='$DriveLink',PaymentDone='$PaymentDone',PaymentDate='$PaymentDate',CustId='$CustId',CellNo='$CellNo',CustName='$CustName',Address='$Address',Lattitude='$Lattitude',Longitude='$Longitude',Photo1='$Photo',Photo2='$Photo2',Photo3='$Photo3',Photo4='$Photo4',Photo5='$Photo5',Photo6='$Photo6',Photo7='$Photo7',Photo8='$Photo8',Photo9='$Photo9',Photo10='$Photo10',Status='1',CreatedBy='$user_id',CreatedDate='$CreatedDate',Type=2,Photo11='$Photo11',Photo12='$Photo12',WaterOutflow='$WaterOutflow',FarmerNoc='$FarmerNoc',FarmerNocDate='$FarmerNocDate',FarmerVideo='$FarmerVideo',FarmerVideoDate='$FarmerVideoDate',PoInspection='$PoInspection',PoInspectionDate='$PoInspectionDate',PoApproval='$PoApproval',PoApprovalDate='$PoApprovalDate',Photo13='$Photo13',Photo14='$Photo14',Photo15='$Photo15',Photo16='$Photo16',Photo17='$Photo17',Photo18='$Photo18',InstStatus='$InstStatus',InstallStatus='$InstallStatus',InstallationDate='$InstallationDate',DgmApproval='$DgmApproval',DgmApprovalDate='$DgmApprovalDate',InsuranceApproval='$InsuranceApproval',InsuranceApprovalDate='$InsuranceApprovalDate',CircleOfficeStatus='$CircleOfficeStatus',CircleOfficeDate='$CircleOfficeDate',RmsIntegrationStatus='$RmsIntegrationStatus',RmsIntegrationDate='$RmsIntegrationDate',IcrSignDoOffice='$IcrSignDoOffice',IcrSignDoOfficeDate='$IcrSignDoOfficeDate',BillForward='$BillForward',BillForwardDate='$BillForwardDate',RoToRoAccts='$RoToRoAccts',RoToRoAcctsDate='$RoToRoAcctsDate',RoAcctsToZo='$RoAcctsToZo',RoAcctsToZoDate='$RoAcctsToZoDate',ZoToHo='$ZoToHo',ZoToHoDate='$ZoToHoDate',HoToHoAccts='$HoToHoAccts',HoToHoAcctsDate='$HoToHoAcctsDate',ForwardToPayment='$ForwardToPayment',ForwardToPaymentDate='$ForwardToPaymentDate',SentToHo='$SentToHo',SentToHoDate='$SentToHoDate',FileInHand='$FileInHand',FileInHandDate='$FileInHandDate',Payment90='$Payment90',Payment90Amt='$Payment90Amt',Payment10='$Payment10',Payment10Amt='$Payment10Amt',InspectionDiscrepancy='$InspectionDiscrepancy',InspectionDiscrepancyDate='$InspectionDiscrepancyDate',InspectionDiscrepancyRemark='$InspectionDiscrepancyRemark',WarrantyReg='$WarrantyReg',WarrantyRegDate='$WarrantyRegDate',DataUploadStatus='$DataUploadStatus',DataUploadDate='$DataUploadDate',Foundation='$Foundation',FoundationContractorId='$FoundationContractorId',FoundationDate='$FoundationDate',Documentation='$Documentation',DocumentationContractorId='$DocumentationContractorId',DocumentationDate='$DocumentationDate'";
    $conn->query($sql);
    echo "<script>alert('Record Saved Successfully');window.location.href='installation-project-sub-head-dashboard.php?id=$ProjectId&name=$projname';</script>";
}
else{
    $sql = "UPDATE tbl_installations SET DriveLink='$DriveLink',PaymentDone='$PaymentDone',PaymentDate='$PaymentDate',CustId='$CustId',CellNo='$CellNo',CustName='$CustName',Address='$Address',Lattitude='$Lattitude',Longitude='$Longitude',Photo1='$Photo',Photo2='$Photo2',Photo3='$Photo3',Photo4='$Photo4',Photo5='$Photo5',Photo6='$Photo6',Photo7='$Photo7',Photo8='$Photo8',Photo9='$Photo9',Photo10='$Photo10',Status='1',ModifiedBy='$user_id',ModifiedDate='$CreatedDate',Type=2,Photo11='$Photo11',Photo12='$Photo12',WaterOutflow='$WaterOutflow',FarmerNoc='$FarmerNoc',FarmerNocDate='$FarmerNocDate',FarmerVideo='$FarmerVideo',FarmerVideoDate='$FarmerVideoDate',PoInspection='$PoInspection',PoInspectionDate='$PoInspectionDate',PoApproval='$PoApproval',PoApprovalDate='$PoApprovalDate',Photo13='$Photo13',Photo14='$Photo14',Photo15='$Photo15',Photo16='$Photo16',Photo17='$Photo17',Photo18='$Photo18',InstStatus='$InstStatus',InstallStatus='$InstallStatus',InstallationDate='$InstallationDate',DgmApproval='$DgmApproval',DgmApprovalDate='$DgmApprovalDate',InsuranceApproval='$InsuranceApproval',InsuranceApprovalDate='$InsuranceApprovalDate',CircleOfficeStatus='$CircleOfficeStatus',CircleOfficeDate='$CircleOfficeDate',RmsIntegrationStatus='$RmsIntegrationStatus',RmsIntegrationDate='$RmsIntegrationDate',IcrSignDoOffice='$IcrSignDoOffice',IcrSignDoOfficeDate='$IcrSignDoOfficeDate',BillForward='$BillForward',BillForwardDate='$BillForwardDate',RoToRoAccts='$RoToRoAccts',RoToRoAcctsDate='$RoToRoAcctsDate',RoAcctsToZo='$RoAcctsToZo',RoAcctsToZoDate='$RoAcctsToZoDate',ZoToHo='$ZoToHo',ZoToHoDate='$ZoToHoDate',HoToHoAccts='$HoToHoAccts',HoToHoAcctsDate='$HoToHoAcctsDate',ForwardToPayment='$ForwardToPayment',ForwardToPaymentDate='$ForwardToPaymentDate',SentToHo='$SentToHo',SentToHoDate='$SentToHoDate',FileInHand='$FileInHand',FileInHandDate='$FileInHandDate',Payment90='$Payment90',Payment90Amt='$Payment90Amt',Payment10='$Payment10',Payment10Amt='$Payment10Amt',InspectionDiscrepancy='$InspectionDiscrepancy',InspectionDiscrepancyDate='$InspectionDiscrepancyDate',InspectionDiscrepancyRemark='$InspectionDiscrepancyRemark',WarrantyReg='$WarrantyReg',WarrantyRegDate='$WarrantyRegDate',DataUploadStatus='$DataUploadStatus',DataUploadDate='$DataUploadDate',Foundation='$Foundation',FoundationContractorId='$FoundationContractorId',FoundationDate='$FoundationDate',Documentation='$Documentation',DocumentationContractorId='$DocumentationContractorId',DocumentationDate='$DocumentationDate' WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Record Updated Successfully');window.location.href='installation-project-sub-head-dashboard.php?id=$ProjectId&name=$projname';</script>";
}
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Pump Installation</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off" action="" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                       
                                     <!--    <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Customer</option>
 <?php 
  if($Roll==1 || $Roll==7){
                     $sql12 = "SELECT * FROM tbl_users tu WHERE tu.Roll=5 AND tu.InstallerStatus=1 AND tu.ProjectType=1";
            }
        else{
        $sql12 = "SELECT * FROM tbl_users tu WHERE tu.Roll=5 AND tu.InstallerStatus=1 AND tu.ProjectType=1"; 
        }
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>  -->
<input type="hidden" name="CustId" id="CustId" value="<?php echo $row7["CustId"];?>">
<!-- <div class="form-group col-md-2" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" onclick="addVendor()">+</button>
</div> -->

<div class="form-group col-md-12">
                                            <label class="form-label">Contact No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["CellNo"]; ?>"
                                                autocomplete="off" oninput="getUserDetails()">
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-12">
   <label class="form-label">Customer Name </label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row7["CustName"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-12">
   <label class="form-label">Address</label>
     <textarea name="Address" id="Address" class="form-control"  
                                                ><?php echo $row7['Address']; ?></textarea>
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
     <textarea name="DriveLink" id="DriveLink" class="form-control"  
                                                ><?php echo $row7['DriveLink']; ?></textarea>
    <div class="clearfix"></div>
 </div>  
                                        <div class="form-group col-md-6">
                                            <label class="form-label">System To Be Install Location <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto"
                                                    value="<?php echo $row7['Photo1'];?>" id="OldPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo1']=='') {} else{?>
                                            <span id="show_photo">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=1"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo"></a><img
                                                        src="../uploads/<?php echo $row7['Photo1'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>


                                         <div class="form-group col-md-6">
                                            <label class="form-label">Material Received <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo2"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto2"
                                                    value="<?php echo $row7['Photo2'];?>" id="OldPhoto2">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo2']=='') {} else{?>
                                            <span id="show_photo2">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=2"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo2"></a><img
                                                        src="../uploads/<?php echo $row7['Photo2'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                        <!--<div class="form-group col-md-6">
                                            <label class="form-label">Pole Pit Exacavation 1 <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo3"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto3"
                                                    value="<?php echo $row7['Photo3'];?>" id="OldPhoto3">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo3']=='') {} else{?>
                                            <span id="show_photo3">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=3"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo3"></a><img
                                                        src="../uploads/<?php echo $row7['Photo3'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label class="form-label">Pole Pit Exacavation 2 <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo4"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto4"
                                                    value="<?php echo $row7['Photo4'];?>" id="OldPhoto4">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo4']=='') {} else{?>
                                            <span id="show_photo4">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=4"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo4"></a><img
                                                        src="../uploads/<?php echo $row7['Photo4'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="form-label">Foundation Photos With Customer <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo5"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto5"
                                                    value="<?php echo $row7['Photo5'];?>" id="OldPhoto5">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo5']=='') {} else{?>
                                            <span id="show_photo5">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=5"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo5"></a><img
                                                        src="../uploads/<?php echo $row7['Photo5'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label class="form-label">Structure Photos <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo6"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto6"
                                                    value="<?php echo $row7['Photo6'];?>" id="OldPhoto6">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo6']=='') {} else{?>
                                            <span id="show_photo6">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=6"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo6"></a><img
                                                        src="../uploads/<?php echo $row7['Photo6'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="form-label">Panel Photos 1 <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo7"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto7"
                                                    value="<?php echo $row7['Photo7'];?>" id="OldPhoto7">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo7']=='') {} else{?>
                                            <span id="show_photo7">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=7"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo7"></a><img
                                                        src="../uploads/<?php echo $row7['Photo7'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="form-label">Panel Photos 2 <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo8"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto8"
                                                    value="<?php echo $row7['Photo8'];?>" id="OldPhoto8">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo8']=='') {} else{?>
                                            <span id="show_photo8">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=8"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo8"></a><img
                                                        src="../uploads/<?php echo $row7['Photo8'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                         <div class="form-group col-md-6">
                                            <label class="form-label">Pump Photo <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo9"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto9"
                                                    value="<?php echo $row7['Photo9'];?>" id="OldPhoto9">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo9']=='') {} else{?>
                                            <span id="show_photo9">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=9"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo9"></a><img
                                                        src="../uploads/<?php echo $row7['Photo9'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label class="form-label">Motor Photo <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo10"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto10"
                                                    value="<?php echo $row7['Photo10'];?>" id="OldPhoto10">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo10']=='') {} else{?>
                                            <span id="show_photo10">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=10"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo10"></a><img
                                                        src="../uploads/<?php echo $row7['Photo10'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

<div class="form-group col-md-6">
                                            <label class="form-label">Controller Photo <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo11"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto11"
                                                    value="<?php echo $row7['Photo11'];?>" id="OldPhoto11">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo11']=='') {} else{?>
                                            <span id="show_photo11">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=11"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo11"></a><img
                                                        src="../uploads/<?php echo $row7['Photo11'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                         <div class="form-group col-md-6">
                                            <label class="form-label">Earthing Photo <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo12"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto12"
                                                    value="<?php echo $row7['Photo12'];?>" id="OldPhoto12">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo12']=='') {} else{?>
                                            <span id="show_photo12">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=12"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo12"></a><img
                                                        src="../uploads/<?php echo $row7['Photo12'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="form-label">Water Delivery Photos with Customer 1 <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo13"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto13"
                                                    value="<?php echo $row7['Photo13'];?>" id="OldPhoto13">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo13']=='') {} else{?>
                                            <span id="show_photo13">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=13"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo13"></a><img
                                                        src="../uploads/<?php echo $row7['Photo13'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="form-label">Water Delivery Photos with Customer 2 <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo14"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto14"
                                                    value="<?php echo $row7['Photo14'];?>" id="OldPhoto14">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo14']=='') {} else{?>
                                            <span id="show_photo14">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=14"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo14"></a><img
                                                        src="../uploads/<?php echo $row7['Photo14'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>
 -->
                                     <div class="form-group col-md-4">
                                            <label class="form-label">Water outflow Video <span class="text-danger">*</span></label>
                                            <select class="form-control" id="WaterOutflow" name="WaterOutflow" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["WaterOutflow"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                    <option value="Yes" <?php if($row7["WaterOutflow"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Farmer NOC For Civil Material <span class="text-danger">*</span></label>
                                            <select class="form-control" id="FarmerNoc" name="FarmerNoc" required="">
                                               <!--  <option selected="" disabled="" value="">Select</option> -->
                                               <option value="No" <?php if($row7["FarmerNoc"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["FarmerNoc"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                                
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="FarmerNocDate" id="FarmerNocDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["FarmerNocDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-4">
                                            <label class="form-label">Farmer Video For Civil Material Payment Receipt <span class="text-danger">*</span></label>
                                            <select class="form-control" id="FarmerVideo" name="FarmerVideo" required="">
                                               <!--  <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["FarmerVideo"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                    <option value="Yes" <?php if($row7["FarmerVideo"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="FarmerVideoDate" id="FarmerVideoDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["FarmerVideoDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

<div class="form-group col-md-3">
                                            <label class="form-label">Installation Done  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="InstallStatus" name="InstallStatus" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["InstallStatus"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["InstallStatus"]=='Yes') {?> selected
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
                                            <label class="form-label">Data Upload  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="DataUploadStatus" name="DataUploadStatus" required="">
                                               <!--  <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["DataUploadStatus"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["DataUploadStatus"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Data Upload Date </label>
                                            <input type="date" name="DataUploadDate" id="DataUploadDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["DataUploadDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label class="form-label">PO Inspection  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="PoInspection" name="PoInspection" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["PoInspection"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["PoInspection"]=='Yes') {?> selected
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
                                            <label class="form-label">Inspection Discrepancy  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="InspectionDiscrepancy" name="InspectionDiscrepancy" required="">
                                               <!--  <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["InspectionDiscrepancy"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["InspectionDiscrepancy"]=='Yes') {?> selected
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
                                            <label class="form-label">Insurance Approval  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="InsuranceApproval" name="InsuranceApproval" required="">
                                               <!--  <option selected="" disabled="" value="">Select</option> -->
                                               
                                                <option value="No" <?php if($row7["InsuranceApproval"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                 <option value="Yes" <?php if($row7["InsuranceApproval"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Insurance Approval Date </label>
                                            <input type="date" name="InsuranceApprovalDate" id="InsuranceApprovalDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["InsuranceApprovalDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <?php if($ProjectId == 102 || $ProjectId == 103){?>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">PO Note Putup Approval  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="PoApproval" name="PoApproval" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["PoApproval"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["PoApproval"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">PO Note Putup Date </label>
                                            <input type="date" name="PoApprovalDate" id="PoApprovalDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["PoApprovalDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                       
                                        <div class="form-group col-md-3">
                                            <label class="form-label">DGM Approval  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="DgmApproval" name="DgmApproval" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["DgmApproval"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["DgmApproval"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">DGM Approval Date </label>
                                            <input type="date" name="DgmApprovalDate" id="DgmApprovalDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["DgmApprovalDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        <?php } ?>
                                           <div class="form-group col-md-3">
                                            <label class="form-label">File In Hand <span class="text-danger">*</span></label>
                                            <select class="form-control" id="FileInHand" name="FileInHand" required="">
                                              <!--   <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["FileInHand"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["FileInHand"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> File In Hand Date </label>
                                            <input type="date" name="FileInHandDate" id="FileInHandDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["FileInHandDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                    

                                  <?php if($ProjectId == 102 || $ProjectId == 103){?>
                                    <div class="form-group col-md-3">
                                            <label class="form-label">Bill Submit To Circle Office <span class="text-danger">*</span></label>
                                            <select class="form-control" id="CircleOfficeStatus" name="CircleOfficeStatus" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["CircleOfficeStatus"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["CircleOfficeStatus"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> Date </label>
                                            <input type="date" name="CircleOfficeDate" id="CircleOfficeDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["CircleOfficeDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                    <?php } ?>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">RMS Integration <span class="text-danger">*</span></label>
                                            <select class="form-control" id="RmsIntegrationStatus" name="RmsIntegrationStatus" required="">
                                               <!--  <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["RmsIntegrationStatus"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["RmsIntegrationStatus"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> RMS Integration Date </label>
                                            <input type="date" name="RmsIntegrationDate" id="RmsIntegrationDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["RmsIntegrationDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <?php if($ProjectId == 107){?>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">ICR Sign DO Office <span class="text-danger">*</span></label>
                                            <select class="form-control" id="IcrSignDoOffice" name="IcrSignDoOffice" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["IcrSignDoOffice"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["IcrSignDoOffice"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> ICR Sign DO Office Date </label>
                                            <input type="date" name="IcrSignDoOfficeDate" id="IcrSignDoOfficeDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["IcrSignDoOfficeDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-3">
                                            <label class="form-label">Bill Forward to RO <span class="text-danger">*</span></label>
                                            <select class="form-control" id="BillForward" name="BillForward" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["BillForward"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["BillForward"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> Bill Forward to RO Date </label>
                                            <input type="date" name="BillForwardDate" id="BillForwardDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["BillForwardDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-3">
                                            <label class="form-label">Files Forwarded to RO to RO Accts <span class="text-danger">*</span></label>
                                            <select class="form-control" id="RoToRoAccts" name="RoToRoAccts" required="">
                                               <!--  <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["RoToRoAccts"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["RoToRoAccts"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> RO to RO Accts Date </label>
                                            <input type="date" name="RoToRoAcctsDate" id="RoToRoAcctsDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["RoToRoAcctsDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Files Forwarded to RO Accts to ZO <span class="text-danger">*</span></label>
                                            <select class="form-control" id="RoAcctsToZo" name="RoAcctsToZo" required="">
                                               <!--  <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["RoAcctsToZo"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["RoAcctsToZo"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> RO Accts to ZO Date </label>
                                            <input type="date" name="RoAcctsToZoDate" id="RoAcctsToZoDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["RoAcctsToZoDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-3">
                                            <label class="form-label">Files Forwarded to ZO to HO <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ZoToHo" name="ZoToHo" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["ZoToHo"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["ZoToHo"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> ZO to HO Date </label>
                                            <input type="date" name="ZoToHoDate" id="ZoToHoDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ZoToHoDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Files Forwarded to HO to HO Accts <span class="text-danger">*</span></label>
                                            <select class="form-control" id="HoToHoAccts" name="HoToHoAccts" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["HoToHoAccts"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["HoToHoAccts"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> HO to HO Accts Date </label>
                                            <input type="date" name="HoToHoAcctsDate" id="HoToHoAcctsDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["HoToHoAcctsDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                    <?php } ?>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Files Forwarded to Payment <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ForwardToPayment" name="ForwardToPayment" required="">
                                               <!--  <option selected="" disabled="" value="">Select</option> -->
                                               
                                                <option value="No" <?php if($row7["ForwardToPayment"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                 <option value="Yes" <?php if($row7["ForwardToPayment"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> Payment Date </label>
                                            <input type="date" name="ForwardToPaymentDate" id="ForwardToPaymentDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ForwardToPaymentDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                      <?php if($ProjectId != 107){?>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Files Sent to HO <span class="text-danger">*</span></label>
                                            <select class="form-control" id="SentToHo" name="SentToHo" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                               
                                                <option value="No" <?php if($row7["SentToHo"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                 <option value="Yes" <?php if($row7["SentToHo"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label"> Files Sent to HO Date </label>
                                            <input type="date" name="SentToHoDate" id="SentToHoDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["SentToHoDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                         <?php } ?>
                                         
                                      
                                          
                                    

                                       
 
                                        
                                        <div class="form-group col-md-6">
                                            <label class="form-label">PO Site Visit For Inspection Photo 1 <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo15"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto15"
                                                    value="<?php echo $row7['Photo15'];?>" id="OldPhoto15">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo15']=='') {} else{?>
                                            <span id="show_photo15">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=15"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo15"></a><img
                                                        src="../uploads/<?php echo $row7['Photo15'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                         <div class="form-group col-md-6">
                                            <label class="form-label">PO Site Visit For Inspection Photo 2 <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo16"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto16"
                                                    value="<?php echo $row7['Photo16'];?>" id="OldPhoto16">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo16']=='') {} else{?>
                                            <span id="show_photo16">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=16"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo16"></a><img
                                                        src="../uploads/<?php echo $row7['Photo16'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                         <div class="form-group col-md-6">
                                            <label class="form-label">PO Site Visit For Inspection Photo 3 <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo17"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto17"
                                                    value="<?php echo $row7['Photo17'];?>" id="OldPhoto17">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo17']=='') {} else{?>
                                            <span id="show_photo17">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=17"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo17"></a><img
                                                        src="../uploads/<?php echo $row7['Photo17'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                         <div class="form-group col-md-6">
                                            <label class="form-label">PO Site Visit For Inspection Photo 4 <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo18"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto18"
                                                    value="<?php echo $row7['Photo18'];?>" id="OldPhoto18">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo18']=='') {} else{?>
                                            <span id="show_photo18">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        onClick="return confirm('Are you sure you want delete this photo');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&action=deletephoto&value=18"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo18"></a><img
                                                        src="../uploads/<?php echo $row7['Photo18'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                       <!-- <div class="form-group col-md-3">
                                            <label class="form-label">Payment Done  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="PaymentDone" name="PaymentDone" required="">
                                                <option selected="" disabled="" value="">Select</option>
                                                <option value="Yes" <?php if($row7["PaymentDone"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                                <option value="No" <?php if($row7["PaymentDone"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Payment Date </label>
                                            <input type="date" name="PaymentDate" id="PaymentDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["PaymentDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        -->
                                        
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Payment Released 90%  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Payment90" name="Payment90" required="">
                                               <!--  <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["Payment90"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["Payment90"]=='Yes') {?> selected
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
                                            <label class="form-label">Payment Released 10%  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Payment10" name="Payment10" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["Payment10"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["Payment10"]=='Yes') {?> selected
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
                                        </div>
                                         

                                        <!--<div class="form-group col-md-6">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="InstStatus" name="InstStatus" required="">
                                                <option selected="" disabled="" value="">Select Status</option>
                                                <option value="Installation" <?php if($row7["InstStatus"]=='Installation') {?> selected
                                                    <?php } ?>>Installation</option>
                                                <option value="PO Inspection" <?php if($row7["InstStatus"]=='PO Inspection') {?> selected
                                                    <?php } ?>>PO Inspection</option>
                                                    <option value="Payment Done" <?php if($row7["InstStatus"]=='Payment Done') {?> selected
                                                    <?php } ?>>Payment Done</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>-->


                                        

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Foundation Done  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Foundation" name="Foundation" required="">
                                                <option value="No" <?php if($row7["Foundation"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["Foundation"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-lg-4">
<label class="form-label"> Contractor<span class="text-danger">*</span></label>
 <select class="form-control" name="FoundationContractorId" id="FoundationContractorId" required>
<option selected="" value="">No One</option>
 <?php 
$sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(40) ORDER BY Fname";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>" <?php if($row7["FoundationContractorId"]==$result['id']) {?> selected <?php } ?>>
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
                                            <label class="form-label">Documentation Done  <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Documentation" name="Documentation" required="">
                                                <option value="No" <?php if($row7["Documentation"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["Documentation"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-lg-4">
<label class="form-label"> Contractor<span class="text-danger">*</span></label>
 <select class="form-control" name="DocumentationContractorId" id="DocumentationContractorId" required>
<option selected="" value="">No One</option>
 <?php 
$sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(40) ORDER BY Fname";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>" <?php if($row7["DocumentationContractorId"]==$result['id']) {?> selected <?php } ?>>
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
                                        

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Warranty Registration <span class="text-danger">*</span></label>
                                            <select class="form-control" id="WarrantyReg" name="WarrantyReg" required="">
                                                <!-- <option selected="" disabled="" value="">Select</option> -->
                                                
                                                <option value="No" <?php if($row7["WarrantyReg"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="Yes" <?php if($row7["WarrantyReg"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">  Warranty  Till Date </label>
                                            <input type="date" name="WarrantyRegDate" id="WarrantyRegDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["WarrantyRegDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

<!--<div class="form-group col-md-3">
                                            <label class="form-label">Sent to Service <span class="text-danger">*</span></label>
                                            <select class="form-control" id="SentToHo" name="SentToHo" required="">
                                                <option selected="" disabled="" value="">Select</option>
                                                <option value="Yes" <?php if($row7["SentToHo"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                                <option value="No" <?php if($row7["SentToHo"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">  Sent to Service Date </label>
                                            <input type="date" name="SentToHoDate" id="SentToHoDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["SentToHoDate"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                         -->

                                      

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
                dataType:"json",  
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