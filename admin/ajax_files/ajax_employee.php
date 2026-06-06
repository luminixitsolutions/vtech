<?php
session_start();
include_once '../config.php';
require_once dirname(__DIR__) . '/inc-employee-menu-options.php';

function ajaxEmployeeIsXhr()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function ajaxEmployeeRespondDuplicate($roll)
{
    if (ajaxEmployeeIsXhr()) {
        header('Content-Type: text/plain; charset=utf-8');
        echo '0';
        exit;
    }
    if ($roll == 27) {
        echo "<script>alert('Phone No already Exist!');window.location.href='../user_management/add-store-incharge.php';</script>";
    } elseif ($roll == 26) {
        echo "<script>alert('Phone No already Exist!');window.location.href='../user_management/add-dispatch-officer.php';</script>";
    } else {
        echo "<script>alert('Phone No already Exist!');window.location.href='../user_management/add-employee.php';</script>";
    }
    exit;
}

function ajaxEmployeeRespondSaved($roll, $isNew)
{
    if (ajaxEmployeeIsXhr()) {
        header('Content-Type: text/plain; charset=utf-8');
        echo '1';
        exit;
    }
    $verb = $isNew ? 'Created' : 'Updated';
    if ($roll == 27) {
        $url = '../user_management/view-store-incharge.php';
    } elseif ($roll == 26) {
        $url = '../user_management/view-dispatch-officer.php';
    } else {
        $url = '../user_management/view-employee.php';
    }
    echo "<script>alert('Record $verb Successfully!');window.location.href='$url';</script>";
    exit;
}

function ajaxEmployeePostInt($key, $default = 0)
{
    if (!isset($_POST[$key]) || $_POST[$key] === '' || $_POST[$key] === null) {
        return (int) $default;
    }
    return (int) $_POST[$key];
}

function ajaxEmployeePostImplode($key)
{
    if (empty($_POST[$key])) {
        return '0';
    }
    $val = $_POST[$key];
    if (is_array($val)) {
        return implode(',', array_map('intval', $val));
    }
    return preg_replace('/[^0-9,]/', '', (string) $val);
}

function ajaxEmployeePostOptions()
{
    if (empty($_POST['Options'])) {
        return '0';
    }
    $val = $_POST['Options'];
    if (is_array($val)) {
        return implode(',', array_map('intval', $val));
    }
    return preg_replace('/[^0-9,]/', '', (string) $val);
}

function ajaxEmployeeSqlDate($key)
{
    global $conn;
    $v = trim((string) ($_POST[$key] ?? ''));
    if ($v === '') {
        return 'NULL';
    }
    return "'" . $conn->real_escape_string($v) . "'";
}

function ajaxEmployeeFail($code, $message = '')
{
    if (ajaxEmployeeIsXhr()) {
        header('Content-Type: text/plain; charset=utf-8');
        echo $message !== '' ? $code . ':' . $message : $code;
        exit;
    }
    echo "<script>alert(" . json_encode($message !== '' ? $message : 'Save failed.') . ");history.back();</script>";
    exit;
}

if(isset($_POST['action']) && $_POST['action'] == 'Save'){
$user_id = (int) ($_SESSION['Admin']['id'] ?? 0);
if ($user_id <= 0) {
    ajaxEmployeeFail('-3', 'Session expired. Please login again.');
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$Fname = addslashes(trim($_POST['Fname'] ?? ''));
$Mname = addslashes(trim($_POST['Mname'] ?? ''));
$Lname = addslashes(trim($_POST['Lname'] ?? ''));
$Phone = trim((string)($_POST['Phone'] ?? ''));
$EmailId = $_POST['EmailId'] ?? '';
$Phone2 = $_POST['Phone2'] ?? '';
$Password = addslashes($_POST['Password'] ?? '');
$CountryId = ajaxEmployeePostInt('CountryId', 0);
$StateId = ajaxEmployeePostInt('StateId', 0);
$CityId = ajaxEmployeePostInt('CityId', 0);
$Address = addslashes(trim($_POST['Address'] ?? ''));
$GstNo = addslashes(trim($_POST['GstNo'] ?? ''));
$Pincode = trim($_POST['Pincode'] ?? '');
$Details = addslashes(trim($_POST['Details'] ?? ''));

$FatherPhone = addslashes(trim($_POST['FatherPhone'] ?? ''));
$Designation = addslashes(trim($_POST['Designation'] ?? ''));
$Dob = ajaxEmployeeSqlDate('Dob');
$AadharNo = addslashes(trim($_POST['AadharNo'] ?? ''));
$BloodGroup = addslashes(trim($_POST['BloodGroup'] ?? ''));
$JoinDate = ajaxEmployeeSqlDate('JoinDate');
$EmailId2 = addslashes(trim($_POST['EmailId2'] ?? ''));
$UnderUser = ajaxEmployeePostInt('UnderUser', 0);

$Status = ajaxEmployeePostInt('Status', 0);
$CatId = ajaxEmployeePostInt('CatId', 0);
$Roll = ajaxEmployeePostInt('Roll', 0);
$menuAccessPosted = !empty($_POST['menu_access_posted']);
$Options = employeeMenuResolveOptionsForSave(
    $_POST['Options'] ?? null,
    $Roll,
    $id,
    $menuAccessPosted
);

$PanNo = addslashes(trim($_POST['PanNo'] ?? ''));
$CompId = ajaxEmployeePostInt('CompId', 0);
$BranchId = ajaxEmployeePostInt('BranchId', 0);
$InTime = addslashes(trim($_POST['InTime'] ?? ''));
$OutTime = addslashes(trim($_POST['OutTime'] ?? ''));
$ImmediateBossId = ajaxEmployeePostInt('ImmediateBossId', 0);
$ImmediateBoss = ajaxEmployeePostInt('ImmediateBoss', 0);
$OfficeEmployee = (isset($_POST['OfficeEmployee']) && $_POST['OfficeEmployee'] === '1') ? 1 : 0;
$RooftopBranchId = ajaxEmployeePostInt('RooftopBranchId', 0);
$UnderByManager = ajaxEmployeePostInt('UnderByManager', 0);
$UnderByGrManager = ajaxEmployeePostInt('UnderByGrManager', 0);
$UnderByBusHead = ajaxEmployeePostInt('UnderByBusHead', 0);
$CreatedDate = date('Y-m-d');

$MulBranchId = ajaxEmployeePostImplode('MulBranchId');
$MulRooftopBranchId = ajaxEmployeePostImplode('MulRooftopBranchId');

$randno = rand(1,100);
$Photo = isset($_POST['OldPhoto']) ? $_POST['OldPhoto'] : '';
if (!empty($_FILES['Photo']['tmp_name']) && is_uploaded_file($_FILES['Photo']['tmp_name']) && !empty($_FILES['Photo']['name'])) {
    $photoName = $_FILES['Photo']['name'];
    $dot = strrpos($photoName, '.');
    if ($dot !== false) {
        $fnm = str_replace(' ', '_', substr($photoName, 0, $dot));
        $ext = substr($photoName, $dot);
        $dest = '../../uploads/' . $randno . '_' . $fnm . $ext;
        $imagepath = $randno . '_' . $fnm . $ext;
        if (move_uploaded_file($_FILES['Photo']['tmp_name'], $dest)) {
            $Photo = $imagepath;
        }
    }
}

$randno2 = rand(1,100);
$Photo2 = isset($_POST['OldPhoto2']) ? $_POST['OldPhoto2'] : '';
if (!empty($_FILES['Photo2']['tmp_name']) && is_uploaded_file($_FILES['Photo2']['tmp_name']) && !empty($_FILES['Photo2']['name'])) {
    $photoName2 = $_FILES['Photo2']['name'];
    $dot2 = strrpos($photoName2, '.');
    if ($dot2 !== false) {
        $fnm2 = str_replace(' ', '_', substr($photoName2, 0, $dot2));
        $ext2 = substr($photoName2, $dot2);
        $dest2 = '../../uploads/' . $randno2 . '_' . $fnm2 . $ext2;
        $imagepath2 = $randno2 . '_' . $fnm2 . $ext2;
        if (move_uploaded_file($_FILES['Photo2']['tmp_name'], $dest2)) {
            $Photo2 = $imagepath2;
        }
    }
}

$randno3 = rand(1,100);
$Photo3 = isset($_POST['OldPhoto3']) ? $_POST['OldPhoto3'] : '';
if (!empty($_FILES['Photo3']['tmp_name']) && is_uploaded_file($_FILES['Photo3']['tmp_name']) && !empty($_FILES['Photo3']['name'])) {
    $photoName3 = $_FILES['Photo3']['name'];
    $dot3 = strrpos($photoName3, '.');
    if ($dot3 !== false) {
        $fnm3 = str_replace(' ', '_', substr($photoName3, 0, $dot3));
        $ext3 = substr($photoName3, $dot3);
        $dest3 = '../../uploads/' . $randno3 . '_' . $fnm3 . $ext3;
        $imagepath3 = $randno3 . '_' . $fnm3 . $ext3;
        if (move_uploaded_file($_FILES['Photo3']['tmp_name'], $dest3)) {
            $Photo3 = $imagepath3;
        }
    }
}


if($id <= 0){
    $sql = "SELECT * FROM tbl_users WHERE Phone='$Phone' AND Roll='$Roll'";
    $rncnt = getRow($sql);
    if($rncnt > 0){
        ajaxEmployeeRespondDuplicate($Roll);
    }
    else{
$empNewData = [
    'Fname' => $Fname, 'Phone' => $Phone, 'Roll' => $Roll, 'Status' => $Status, 'CompId' => $CompId,
];
$sql = "INSERT INTO tbl_users SET UnderByManager='$UnderByManager',UnderByGrManager='$UnderByGrManager',UnderByBusHead='$UnderByBusHead',MulRooftopBranchId='$MulRooftopBranchId',MulBranchId='$MulBranchId',UnderUser='$UnderUser',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='$Roll',CreatedDate='$CreatedDate',CreatedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',Options='$Options',CompId='$CompId',RooftopBranchId='$RooftopBranchId',BranchId='$BranchId',FatherPhone='$FatherPhone',Designation='$Designation',Dob=$Dob,AadharNo='$AadharNo',BloodGroup='$BloodGroup',JoinDate=$JoinDate,EmailId2='$EmailId2',InTime='$InTime',OutTime='$OutTime',ImmediateBossId='$ImmediateBossId',ImmediateBoss='$ImmediateBoss'";
if (!$conn->query($sql)) {
    ajaxEmployeeFail('-1', $conn->error);
}
$EmpId = mysqli_insert_id($conn);
$CustomerId = "VTECH-E".$EmpId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'";
$conn->query($sql3);
$conn->query("INSERT INTO tbl_user2 (id, OfficeEmployee) VALUES ('$EmpId', '$OfficeEmployee') ON DUPLICATE KEY UPDATE OfficeEmployee='$OfficeEmployee'");

if (function_exists('addEmployeeLog')) {
    $empNewData['id'] = $EmpId;
    addEmployeeLog([
        'action_type' => EMP_ACT_ADD_RECORD,
        'record_table' => 'tbl_users',
        'record_id' => (string) $EmpId,
        'new_data' => $empNewData,
        'page_name' => 'Employee Account',
        'module_name' => 'User Management',
    ]);
}

ajaxEmployeeRespondSaved($Roll, true);
}
}
else{
    $sql = "SELECT * FROM tbl_users WHERE Phone='$Phone' AND Roll='$Roll' AND id!='$id'";
    $rncnt = getRow($sql);
    if($rncnt > 0){
        ajaxEmployeeRespondDuplicate($Roll);
    }
    else{
$empOldRow = function_exists('employeeActivityLogFetchUserRowForEdit')
    ? employeeActivityLogFetchUserRowForEdit($id) : null;
$sql = "UPDATE tbl_users SET UnderByManager='$UnderByManager',UnderByGrManager='$UnderByGrManager',UnderByBusHead='$UnderByBusHead',MulRooftopBranchId='$MulRooftopBranchId',MulBranchId='$MulBranchId',UnderUser='$UnderUser',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='$Roll',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',Options='$Options',CompId='$CompId',RooftopBranchId='$RooftopBranchId',BranchId='$BranchId',FatherPhone='$FatherPhone',Designation='$Designation',Dob=$Dob,AadharNo='$AadharNo',BloodGroup='$BloodGroup',JoinDate=$JoinDate,EmailId2='$EmailId2',InTime='$InTime',OutTime='$OutTime',ImmediateBossId='$ImmediateBossId',ImmediateBoss='$ImmediateBoss' WHERE id='$id'";
if (!$conn->query($sql)) {
    ajaxEmployeeFail('-1', $conn->error);
}
$conn->query("INSERT INTO tbl_user2 (id, OfficeEmployee) VALUES ('$id', '$OfficeEmployee') ON DUPLICATE KEY UPDATE OfficeEmployee='$OfficeEmployee'");

if (function_exists('addEmployeeLog')) {
    $empNewRow = function_exists('employeeActivityLogFetchUserRowForEdit')
        ? employeeActivityLogFetchUserRowForEdit($id) : null;
    addEmployeeLog([
        'action_type' => EMP_ACT_EDIT_RECORD,
        'record_table' => 'tbl_users',
        'record_id' => (string) $id,
        'old_data' => $empOldRow,
        'new_data' => $empNewRow,
        'page_name' => 'Employee Account',
        'module_name' => 'User Management',
    ]);
}

ajaxEmployeeRespondSaved($Roll, false);
}
}
}

if(isset($_POST['action']) && $_POST['action'] == 'deletePhoto'){
   	$id = (int) $_POST['id'];
    $Photo = $_POST['Photo'];
    if (function_exists('addEmployeeLog')) {
        addEmployeeLog([
            'action_type' => EMP_ACT_DELETE_RECORD,
            'record_table' => 'tbl_users',
            'record_id' => (string) $id,
            'old_data' => ['Photo' => $Photo],
            'page_name' => 'Employee Photo',
            'module_name' => 'User Management',
        ]);
    }
    $q = "UPDATE tbl_users SET Photo='' WHERE id=$id";
    $conn->query($q);
    echo "File Deleted Successfully";
}

if (isset($_POST['action']) && $_POST['action'] === 'getDefaultOptionsByRoll') {
    $roll = ajaxEmployeePostInt('Roll', 0);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(employeeMenuDefaultOptionIdsForRoll($roll));
    exit;
}

if(isset($_POST['action']) && $_POST['action'] == 'getUserDetails'){
$id = $_POST['id'];
$sql = "SELECT tu.*,tu2.Fname AS AgentName FROM tbl_users tu LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id WHERE tu.id='$id'";
$row = getRecord($sql);
echo json_encode($row);
}

if(isset($_POST['action']) && $_POST['action'] == 'getUserDetails2'){
$CellNo = $_POST['CellNo'];
$sql = "SELECT tu.*,tu2.Fname AS AgentName FROM tbl_users tu LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id WHERE tu.Phone='$CellNo'";
$row = getRecord($sql);
echo json_encode($row);
}