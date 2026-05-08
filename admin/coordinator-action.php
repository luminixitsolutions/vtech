<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$userId = $_SESSION['Admin']['id'];

$action = $_POST['action'];
$flowId = intval($_POST['flowId']);

if($action === 'followup'){

    $remark = addslashes($_POST['remark']);

    mysqli_query($conn,"
        INSERT INTO tbl_installation_actions
        (flow_id, action_by, action_type, remarks)
        VALUES
        ('$flowId','$userId','FOLLOW_UP','$remark')
    ");
}

if($action === 'installed'){

    mysqli_query($conn,"
        UPDATE tbl_installation_flow
        SET is_completed=1,
            status='COMPLETED'
        WHERE id='$flowId'
    ");

    mysqli_query($conn,"
        INSERT INTO tbl_installation_actions
        (flow_id, action_by, action_type, remarks)
        VALUES
        ('$flowId','$userId','INSTALL_DONE','Installation completed')
    ");
}
