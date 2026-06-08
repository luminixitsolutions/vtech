<?php
session_start();
include_once '../config.php';
$user_id = (int) ($_SESSION['Admin']['id'] ?? 0);

function driverIntField($value)
{
    $value = trim((string) $value);
    return $value === '' ? 0 : (int) $value;
}

function driverFloatField($value)
{
    $value = trim((string) $value);
    return $value === '' ? 0 : (float) $value;
}

function driverUploadPhoto($fileKey, $oldPhoto)
{
    if (empty($_FILES[$fileKey]['tmp_name']) || empty($_FILES[$fileKey]['name'])) {
        return (string) $oldPhoto;
    }

    $randno = rand(1, 100);
    $name = $_FILES[$fileKey]['name'];
    $dotPos = strrpos($name, '.');
    $fnm = $dotPos === false ? str_replace(' ', '_', $name) : str_replace(' ', '_', substr($name, 0, $dotPos));
    $ext = $dotPos === false ? '' : substr($name, $dotPos);
    $dest = '../../uploads/' . $randno . '_' . $fnm . $ext;

    if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $dest)) {
        return $randno . '_' . $fnm . $ext;
    }

    return (string) $oldPhoto;
}

function driverFail($message)
{
    echo "<script>alert(" . json_encode($message) . ");history.back();</script>";
    exit;
}

function driverBuildInsertRow($conn, array $post, $userId)
{
    $res = $conn->query('SELECT * FROM tbl_users WHERE Roll=39 ORDER BY id DESC LIMIT 1');
    $tpl = $res ? $res->fetch_assoc() : null;
    if (!$tpl) {
        return null;
    }

    $today = date('Y-m-d');
    $intFields = ['CountryId', 'StateId', 'CityId', 'CompId', 'BranchId', 'RooftopBranchId', 'Status', 'UnderUser'];
    foreach ($intFields as $field) {
        $val = trim((string) ($post[$field] ?? ''));
        $tpl[$field] = $val === '' ? 0 : (int) $val;
    }

    $textFields = [
        'Fname', 'Mname', 'Lname', 'Phone', 'Phone2', 'EmailId', 'Address', 'GstNo', 'PanNo', 'Pincode', 'CatId', 'Details',
        'VehicalNo', 'VehicalModel', 'EngineNo', 'VehAverage', 'CompanyName',
    ];
    foreach ($textFields as $field) {
        if (array_key_exists($field, $post)) {
            $tpl[$field] = (string) $post[$field];
        }
    }
    if (array_key_exists('PerDayVehicle', $post)) {
        $tpl['PerDayVehicle'] = driverFloatField($post['PerDayVehicle']);
    }

    $tpl['Password'] = (string) ($post['Password'] ?? '12345');
    $tpl['Photo'] = (string) ($post['Photo'] ?? '');
    $tpl['Roll'] = 39;
    $tpl['CustomerId'] = 'VTECH-D0';
    $tpl['CreatedDate'] = $today;
    $tpl['ModifiedDate'] = $today;
    $tpl['CreatedBy'] = (int) $userId;
    $tpl['ModifiedBy'] = (int) $userId;
    unset($tpl['id']);

    $dateCols = $conn->query("SHOW COLUMNS FROM tbl_users WHERE Type LIKE 'date%'");
    if ($dateCols) {
        while ($col = $dateCols->fetch_assoc()) {
            $name = $col['Field'];
            if (!array_key_exists($name, $tpl)) {
                continue;
            }
            $val = (string) $tpl[$name];
            if ($val === '' || $val === '0000-00-00') {
                $tpl[$name] = $today;
            }
        }
    }

    return $tpl;
}

if (($_POST['action'] ?? '') == 'Save') {
    $id = trim((string) ($_POST['id'] ?? ''));
    $CreatedDate = date('Y-m-d');
    $Photo = driverUploadPhoto('Photo', $_POST['OldPhoto'] ?? '');

    $post = [
        'Fname' => trim($_POST['Fname'] ?? ''),
        'Mname' => trim($_POST['Mname'] ?? ''),
        'Lname' => trim($_POST['Lname'] ?? ''),
        'Phone' => trim($_POST['Phone'] ?? ''),
        'Phone2' => trim($_POST['Phone2'] ?? ''),
        'EmailId' => trim($_POST['EmailId'] ?? ''),
        'Password' => trim($_POST['Password'] ?? '12345'),
        'Address' => trim($_POST['Address'] ?? ''),
        'GstNo' => trim($_POST['GstNo'] ?? ''),
        'PanNo' => trim($_POST['PanNo'] ?? ''),
        'Pincode' => trim($_POST['Pincode'] ?? ''),
        'CompId' => driverIntField($_POST['CompId'] ?? 0),
        'BranchId' => driverIntField($_POST['BranchId'] ?? 0),
        'RooftopBranchId' => driverIntField($_POST['RooftopBranchId'] ?? 0),
        'UnderUser' => driverIntField($_POST['UnderUser'] ?? 0),
        'Status' => driverIntField($_POST['Status'] ?? 1),
        'VehicalNo' => trim($_POST['VehicalNo'] ?? ''),
        'VehicalModel' => trim($_POST['VehicalModel'] ?? ''),
        'EngineNo' => trim($_POST['EngineNo'] ?? ''),
        'VehAverage' => trim($_POST['VehAverage'] ?? ''),
        'CompanyName' => trim($_POST['CompanyName'] ?? ''),
        'PerDayVehicle' => driverFloatField($_POST['PerDayVehicle'] ?? 0),
        'Photo' => $Photo,
    ];

    if ($post['Fname'] === '' || $post['Phone'] === '') {
        driverFail('Driver name and mobile number are required.');
    }
    if ($post['UnderUser'] <= 0) {
        driverFail('Please select a transportor.');
    }
    if ($post['VehicalNo'] === '' || $post['VehAverage'] === '') {
        driverFail('Vehicle number and vehicle average are required.');
    }

    if ($id === '') {
        $row = driverBuildInsertRow($conn, $post, $user_id);
        if (!$row) {
            driverFail('Unable to create driver account. No template user found.');
        }

        $sets = [];
        foreach ($row as $key => $value) {
            if ($value === null) {
                continue;
            }
            $sets[] = '`' . $key . "`='" . $conn->real_escape_string((string) $value) . "'";
        }

        $sql = 'INSERT INTO tbl_users SET ' . implode(',', $sets);
        if (!$conn->query($sql)) {
            driverFail('Error: ' . $conn->error);
        }

        $empId = mysqli_insert_id($conn);
        $customerId = 'VTECH-D' . $empId;
        $conn->query("UPDATE tbl_users SET CustomerId='$customerId' WHERE id='$empId'");
        echo "<script>alert('Record Created Successfully!');window.location.href='../user_management/view-drivers.php';</script>";
        exit;
    }

    $Fname = addslashes($post['Fname']);
    $Mname = addslashes($post['Mname']);
    $Lname = addslashes($post['Lname']);
    $Phone = addslashes($post['Phone']);
    $EmailId = addslashes($post['EmailId']);
    $Phone2 = addslashes($post['Phone2']);
    $Password = addslashes($post['Password']);
    $Address = addslashes($post['Address']);
    $GstNo = addslashes($post['GstNo']);
    $PanNo = addslashes($post['PanNo']);
    $Pincode = addslashes($post['Pincode']);
    $CompId = (int) $post['CompId'];
    $BranchId = (int) $post['BranchId'];
    $RooftopBranchId = (int) $post['RooftopBranchId'];
    $UnderUser = (int) $post['UnderUser'];
    $Status = (int) $post['Status'];
    $Photo = addslashes($post['Photo']);
    $VehicalNo = addslashes($post['VehicalNo']);
    $VehicalModel = addslashes($post['VehicalModel']);
    $EngineNo = addslashes($post['EngineNo']);
    $VehAverage = addslashes($post['VehAverage']);
    $CompanyName = addslashes($post['CompanyName']);
    $PerDayVehicle = driverFloatField($post['PerDayVehicle']);
    $idEsc = $conn->real_escape_string($id);

    $sql = "UPDATE tbl_users SET UnderUser='$UnderUser',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='39',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',GstNo='$GstNo',PanNo='$PanNo',CompId='$CompId',RooftopBranchId='$RooftopBranchId',BranchId='$BranchId',VehicalNo='$VehicalNo',VehicalModel='$VehicalModel',EngineNo='$EngineNo',VehAverage='$VehAverage',CompanyName='$CompanyName',PerDayVehicle='$PerDayVehicle' WHERE id='$idEsc'";
    if (!$conn->query($sql)) {
        driverFail('Error: ' . $conn->error);
    }

    echo "<script>alert('Record Updated Successfully!');window.location.href='../user_management/view-drivers.php';</script>";
    exit;
}

if (($_POST['action'] ?? '') == 'deletePhoto') {
    $id = (int) ($_POST['id'] ?? 0);
    $conn->query("UPDATE tbl_users SET Photo='' WHERE id=$id");
    echo 'File Deleted Successfully';
}

if (($_POST['action'] ?? '') == 'getUserDetails') {
    $id = $_POST['id'] ?? '';
    $sql = "SELECT tu.*,tu2.Fname AS AgentName FROM tbl_users tu LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id WHERE tu.id='$id'";
    $row = getRecord($sql);
    echo json_encode($row);
}

if (($_POST['action'] ?? '') == 'getUserDetails2') {
    $CellNo = $_POST['CellNo'] ?? '';
    $sql = "SELECT tu.*,tu2.Fname AS AgentName FROM tbl_users tu LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id WHERE tu.Phone='$CellNo'";
    $row = getRecord($sql);
    echo json_encode($row);
}
