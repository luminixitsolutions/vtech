<?php
include_once 'config.php';

/*
 RUN THIS FILE DAILY USING CRON
 Example: 12:01 AM every day
*/

$sql = "
SELECT 
    id,
    current_stage,
    stage_start_date,
    allowed_days
FROM tbl_installation_flow
WHERE is_completed = 0
AND status = 'ACTIVE'
";

$res = $conn->query($sql);

while($row = $res->fetch_assoc()){

    $flowId       = $row['id'];
    $stage        = $row['current_stage'];
    $startDate    = $row['stage_start_date'];
    $allowedDays  = $row['allowed_days'];

    $daysPassed = (int)((time() - strtotime($startDate)) / 86400);

    if($daysPassed < $allowedDays){
        continue;
    }

    // Determine next stage
    switch($stage){
        case 'COORDINATOR':
            $nextStage = 'MANAGER';
            break;

        case 'MANAGER':
            $nextStage = 'GENERAL_MANAGER';
            break;

        case 'GENERAL_MANAGER':
            $nextStage = 'BUSINESS_HEAD';
            break;

        case 'BUSINESS_HEAD':
            $nextStage = 'DISPUTE';
            break;

        default:
            continue 2;
    }

    // Update flow
    mysqli_query($conn,"
        UPDATE tbl_installation_flow
        SET current_stage = '$nextStage',
            stage_start_date = NOW()
        WHERE id = '$flowId'
    ");

    // Log escalation
    mysqli_query($conn,"
        INSERT INTO tbl_installation_actions
        (flow_id, action_by, action_type, remarks, action_date)
        VALUES
        ('$flowId', 0, 'ESCALATED',
         'Auto escalated to $nextStage after $allowedDays days',
         NOW())
    ");
}

echo "Escalation Cron Executed";
?>