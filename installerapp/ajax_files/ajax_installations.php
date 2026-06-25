<?php
session_start();
include_once '../config.php';

header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_OFF);

function installation_json_error($message)
{
    echo json_encode(array('error' => $message));
    exit;
}

function installation_upload_photo($fileKey, $fallback = '')
{
    if (!isset($_FILES[$fileKey]) || !is_uploaded_file($_FILES[$fileKey]['tmp_name'])) {
        return $fallback;
    }

    $origName = trim((string) ($_FILES[$fileKey]['name'] ?? ''));
    if ($origName === '') {
        return $fallback;
    }

    $dotPos = strrpos($origName, '.');
    if ($dotPos === false) {
        return $fallback;
    }

    $randno = rand(1, 100);
    $fnm = str_replace(' ', '_', substr($origName, 0, $dotPos));
    $ext = substr($origName, $dotPos);
    $imagepath = $randno . '_' . $fnm . $ext;
    $dest = dirname(__DIR__, 2) . '/uploads/' . $imagepath;

    if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $dest)) {
        return $imagepath;
    }

    return $fallback;
}

if (($_POST['action'] ?? '') !== 'saveinstallation') {
    installation_json_error('Invalid action');
}

$user_id = (int) ($_SESSION['User']['id'] ?? $_SESSION['Admin']['id'] ?? 0);
$custId = (int) ($_POST['id'] ?? $_POST['CustId'] ?? 0);

if ($custId < 1) {
    installation_json_error('Invalid customer id');
}

$custRow = getRecord("SELECT * FROM tbl_users WHERE id='$custId' LIMIT 1");
if (!is_array($custRow) || empty($custRow['id'])) {
    installation_json_error('Customer not found');
}

$CreatedDate = date('Y-m-d');
$InstallationDate = addslashes(trim((string) ($_POST['InstallationDate'] ?? '')));
$WaterOutputPhotoDate = addslashes(trim((string) ($_POST['WaterOutputPhotoDate'] ?? '')));
$InstallationPhotoDate = addslashes(trim((string) ($_POST['InstallationPhotoDate'] ?? '')));
$Lattitude = addslashes(trim((string) ($_POST['Lattitude'] ?? '')));
$Longitude = addslashes(trim((string) ($_POST['Longitude'] ?? '')));
$Money = addslashes(trim((string) ($_POST['Money'] ?? '')));
$Specify = addslashes(trim((string) ($_POST['Specify'] ?? '')));
$phone = trim((string) ($_POST['phone'] ?? ''));
$ProjType = addslashes(trim((string) ($_POST['Type'] ?? '1')));

$CellNo = addslashes((string) ($custRow['Phone'] ?? $phone));
$CustName = addslashes((string) ($custRow['Fname'] ?? ''));
$Address = addslashes((string) ($custRow['Address'] ?? ''));

$oldPhoto = addslashes(trim((string) ($_POST['OldPhoto'] ?? ($custRow['InstPhoto1'] ?? ''))));
$oldPhoto2 = addslashes(trim((string) ($_POST['OldPhoto2'] ?? ($custRow['InstPhoto2'] ?? ''))));

$Photo = addslashes(installation_upload_photo('Photo', $oldPhoto));
$Photo2 = addslashes(installation_upload_photo('Photo2', $oldPhoto2));

$sql = "UPDATE tbl_users SET InstPhoto1='$Photo',InstPhoto2='$Photo2',InstallationDate='$InstallationDate',InstMoney='$Money',InstSpecify='$Specify',InstalledBy='$user_id',InstLattitude='$Lattitude',InstLongitude='$Longitude',InstOtpVerify=0 WHERE id='$custId'";
if (!$conn->query($sql)) {
    installation_json_error('Failed to update customer: ' . $conn->error);
}

$existing = getRecord("SELECT id FROM tbl_installations WHERE CustId='$custId' LIMIT 1");
$instId = 0;

if (is_array($existing) && !empty($existing['id'])) {
    $instId = (int) $existing['id'];
    $sql = "UPDATE tbl_installations SET WaterOutputPhotoDate='$WaterOutputPhotoDate',InstallationPhotoDate='$InstallationPhotoDate',CellNo='$CellNo',CustName='$CustName',Address='$Address',Lattitude='$Lattitude',Longitude='$Longitude',InstStatus='Installation',Status=1,Type='$ProjType',Photo13='$Photo2',Photo1='$Photo',InstallationDate='$InstallationDate' WHERE CustId='$custId'";
    if (!$conn->query($sql)) {
        installation_json_error('Failed to update installation: ' . $conn->error);
    }
} else {
    $sql = "INSERT INTO tbl_installations SET WaterOutputPhotoDate='$WaterOutputPhotoDate',InstallationPhotoDate='$InstallationPhotoDate',CustId='$custId',CellNo='$CellNo',CustName='$CustName',Address='$Address',Lattitude='$Lattitude',Longitude='$Longitude',InstStatus='Installation',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',ModifiedBy='0',Type='$ProjType',Photo13='$Photo2',Photo1='$Photo',InstallationDate='$InstallationDate',FoundationContractorId='0',DocumentationContractorId='0',PaymentStatus='0',ProjectId='0',ProjectSubHeadId='0',AssignPendingFileSubmissionTo='0'";
    if (!$conn->query($sql)) {
        installation_json_error('Failed to create installation: ' . $conn->error);
    }
    $instId = (int) mysqli_insert_id($conn);

    $conn->query("DELETE FROM tbl_crop_image WHERE UserId='$user_id'");
}

$_SESSION['otp'] = rand(1000, 9999);

$responsePhone = $phone !== '' ? $phone : (string) ($custRow['Phone'] ?? '');
echo json_encode(array(
    'id' => $custId,
    'phone' => $responsePhone,
    'InstId' => $instId,
));
