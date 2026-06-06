<?php
/**
 * MSEDCL SMART PROJECT — standalone rooftop module (PMSGY → Mahadiscom → Payment → Survey).
 */

define('MSEDCL_SMART_STAGE_PMSGY', 'pmsgy');
define('MSEDCL_SMART_STAGE_MAHADISCOM', 'mahadiscom');
define('MSEDCL_SMART_STAGE_SURVEY_PENDING', 'survey_pending');
define('MSEDCL_SMART_STAGE_SURVEY_DONE', 'survey_done');

define('MSEDCL_SMART_MODULE_DIR', 'msedcl_smart');
define('MSEDCL_SMART_OPT_DASHBOARD', 188);
define('MSEDCL_SMART_OPT_PMSGY', 189);
define('MSEDCL_SMART_OPT_MAHADISCOM', 190);
define('MSEDCL_SMART_OPT_PAYMENT', 191);
define('MSEDCL_SMART_OPT_SURVEY_PENDING', 192);
define('MSEDCL_SMART_OPT_ABSTRACT', 193);

function msedclSmartModuleUrl($file = 'dashboard.php')
{
    return MSEDCL_SMART_MODULE_DIR . '/' . ltrim($file, '/');
}

function msedclSmartInitUserAccess()
{
    global $Roll, $Options, $row77, $user_id;

    $user_id = (int) $_SESSION['Admin']['id'];
    $row77 = getRecord("SELECT * FROM tbl_users WHERE id='$user_id'");
    if (!is_array($row77)) {
        $row77 = [];
    }
    $Roll = (int) ($row77['Roll'] ?? 0);

    if (!function_exists('adminResolveMenuOptionsFromUserRow')) {
        require_once __DIR__ . '/inc-menu-option-groups.php';
    }
    $Options = adminResolveMenuOptionsFromUserRow($row77);

    return [
        'Roll' => $Roll,
        'Options' => $Options,
        'row77' => $row77,
        'user_id' => $user_id,
    ];
}

function msedclSmartCanAccessOption($optionId)
{
    global $Roll, $Options;

    if (!function_exists('adminUserHasFullMenuAccess')) {
        require_once __DIR__ . '/inc-menu-option-groups.php';
    }
    if (adminUserHasFullMenuAccess($Roll ?? 0)) {
        return true;
    }

    return in_array((string) (int) $optionId, $Options ?? [], true);
}

function msedclSmartHasAnyAccess()
{
    foreach ([
        MSEDCL_SMART_OPT_DASHBOARD,
        MSEDCL_SMART_OPT_PMSGY,
        MSEDCL_SMART_OPT_MAHADISCOM,
        MSEDCL_SMART_OPT_PAYMENT,
        MSEDCL_SMART_OPT_SURVEY_PENDING,
        MSEDCL_SMART_OPT_ABSTRACT,
    ] as $optionId) {
        if (msedclSmartCanAccessOption($optionId)) {
            return true;
        }
    }

    return false;
}

function msedclSmartRequireAnyAccess()
{
    if (!msedclSmartHasAnyAccess()) {
        header('Location: ../dashboard.php');
        exit;
    }
}

function msedclSmartFirstAccessiblePage()
{
    $map = [
        MSEDCL_SMART_OPT_DASHBOARD => 'dashboard.php',
        MSEDCL_SMART_OPT_PMSGY => 'pmsgy.php',
        MSEDCL_SMART_OPT_MAHADISCOM => 'mahadiscom.php',
        MSEDCL_SMART_OPT_PAYMENT => 'payment.php',
        MSEDCL_SMART_OPT_SURVEY_PENDING => 'survey-pending.php',
        MSEDCL_SMART_OPT_ABSTRACT => 'abstract.php',
    ];

    foreach ($map as $optionId => $file) {
        if (msedclSmartCanAccessOption($optionId)) {
            return $file;
        }
    }

    return '../dashboard.php';
}

function msedclSmartRequireOption($optionId)
{
    if (!msedclSmartCanAccessOption($optionId)) {
        header('Location: ' . msedclSmartFirstAccessiblePage());
        exit;
    }
}

function msedclSmartEnsureTables()
{
    global $conn;
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;

    $conn->query("CREATE TABLE IF NOT EXISTS tbl_rooftop_msedcl_smart_customers (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      BeneficiaryId VARCHAR(50) DEFAULT NULL,
      CustName VARCHAR(255) DEFAULT NULL,
      CellNo VARCHAR(50) DEFAULT NULL,
      District VARCHAR(100) DEFAULT NULL,
      Taluka VARCHAR(100) DEFAULT NULL,
      Village VARCHAR(100) DEFAULT NULL,
      Block VARCHAR(100) DEFAULT NULL,
      Address TEXT,
      PumpCapacity VARCHAR(50) DEFAULT NULL,
      WoNo VARCHAR(50) DEFAULT NULL,
      PmsgyApplied TINYINT NOT NULL DEFAULT 0,
      PmsgyAppliedDate DATE DEFAULT NULL,
      MahadiscomApplied TINYINT NOT NULL DEFAULT 0,
      MahadiscomAppliedDate DATE DEFAULT NULL,
      PaymentDone TINYINT NOT NULL DEFAULT 0,
      PaymentDoneDate DATE DEFAULT NULL,
      SurveyDone TINYINT NOT NULL DEFAULT 0,
      SurveyDoneDate DATE DEFAULT NULL,
      CurrentStage VARCHAR(32) NOT NULL DEFAULT 'pmsgy',
      Status TINYINT NOT NULL DEFAULT 1,
      CreatedDate DATE DEFAULT NULL,
      CreatedDateTime DATETIME DEFAULT NULL,
      CreatedBy INT NOT NULL DEFAULT 0,
      UpdatedDateTime DATETIME DEFAULT NULL,
      UpdatedBy INT NOT NULL DEFAULT 0,
      CustUserId INT NOT NULL DEFAULT 0,
      PRIMARY KEY (id),
      KEY idx_beneficiary (BeneficiaryId),
      KEY idx_district (District),
      KEY idx_stage (CurrentStage),
      KEY idx_payment (PaymentDone),
      KEY idx_survey (SurveyDone),
      KEY idx_cust_user (CustUserId)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $custUserCol = $conn->query("SHOW COLUMNS FROM tbl_rooftop_msedcl_smart_customers LIKE 'CustUserId'");
    if ($custUserCol && $custUserCol->num_rows === 0) {
        $conn->query("ALTER TABLE tbl_rooftop_msedcl_smart_customers ADD COLUMN CustUserId INT NOT NULL DEFAULT 0 AFTER UpdatedBy, ADD KEY idx_cust_user (CustUserId)");
    }

    $conn->query("CREATE TABLE IF NOT EXISTS tbl_rooftop_msedcl_smart_history (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      CustomerId INT NOT NULL DEFAULT 0,
      BeneficiaryId VARCHAR(50) DEFAULT NULL,
      ActionType VARCHAR(50) NOT NULL,
      OldStage VARCHAR(32) DEFAULT NULL,
      NewStage VARCHAR(32) DEFAULT NULL,
      PerformedBy INT NOT NULL DEFAULT 0,
      PerformedByName VARCHAR(255) DEFAULT NULL,
      SourceFile VARCHAR(255) DEFAULT NULL,
      Remarks VARCHAR(500) DEFAULT NULL,
      CreatedDateTime DATETIME NOT NULL,
      PRIMARY KEY (id),
      KEY idx_customer (CustomerId),
      KEY idx_action (ActionType)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function msedclSmartNormalizeBeneficiaryId($value)
{
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }
    if (is_numeric($value)) {
        return (string) (int) $value;
    }
    return $value;
}

function msedclSmartNormalizeRow(array $row)
{
    $out = [];
    foreach ($row as $k => $v) {
        $out[$k] = is_string($v) ? trim($v) : $v;
    }
    return $out;
}

function msedclSmartIsHeaderRow(array $row)
{
    $joined = strtolower(implode(' ', array_map('strval', $row)));
    if (strpos($joined, 'beneficiary') !== false && (strpos($joined, 'district') !== false || strpos($joined, 'name') !== false || strpos($joined, 'payment') !== false)) {
        return true;
    }
    if (preg_match('/^(sr\.?\s*no|s\.?\s*no|district|taluka|village)$/i', trim((string) ($row[0] ?? '')))) {
        return true;
    }
    return false;
}

function msedclSmartDetectColumnMap(array $allRows)
{
    $map = [];
    $aliases = [
        'beneficiary_id' => ['beneficiary id', 'beneficiaryid', 'beneficiary', 'ben id', 'ben. id'],
        'cust_name' => ['customer name', 'name', 'farmer name', 'applicant name', 'cust name'],
        'cell_no' => ['mobile', 'phone', 'cell no', 'cellno', 'contact', 'mobile no'],
        'district' => ['district'],
        'taluka' => ['taluka', 'tehsil'],
        'village' => ['village'],
        'block' => ['block'],
        'address' => ['address'],
        'pump_capacity' => ['rooftop capacity id', 'rooftop capacity', 'pump capacity id', 'pump capacity', 'capacity'],
        'wo_no' => ['consumer number', 'consumer no', 'consumer no.', 'wo no', 'wo number', 'work order', 'won'],
        'payment_done' => ['payment yes/no', 'payment yes no', 'payment done', 'payment', 'paid', 'payment status'],
    ];

    foreach ($allRows as $row) {
        if (!msedclSmartIsHeaderRow($row)) {
            continue;
        }
        foreach ($row as $idx => $cell) {
            $label = strtolower(trim(preg_replace('/\s+/', ' ', (string) $cell)));
            if ($label === '') {
                continue;
            }
            foreach ($aliases as $key => $names) {
                if (isset($map[$key])) {
                    continue;
                }
                foreach ($names as $name) {
                    if ($label === $name || strpos($label, $name) !== false) {
                        $map[$key] = (int) $idx;
                        break;
                    }
                }
            }
        }
        break;
    }

    if (!isset($map['beneficiary_id'])) {
        $map = [
            'beneficiary_id' => 0,
            'cust_name' => 1,
            'taluka' => 2,
            'village' => 3,
            'block' => 4,
            'district' => 5,
            'cell_no' => 7,
            'pump_capacity' => 8,
            'wo_no' => 9,
        ];
    }
    return $map;
}

function msedclSmartFieldValue(array $row, array $map, $key)
{
    if (!isset($map[$key]) || !isset($row[$map[$key]])) {
        return '';
    }
    return trim((string) $row[$map[$key]]);
}

/**
 * Parse Yes/No style cell for payment import. Returns true, false, or null if blank/unknown.
 */
function msedclSmartParseYesNo($value)
{
    $value = strtolower(trim((string) $value));
    if ($value === '') {
        return null;
    }
    if (in_array($value, ['yes', 'y', '1', 'true', 'paid', 'done'], true)) {
        return true;
    }
    if (in_array($value, ['no', 'n', '0', 'false', 'pending', 'not', 'no payment'], true)) {
        return false;
    }

    return null;
}

function msedclSmartEnsureMahadiscomColumnMap(array $columnMap, array $allRows)
{
    if (isset($columnMap['payment_done'])) {
        return $columnMap;
    }

    foreach ($allRows as $row) {
        if (!msedclSmartIsHeaderRow($row)) {
            continue;
        }
        foreach ($row as $idx => $cell) {
            if (isset($columnMap['beneficiary_id']) && (int) $idx === (int) $columnMap['beneficiary_id']) {
                continue;
            }
            $label = strtolower(trim(preg_replace('/\s+/', ' ', (string) $cell)));
            if ($label !== '' && (strpos($label, 'payment') !== false || strpos($label, 'paid') !== false)) {
                $columnMap['payment_done'] = (int) $idx;
                return $columnMap;
            }
        }
        if (isset($columnMap['beneficiary_id']) && count(array_filter($row, function ($cell) {
            return trim((string) $cell) !== '';
        })) >= 2) {
            $columnMap['payment_done'] = ((int) $columnMap['beneficiary_id'] === 0) ? 1 : 0;
        }
        break;
    }

    return $columnMap;
}

function msedclSmartRooftopCapacityMasterRowById($masterId)
{
    $masterId = (int) $masterId;
    if ($masterId < 1) {
        return null;
    }

    $row = getRecord("SELECT id, Name FROM tbl_rooftop_common_master WHERE id='$masterId' AND Roll='2' AND Status='1' LIMIT 1");
    if (is_array($row) && !empty($row['id'])) {
        return $row;
    }

    $row = getRecord("SELECT id, Name FROM tbl_common_master WHERE id='$masterId' AND Roll='2' AND Status='1' LIMIT 1");
    if (is_array($row) && !empty($row['id'])) {
        return $row;
    }

    return null;
}

function msedclSmartRooftopCapacityMasterRowByName($name)
{
    global $conn;
    $name = trim((string) $name);
    if ($name === '') {
        return null;
    }

    $esc = mysqli_real_escape_string($conn, $name);
    $row = getRecord("SELECT id, Name FROM tbl_rooftop_common_master WHERE Name='$esc' AND Roll='2' AND Status='1' LIMIT 1");
    if (is_array($row) && !empty($row['id'])) {
        return $row;
    }

    return getRecord("SELECT id, Name FROM tbl_common_master WHERE Name='$esc' AND Roll='2' AND Status='1' LIMIT 1");
}

function msedclSmartRooftopCapacityMasterName($masterId)
{
    $row = msedclSmartRooftopCapacityMasterRowById($masterId);
    return (is_array($row) && !empty($row['Name'])) ? trim((string) $row['Name']) : '';
}

/**
 * Rooftop Capacity column = master id from Rooftop Plant Capacity (tbl_rooftop_common_master, Roll=2).
 *
 * @return array{ok: bool, id: string, message?: string}
 */
function msedclSmartResolveRooftopCapacityMasterId($value)
{
    $value = trim((string) $value);
    if ($value === '') {
        return ['ok' => true, 'id' => ''];
    }

    if (is_numeric($value)) {
        $row = msedclSmartRooftopCapacityMasterRowById((int) $value);
        if (is_array($row) && !empty($row['id'])) {
            return ['ok' => true, 'id' => (string) $row['id']];
        }
        return ['ok' => false, 'id' => '', 'message' => 'Invalid Rooftop Capacity master ID: ' . $value];
    }

    $row = msedclSmartRooftopCapacityMasterRowByName($value);
    if (is_array($row) && !empty($row['id'])) {
        return ['ok' => true, 'id' => (string) $row['id']];
    }

    return ['ok' => false, 'id' => '', 'message' => 'Rooftop Capacity not found in master: ' . $value];
}

function msedclSmartFindCustomerByBeneficiaryId($beneficiaryId)
{
    msedclSmartEnsureTables();
    $bid = msedclSmartNormalizeBeneficiaryId($beneficiaryId);
    if ($bid === '') {
        return null;
    }
    $esc = mysqli_real_escape_string($GLOBALS['conn'], $bid);
    return getRecord("SELECT * FROM tbl_rooftop_msedcl_smart_customers WHERE BeneficiaryId='$esc' AND Status=1 LIMIT 1");
}

function msedclSmartLogHistory($customerId, $beneficiaryId, $actionType, $oldStage, $newStage, $performedBy, $performedByName, $sourceFile = '', $remarks = '')
{
    msedclSmartEnsureTables();
    global $conn;
    $customerId = (int) $customerId;
    $performedBy = (int) $performedBy;
    $escBid = mysqli_real_escape_string($conn, (string) $beneficiaryId);
    $escAction = mysqli_real_escape_string($conn, (string) $actionType);
    $escOld = mysqli_real_escape_string($conn, (string) $oldStage);
    $escNew = mysqli_real_escape_string($conn, (string) $newStage);
    $escName = mysqli_real_escape_string($conn, (string) $performedByName);
    $escFile = mysqli_real_escape_string($conn, (string) $sourceFile);
    $escRemarks = mysqli_real_escape_string($conn, (string) $remarks);
    $now = date('Y-m-d H:i:s');
    $sql = "INSERT INTO tbl_rooftop_msedcl_smart_history SET
        CustomerId='$customerId',
        BeneficiaryId='$escBid',
        ActionType='$escAction',
        OldStage='$escOld',
        NewStage='$escNew',
        PerformedBy='$performedBy',
        PerformedByName='$escName',
        SourceFile='$escFile',
        Remarks='$escRemarks',
        CreatedDateTime='$now'";
    return (bool) $conn->query($sql);
}

function msedclSmartPerformerName($userId)
{
    $userId = (int) $userId;
    if ($userId < 1) {
        return '';
    }
    $row = getRecord("SELECT Fname, Lname FROM tbl_users WHERE id='$userId' LIMIT 1");
    if (!is_array($row)) {
        return '';
    }
    return trim((string) ($row['Fname'] ?? '') . ' ' . (string) ($row['Lname'] ?? ''));
}

function msedclSmartInsertFromRow(array $row, array $map, $userId, $sourceFile = '')
{
    msedclSmartEnsureTables();
    global $conn;

    $beneficiaryId = msedclSmartNormalizeBeneficiaryId(msedclSmartFieldValue($row, $map, 'beneficiary_id'));
    $custName = msedclSmartFieldValue($row, $map, 'cust_name');
    $cellNo = msedclSmartFieldValue($row, $map, 'cell_no');

    if ($beneficiaryId === '' && $custName === '' && $cellNo === '') {
        return ['ok' => false, 'reason' => 'empty_row'];
    }
    if ($beneficiaryId === '') {
        return ['ok' => false, 'reason' => 'missing_beneficiary', 'label' => $custName !== '' ? $custName : $cellNo];
    }

    $existing = msedclSmartFindCustomerByBeneficiaryId($beneficiaryId);
    if (is_array($existing) && !empty($existing['id'])) {
        return ['ok' => false, 'reason' => 'duplicate', 'label' => $beneficiaryId];
    }

    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');
    $escBid = mysqli_real_escape_string($conn, $beneficiaryId);
    $escName = mysqli_real_escape_string($conn, $custName);
    $escCell = mysqli_real_escape_string($conn, $cellNo);
    $escDistrict = mysqli_real_escape_string($conn, msedclSmartFieldValue($row, $map, 'district'));
    $escTaluka = mysqli_real_escape_string($conn, msedclSmartFieldValue($row, $map, 'taluka'));
    $escVillage = mysqli_real_escape_string($conn, msedclSmartFieldValue($row, $map, 'village'));
    $escBlock = mysqli_real_escape_string($conn, msedclSmartFieldValue($row, $map, 'block'));
    $escAddress = mysqli_real_escape_string($conn, msedclSmartFieldValue($row, $map, 'address'));
    $capRaw = msedclSmartFieldValue($row, $map, 'pump_capacity');
    $capRes = msedclSmartResolveRooftopCapacityMasterId($capRaw);
    if (empty($capRes['ok'])) {
        return [
            'ok' => false,
            'reason' => 'invalid_capacity',
            'label' => $beneficiaryId,
            'message' => isset($capRes['message']) ? $capRes['message'] : 'Invalid Rooftop Capacity.',
        ];
    }
    $escPump = mysqli_real_escape_string($conn, (string) $capRes['id']);
    $escWo = mysqli_real_escape_string($conn, msedclSmartFieldValue($row, $map, 'wo_no'));
    $userId = (int) $userId;

    $sql = "INSERT INTO tbl_rooftop_msedcl_smart_customers SET
        BeneficiaryId='$escBid',
        CustName='$escName',
        CellNo='$escCell',
        District='$escDistrict',
        Taluka='$escTaluka',
        Village='$escVillage',
        Block='$escBlock',
        Address='$escAddress',
        PumpCapacity='$escPump',
        WoNo='$escWo',
        PmsgyApplied=1,
        PmsgyAppliedDate='$today',
        CurrentStage='" . MSEDCL_SMART_STAGE_PMSGY . "',
        Status=1,
        CreatedDate='$today',
        CreatedDateTime='$now',
        CreatedBy='$userId',
        UpdatedDateTime='$now',
        UpdatedBy='$userId'";

    if (!$conn->query($sql)) {
        return ['ok' => false, 'reason' => 'db_error', 'label' => $beneficiaryId];
    }

    $newId = (int) mysqli_insert_id($conn);
    msedclSmartLogHistory($newId, $beneficiaryId, 'pmsgy_import', '', MSEDCL_SMART_STAGE_PMSGY, $userId, msedclSmartPerformerName($userId), $sourceFile, 'PMSGY application uploaded');

    $smartRow = [
        'BeneficiaryId' => $beneficiaryId,
        'CustName' => $custName,
        'CellNo' => $cellNo,
        'District' => msedclSmartFieldValue($row, $map, 'district'),
        'Taluka' => msedclSmartFieldValue($row, $map, 'taluka'),
        'Village' => msedclSmartFieldValue($row, $map, 'village'),
        'Block' => msedclSmartFieldValue($row, $map, 'block'),
        'Address' => msedclSmartFieldValue($row, $map, 'address'),
        'PumpCapacity' => (string) $capRes['id'],
        'WoNo' => msedclSmartFieldValue($row, $map, 'wo_no'),
    ];
    $rooftopUserId = msedclSmartResolveRooftopUserId($smartRow, $userId);
    if ($rooftopUserId < 1) {
        $conn->query("DELETE FROM tbl_rooftop_msedcl_smart_customers WHERE id='$newId' LIMIT 1");

        return [
            'ok' => false,
            'reason' => 'rooftop_user_failed',
            'label' => $beneficiaryId,
            'message' => 'Could not add rooftop customer (tbl_users).',
        ];
    }

    return ['ok' => true, 'id' => $newId, 'label' => $beneficiaryId, 'user_id' => $rooftopUserId];
}

function msedclSmartMarkMahadiscom($customerId, $userId, $sourceFile = '', $remarks = '', $allowAlready = false)
{
    msedclSmartEnsureTables();
    global $conn;
    $customerId = (int) $customerId;
    $row = getRecord("SELECT * FROM tbl_rooftop_msedcl_smart_customers WHERE id='$customerId' AND Status=1 LIMIT 1");
    if (!is_array($row)) {
        return ['ok' => false, 'message' => 'Customer not found.'];
    }
    if ((int) $row['MahadiscomApplied'] === 1) {
        if ($allowAlready) {
            return ['ok' => true, 'already' => true];
        }
        return ['ok' => false, 'message' => 'Already marked on Mahadiscom portal.'];
    }
    if ((int) $row['PmsgyApplied'] !== 1) {
        return ['ok' => false, 'message' => 'Customer must be on PMSGY portal first.'];
    }

    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');
    $oldStage = (string) $row['CurrentStage'];
    $userId = (int) $userId;
    $sql = "UPDATE tbl_rooftop_msedcl_smart_customers SET
        MahadiscomApplied=1,
        MahadiscomAppliedDate='$today',
        CurrentStage='" . MSEDCL_SMART_STAGE_MAHADISCOM . "',
        UpdatedDateTime='$now',
        UpdatedBy='$userId'
        WHERE id='$customerId' LIMIT 1";
    if (!$conn->query($sql)) {
        return ['ok' => false, 'message' => 'Update failed.'];
    }

    msedclSmartLogHistory($customerId, $row['BeneficiaryId'], 'mahadiscom_mark', $oldStage, MSEDCL_SMART_STAGE_MAHADISCOM, $userId, msedclSmartPerformerName($userId), $sourceFile, $remarks !== '' ? $remarks : 'Mahadiscom application marked');

    return ['ok' => true];
}

function msedclSmartDefaultStateId()
{
    static $stateId = null;
    if ($stateId !== null) {
        return $stateId;
    }
    $row = getRecord("SELECT id FROM tbl_state WHERE Name LIKE '%Maharashtra%' ORDER BY id ASC LIMIT 1");
    $stateId = (is_array($row) && !empty($row['id'])) ? (int) $row['id'] : 1;

    return $stateId;
}

function msedclSmartUpdateUserFromSmartRow($userId, array $row)
{
    global $conn;
    $userId = (int) $userId;
    if ($userId < 1) {
        return false;
    }

    $escName = mysqli_real_escape_string($conn, trim((string) ($row['CustName'] ?? '')));
    $escPhone = mysqli_real_escape_string($conn, trim((string) ($row['CellNo'] ?? '')));
    $escDistrict = mysqli_real_escape_string($conn, trim((string) ($row['District'] ?? '')));
    $escTaluka = mysqli_real_escape_string($conn, trim((string) ($row['Taluka'] ?? '')));
    $escVillage = mysqli_real_escape_string($conn, trim((string) ($row['Village'] ?? '')));
    $escBlock = mysqli_real_escape_string($conn, trim((string) ($row['Block'] ?? '')));
    $escAddress = mysqli_real_escape_string($conn, trim((string) ($row['Address'] ?? '')));
    $escBeneficiary = mysqli_real_escape_string($conn, msedclSmartNormalizeBeneficiaryId($row['BeneficiaryId'] ?? ''));
    $escPump = mysqli_real_escape_string($conn, trim((string) ($row['PumpCapacity'] ?? '')));
    $escWo = mysqli_real_escape_string($conn, trim((string) ($row['WoNo'] ?? '')));
    $stateId = (int) msedclSmartDefaultStateId();

    $sql = "UPDATE tbl_users SET
        BeneficiaryId='$escBeneficiary',
        Fname='$escName',
        Phone='$escPhone',
        District='$escDistrict',
        Taluka='$escTaluka',
        Village='$escVillage',
        Block='$escBlock',
        Address='$escAddress',
        PumpCapacity='$escPump',
        WoNo='$escWo',
        StateId='$stateId',
        ProjectType=2,
        Roll=5,
        Status=1
        WHERE id='$userId' LIMIT 1";

    return (bool) $conn->query($sql);
}

function msedclSmartRooftopUserInsertDefaults()
{
    static $defaults = null;
    if ($defaults !== null) {
        return $defaults;
    }

    global $conn;
    $defaults = [];
    $dbName = mysqli_real_escape_string($conn, DB_NAME);
    $sql = "SELECT COLUMN_NAME, DATA_TYPE FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='tbl_users'
        AND COLUMN_NAME <> 'id' AND EXTRA NOT LIKE '%auto_increment%'
        ORDER BY ORDINAL_POSITION";
    $res = $conn->query($sql);
    if ($res) {
        while ($col = $res->fetch_assoc()) {
            $name = $col['COLUMN_NAME'];
            $type = strtolower((string) $col['DATA_TYPE']);
            if (in_array($type, ['int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'decimal', 'float', 'double'], true)) {
                $defaults[$name] = 0;
            } elseif (in_array($type, ['date', 'datetime', 'timestamp'], true)) {
                $defaults[$name] = ($type === 'date') ? date('Y-m-d') : date('Y-m-d H:i:s');
            } else {
                $defaults[$name] = '';
            }
        }
    }

    return $defaults;
}

function msedclSmartCreateUserFromSmartRow(array $row, $performedBy = 0)
{
    global $conn;

    $fields = msedclSmartRooftopUserInsertDefaults();
    $tempCustomerId = 'VTECH-TMP-' . time() . '-' . mt_rand(100, 999);
    $createdDate = date('Y-m-d');
    $performedBy = (int) $performedBy;
    $stateId = (int) msedclSmartDefaultStateId();

    $fields['CustomerId'] = $tempCustomerId;
    $fields['BeneficiaryId'] = msedclSmartNormalizeBeneficiaryId($row['BeneficiaryId'] ?? '');
    $fields['Fname'] = trim((string) ($row['CustName'] ?? ''));
    $fields['Phone'] = trim((string) ($row['CellNo'] ?? ''));
    $fields['District'] = trim((string) ($row['District'] ?? ''));
    $fields['Taluka'] = trim((string) ($row['Taluka'] ?? ''));
    $fields['Village'] = trim((string) ($row['Village'] ?? ''));
    $fields['Block'] = trim((string) ($row['Block'] ?? ''));
    $fields['Address'] = trim((string) ($row['Address'] ?? ''));
    $fields['PumpCapacity'] = trim((string) ($row['PumpCapacity'] ?? ''));
    $fields['WoNo'] = trim((string) ($row['WoNo'] ?? ''));
    $fields['StateId'] = $stateId;
    $fields['CountryId'] = 1;
    $fields['ProjectType'] = 2;
    $fields['Roll'] = 5;
    $fields['Status'] = 1;
    $fields['CoordinatorStatus'] = 0;
    $fields['CoordinatorId'] = 0;
    $fields['LeadCust'] = 0;
    $fields['Password'] = '12345';
    $fields['CreatedDate'] = $createdDate;
    $fields['CreatedBy'] = $performedBy;
    $fields['ModifiedBy'] = 0;
    $fields['ModifiedDate'] = $createdDate;

    $sets = [];
    foreach ($fields as $column => $value) {
        if ($column === 'id') {
            continue;
        }
        if (is_int($value) || is_float($value)) {
            $sets[] = "`$column`=" . (int) $value;
        } else {
            $sets[] = "`$column`='" . mysqli_real_escape_string($conn, (string) $value) . "'";
        }
    }

    $sql = 'INSERT INTO tbl_users SET ' . implode(', ', $sets);
    if (!$conn->query($sql)) {
        return 0;
    }

    $newId = (int) mysqli_insert_id($conn);
    if ($newId > 0) {
        $customerCode = 'VTECH-C' . $newId;
        $escCode = mysqli_real_escape_string($conn, $customerCode);
        $conn->query("UPDATE tbl_users SET CustomerId='$escCode' WHERE id='$newId' LIMIT 1");
    }

    return $newId;
}

function msedclSmartResolveRooftopUserId(array $row, $performedBy = 0)
{
    global $conn;

    $userId = 0;
    $beneficiaryId = msedclSmartNormalizeBeneficiaryId($row['BeneficiaryId'] ?? '');
    if ($beneficiaryId !== '') {
        $escBid = mysqli_real_escape_string($conn, $beneficiaryId);
        $match = getRecord("SELECT id FROM tbl_users WHERE Roll=5 AND ProjectType=2 AND Status=1 AND BeneficiaryId='$escBid' LIMIT 1");
        if (is_array($match)) {
            $userId = (int) $match['id'];
        }
    }

    if ($userId < 1 && trim((string) ($row['CellNo'] ?? '')) !== '') {
        $escPhone = mysqli_real_escape_string($conn, trim((string) $row['CellNo']));
        $match = getRecord("SELECT id FROM tbl_users WHERE Roll=5 AND ProjectType=2 AND Status=1 AND Phone='$escPhone' LIMIT 1");
        if (is_array($match)) {
            $userId = (int) $match['id'];
        }
    }

    if ($userId < 1) {
        $userId = msedclSmartCreateUserFromSmartRow($row, $performedBy);
    } else {
        msedclSmartUpdateUserFromSmartRow($userId, $row);
    }

    return $userId;
}

function msedclSmartEnsureRooftopUserFromSmartCustomer($smartCustomerId, $performedBy = 0)
{
    msedclSmartEnsureTables();

    $smartCustomerId = (int) $smartCustomerId;
    $performedBy = (int) $performedBy;
    $row = getRecord("SELECT * FROM tbl_rooftop_msedcl_smart_customers WHERE id='$smartCustomerId' AND Status=1 LIMIT 1");
    if (!is_array($row)) {
        return ['ok' => false, 'message' => 'Smart customer not found.'];
    }

    $userId = msedclSmartResolveRooftopUserId($row, $performedBy);
    if ($userId < 1) {
        return ['ok' => false, 'message' => 'Could not create rooftop customer record.'];
    }

    return ['ok' => true, 'user_id' => $userId];
}

function msedclSmartSyncSurveyPendingToCoordinatorQueue($smartCustomerId, $performedBy = 0)
{
    msedclSmartEnsureTables();
    global $conn;

    $smartCustomerId = (int) $smartCustomerId;
    $performedBy = (int) $performedBy;
    $row = getRecord("SELECT * FROM tbl_rooftop_msedcl_smart_customers WHERE id='$smartCustomerId' AND Status=1 LIMIT 1");
    if (!is_array($row)) {
        return ['ok' => false, 'message' => 'Smart customer not found.'];
    }
    if ((int) $row['PaymentDone'] !== 1 || (int) $row['SurveyDone'] === 1) {
        return ['ok' => false, 'message' => 'Customer is not survey pending.'];
    }

    $userId = (int) ($row['CustUserId'] ?? 0);
    if ($userId > 0) {
        $linked = getRecord("SELECT id FROM tbl_users WHERE id='$userId' AND Roll=5 AND ProjectType=2 AND Status=1 LIMIT 1");
        if (is_array($linked)) {
            msedclSmartUpdateUserFromSmartRow($userId, $row);
            return ['ok' => true, 'user_id' => $userId, 'linked' => true];
        }
    }

    $userId = msedclSmartResolveRooftopUserId($row, $performedBy);
    if ($userId < 1) {
        return ['ok' => false, 'message' => 'Could not create rooftop customer record.'];
    }

    $now = date('Y-m-d H:i:s');
    $conn->query("UPDATE tbl_rooftop_msedcl_smart_customers SET CustUserId='$userId', UpdatedDateTime='$now', UpdatedBy='$performedBy' WHERE id='$smartCustomerId' LIMIT 1");

    return ['ok' => true, 'user_id' => $userId];
}

function msedclSmartSyncAllSurveyPendingToCoordinatorQueue($performedBy = 0)
{
    msedclSmartEnsureTables();
    $rows = getList("SELECT id FROM tbl_rooftop_msedcl_smart_customers WHERE Status=1 AND PaymentDone=1 AND SurveyDone=0 AND CurrentStage='" . MSEDCL_SMART_STAGE_SURVEY_PENDING . "'");
    if (!is_array($rows)) {
        return 0;
    }

    $synced = 0;
    foreach ($rows as $item) {
        $res = msedclSmartSyncSurveyPendingToCoordinatorQueue((int) $item['id'], $performedBy);
        if (!empty($res['ok'])) {
            $synced++;
        }
    }

    return $synced;
}

function msedclSmartIsForwardedToCoordinator(array $row)
{
    $custUserId = (int) ($row['CustUserId'] ?? 0);
    if ($custUserId < 1) {
        return false;
    }
    $user = getRecord("SELECT id FROM tbl_users WHERE id='$custUserId' AND Roll=5 AND ProjectType=2 AND Status=1 LIMIT 1");

    return is_array($user);
}

function msedclSmartCanDeleteCustomer(array $row)
{
    if ((int) ($row['Status'] ?? 1) !== 1) {
        return false;
    }
    if ((int) ($row['CustUserId'] ?? 0) > 0) {
        return false;
    }
    if (msedclSmartIsForwardedToCoordinator($row)) {
        return false;
    }

    return true;
}

function msedclSmartDeleteCustomer($customerId, $userId, $remarks = '')
{
    msedclSmartEnsureTables();
    global $conn;

    $customerId = (int) $customerId;
    $userId = (int) $userId;
    $row = getRecord("SELECT * FROM tbl_rooftop_msedcl_smart_customers WHERE id='$customerId' AND Status=1 LIMIT 1");
    if (!is_array($row)) {
        return ['ok' => false, 'message' => 'Customer not found.'];
    }
    if (!msedclSmartCanDeleteCustomer($row)) {
        return ['ok' => false, 'message' => 'Cannot delete. Customer is already forwarded to Co-ordinator assign.'];
    }

    $now = date('Y-m-d H:i:s');
    $label = trim((string) ($row['BeneficiaryId'] ?? ''));
    if (!$conn->query("UPDATE tbl_rooftop_msedcl_smart_customers SET Status=0, UpdatedDateTime='$now', UpdatedBy='$userId' WHERE id='$customerId' LIMIT 1")) {
        return ['ok' => false, 'message' => 'Delete failed.'];
    }

    msedclSmartLogHistory(
        $customerId,
        $row['BeneficiaryId'],
        'customer_delete',
        (string) $row['CurrentStage'],
        'deleted',
        $userId,
        msedclSmartPerformerName($userId),
        '',
        $remarks !== '' ? $remarks : 'Customer deleted from MSEDCL SMART list'
    );

    return [
        'ok' => true,
        'message' => ($label !== '' ? $label : 'Customer') . ' deleted successfully.',
    ];
}

function msedclSmartOptionForListType($listType)
{
    $map = [
        'pmsgy' => MSEDCL_SMART_OPT_PMSGY,
        'mahadiscom' => MSEDCL_SMART_OPT_MAHADISCOM,
        'payment' => MSEDCL_SMART_OPT_PAYMENT,
        'survey_pending' => MSEDCL_SMART_OPT_SURVEY_PENDING,
    ];

    return isset($map[$listType]) ? $map[$listType] : 0;
}

function msedclSmartCoordinatorAssignUrl()
{
    global $SiteUrl;

    return rtrim((string) $SiteUrl, '/') . '/assign-customers-to-co-ordinator.php?CoordinatorStatus=0';
}

function msedclSmartCanForwardToCoordinator(array $row)
{
    if ((int) ($row['PaymentDone'] ?? 0) !== 1 || (int) ($row['SurveyDone'] ?? 0) === 1) {
        return false;
    }
    if ((string) ($row['CurrentStage'] ?? '') !== MSEDCL_SMART_STAGE_SURVEY_PENDING) {
        return false;
    }
    if (msedclSmartIsForwardedToCoordinator($row)) {
        return false;
    }

    $custUserId = (int) ($row['CustUserId'] ?? 0);
    if ($custUserId > 0) {
        $user = getRecord("SELECT CoordinatorStatus FROM tbl_users WHERE id='$custUserId' AND Roll=5 AND ProjectType=2 AND Status=1 LIMIT 1");
        if (is_array($user) && (int) $user['CoordinatorStatus'] === 1) {
            return false;
        }
    }

    return true;
}

function msedclSmartForwardStatusLabel(array $row)
{
    if (msedclSmartIsForwardedToCoordinator($row)) {
        return 'Forwarded to Co-ordinator assign';
    }

    if (!msedclSmartCanForwardToCoordinator($row)) {
        $custUserId = (int) ($row['CustUserId'] ?? 0);
        if ($custUserId > 0) {
            $user = getRecord("SELECT CoordinatorStatus FROM tbl_users WHERE id='$custUserId' AND Status=1 LIMIT 1");
            if (is_array($user) && (int) $user['CoordinatorStatus'] === 1) {
                return 'Assigned to Co-ordinator';
            }
        }

        return '';
    }

    return 'Pending forward';
}

function msedclSmartForwardCustomersToCoordinator(array $customerIds, $performedBy = 0)
{
    msedclSmartEnsureTables();

    $synced = 0;
    $skipped = 0;
    $errors = [];
    $performedBy = (int) $performedBy;

    foreach ($customerIds as $customerId) {
        $customerId = (int) $customerId;
        if ($customerId < 1) {
            continue;
        }

        $row = getRecord("SELECT * FROM tbl_rooftop_msedcl_smart_customers WHERE id='$customerId' AND Status=1 LIMIT 1");
        if (!is_array($row)) {
            $skipped++;
            $errors[] = "ID $customerId: not found.";
            continue;
        }

        if (!msedclSmartCanForwardToCoordinator($row)) {
            $skipped++;
            $label = trim((string) ($row['BeneficiaryId'] ?? ''));
            $errors[] = ($label !== '' ? $label : "ID $customerId") . ': cannot forward (already assigned or not survey pending).';
            continue;
        }

        $res = msedclSmartSyncSurveyPendingToCoordinatorQueue($customerId, $performedBy);
        if (!empty($res['ok'])) {
            $synced++;
        } else {
            $skipped++;
            $label = trim((string) ($row['BeneficiaryId'] ?? ''));
            $errors[] = ($label !== '' ? $label : "ID $customerId") . ': ' . ($res['message'] ?? 'forward failed.');
        }
    }

    return [
        'success' => $synced > 0,
        'message' => $synced > 0
            ? "$synced customer(s) forwarded to Co-ordinator assign list." . ($skipped > 0 ? " Skipped: $skipped." : '')
            : ($skipped > 0 ? 'Selected customer(s) could not be forwarded.' : 'No customers were forwarded.'),
        'synced' => $synced,
        'skipped' => $skipped,
        'errors' => array_slice($errors, 0, 20),
        'redirect' => msedclSmartCoordinatorAssignUrl(),
    ];
}

function msedclSmartMarkPaymentDone($customerId, $userId, $sourceFile = '', $remarks = '', $allowAlready = false)
{
    msedclSmartEnsureTables();
    global $conn;
    $customerId = (int) $customerId;
    $row = getRecord("SELECT * FROM tbl_rooftop_msedcl_smart_customers WHERE id='$customerId' AND Status=1 LIMIT 1");
    if (!is_array($row)) {
        return ['ok' => false, 'message' => 'Customer not found.'];
    }
    if ((int) $row['PaymentDone'] === 1) {
        if ($allowAlready) {
            return ['ok' => true, 'already' => true];
        }
        return ['ok' => false, 'message' => 'Payment already marked done.'];
    }
    if ((int) $row['MahadiscomApplied'] !== 1) {
        return ['ok' => false, 'message' => 'Mahadiscom application must be done first.'];
    }

    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');
    $oldStage = (string) $row['CurrentStage'];
    $userId = (int) $userId;
    $sql = "UPDATE tbl_rooftop_msedcl_smart_customers SET
        PaymentDone=1,
        PaymentDoneDate='$today',
        CurrentStage='" . MSEDCL_SMART_STAGE_SURVEY_PENDING . "',
        UpdatedDateTime='$now',
        UpdatedBy='$userId'
        WHERE id='$customerId' LIMIT 1";
    if (!$conn->query($sql)) {
        return ['ok' => false, 'message' => 'Update failed.'];
    }

    msedclSmartLogHistory($customerId, $row['BeneficiaryId'], 'payment_done', $oldStage, MSEDCL_SMART_STAGE_SURVEY_PENDING, $userId, msedclSmartPerformerName($userId), $sourceFile, $remarks !== '' ? $remarks : 'Payment done — moved to survey pending');

    return ['ok' => true];
}

function msedclSmartProcessSpreadsheet($targetPath, $originalName, $fileType, $importType, $userId)
{
    require_once __DIR__ . '/vendor/php-excel-reader/excel_reader2.php';
    require_once __DIR__ . '/vendor/SpreadsheetReader.php';

    msedclSmartEnsureTables();
    $imported = 0;
    $skipped = 0;
    $errors = [];
    $allRows = [];

    try {
        $Reader = new SpreadsheetReader($targetPath, $originalName, $fileType);
        $sheetCount = count($Reader->sheets());
        for ($s = 0; $s < $sheetCount; $s++) {
            $Reader->ChangeSheet($s);
            foreach ($Reader as $Row) {
                if (is_array($Row)) {
                    $allRows[] = msedclSmartNormalizeRow($Row);
                }
            }
        }
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Could not read Excel file.', 'imported' => 0, 'skipped' => 0, 'errors' => []];
    }

    if (empty($allRows)) {
        return ['success' => false, 'message' => 'Excel file is empty.', 'imported' => 0, 'skipped' => 0, 'errors' => []];
    }

    $columnMap = msedclSmartDetectColumnMap($allRows);
    if ($importType === 'mahadiscom') {
        $columnMap = msedclSmartEnsureMahadiscomColumnMap($columnMap, $allRows);
    }
    $headerSkipped = false;

    foreach ($allRows as $row) {
        if (!$headerSkipped && msedclSmartIsHeaderRow($row)) {
            $headerSkipped = true;
            continue;
        }

        $beneficiaryId = msedclSmartNormalizeBeneficiaryId(msedclSmartFieldValue($row, $columnMap, 'beneficiary_id'));
        if ($beneficiaryId === '' || preg_match('/^beneficiary\s*id$/i', $beneficiaryId)) {
            continue;
        }

        if ($importType === 'pmsgy') {
            $result = msedclSmartInsertFromRow($row, $columnMap, $userId, $originalName);
            if (!empty($result['ok'])) {
                $imported++;
            } else {
                $skipped++;
                $reason = isset($result['reason']) ? $result['reason'] : 'unknown';
                $label = isset($result['label']) ? $result['label'] : $beneficiaryId;
                if ($reason === 'duplicate') {
                    $errors[] = "$label: already exists.";
                } elseif ($reason === 'invalid_capacity') {
                    $errors[] = "$label: " . (isset($result['message']) ? $result['message'] : 'Invalid Rooftop Capacity master ID.');
                } elseif ($reason === 'rooftop_user_failed') {
                    $errors[] = "$label: " . (isset($result['message']) ? $result['message'] : 'Could not add rooftop customer.');
                } elseif ($reason !== 'empty_row') {
                    $errors[] = "$label: could not import.";
                }
            }
            continue;
        }

        $customer = msedclSmartFindCustomerByBeneficiaryId($beneficiaryId);
        if (!is_array($customer) || empty($customer['id'])) {
            $skipped++;
            $errors[] = "$beneficiaryId: not found in MSEDCL SMART records.";
            continue;
        }

        if ($importType === 'mahadiscom') {
            $paymentRaw = msedclSmartFieldValue($row, $columnMap, 'payment_done');
            $paymentYes = msedclSmartParseYesNo($paymentRaw);

            $resMahadiscom = msedclSmartMarkMahadiscom((int) $customer['id'], $userId, $originalName, 'Mahadiscom via Excel', true);
            if (empty($resMahadiscom['ok'])) {
                $skipped++;
                $errors[] = "$beneficiaryId: " . ($resMahadiscom['message'] ?? 'Mahadiscom update failed.');
                continue;
            }
            $mahadNew = empty($resMahadiscom['already']);

            $paymentNew = false;
            if ($paymentYes === true) {
                $customer = msedclSmartFindCustomerByBeneficiaryId($beneficiaryId);
                $resPayment = msedclSmartMarkPaymentDone((int) $customer['id'], $userId, $originalName, 'Payment via Mahadiscom Excel', true);
                if (empty($resPayment['ok'])) {
                    $skipped++;
                    $errors[] = "$beneficiaryId: " . ($resPayment['message'] ?? 'Payment update failed.');
                    continue;
                }
                $paymentNew = empty($resPayment['already']);
            } elseif ($paymentRaw !== '' && $paymentYes === null) {
                $skipped++;
                $errors[] = "$beneficiaryId: invalid payment value (use Yes or No).";
                continue;
            }

            if ($mahadNew || $paymentNew) {
                $imported++;
            } else {
                $skipped++;
                $errors[] = "$beneficiaryId: already up to date for this row.";
            }
        } elseif ($importType === 'payment') {
            $res = msedclSmartMarkPaymentDone((int) $customer['id'], $userId, $originalName, 'Payment via Excel');
            if (!empty($res['ok'])) {
                $imported++;
            } else {
                $skipped++;
                $errors[] = "$beneficiaryId: " . ($res['message'] ?? 'skipped');
            }
        }
    }

    $typeLabel = ucfirst($importType);
    return [
        'success' => true,
        'message' => "$typeLabel import complete. Imported: $imported, Skipped: $skipped.",
        'imported' => $imported,
        'skipped' => $skipped,
        'errors' => array_slice($errors, 0, 50),
    ];
}

function msedclSmartStageLabel($stage)
{
    $labels = [
        MSEDCL_SMART_STAGE_PMSGY => 'PMSGY Portal',
        MSEDCL_SMART_STAGE_MAHADISCOM => 'Mahadiscom Portal',
        MSEDCL_SMART_STAGE_SURVEY_PENDING => 'Survey Pending',
        MSEDCL_SMART_STAGE_SURVEY_DONE => 'Survey Done',
    ];
    return isset($labels[$stage]) ? $labels[$stage] : ucfirst(str_replace('_', ' ', (string) $stage));
}

function msedclSmartLoadUserSurveyMap(array $rows)
{
    $ids = [];
    foreach ($rows as $row) {
        $custUserId = (int) ($row['CustUserId'] ?? 0);
        if ($custUserId > 0) {
            $ids[$custUserId] = $custUserId;
        }
    }

    if (empty($ids)) {
        return [];
    }

    $idList = implode(',', array_map('intval', array_values($ids)));
    $userRows = getList("SELECT id, SurveyDetails, FieldSurveyDetails FROM tbl_users WHERE id IN ($idList) AND Roll=5 AND ProjectType=2 AND Status=1");
    if (!is_array($userRows)) {
        return [];
    }

    $map = [];
    foreach ($userRows as $userRow) {
        $map[(int) $userRow['id']] = $userRow;
    }

    return $map;
}

function msedclSmartTelephonicSurveyHtml($custUserId, $surveyDone = null)
{
    $custUserId = (int) $custUserId;
    if ($custUserId < 1) {
        return '<span class="badge badge-secondary">Not forwarded</span>';
    }

    if ((int) $surveyDone === 1) {
        return '<span class="badge badge-success">Survey Done</span>';
    }

    return '<span class="badge badge-warning">Survey Not Done</span>';
}

function msedclSmartFieldSurveyHtml($custUserId, $fieldSurveyDone = null)
{
    $custUserId = (int) $custUserId;
    if ($custUserId < 1) {
        return '<span class="badge badge-secondary">Not forwarded</span>';
    }

    if ((int) $fieldSurveyDone === 1) {
        return '<span class="badge badge-success">Survey Done</span>';
    }

    return '<span class="badge badge-warning">Survey Not Done</span>';
}

function msedclSmartBuildListSql($listType, array $filters = [])
{
    msedclSmartEnsureTables();
    $sql = "SELECT * FROM tbl_rooftop_msedcl_smart_customers WHERE Status=1";

    if ($listType === 'pmsgy') {
        $sql .= " AND PmsgyApplied=1 AND CurrentStage='" . MSEDCL_SMART_STAGE_PMSGY . "'";
    } elseif ($listType === 'mahadiscom') {
        $sql .= " AND MahadiscomApplied=1 AND CurrentStage='" . MSEDCL_SMART_STAGE_MAHADISCOM . "'";
    } elseif ($listType === 'payment') {
        $sql .= " AND PaymentDone=1 AND CurrentStage='" . MSEDCL_SMART_STAGE_SURVEY_PENDING . "' AND SurveyDone=0 AND CustUserId=0";
    } elseif ($listType === 'survey_pending') {
        $sql .= " AND CurrentStage='" . MSEDCL_SMART_STAGE_SURVEY_PENDING . "' AND SurveyDone=0";
    }

    if (!empty($filters['District'])) {
        $esc = mysqli_real_escape_string($GLOBALS['conn'], $filters['District']);
        $sql .= " AND District='$esc'";
    }
    if (!empty($filters['Search'])) {
        $esc = mysqli_real_escape_string($GLOBALS['conn'], $filters['Search']);
        $sql .= " AND (BeneficiaryId LIKE '%$esc%' OR CustName LIKE '%$esc%' OR CellNo LIKE '%$esc%')";
    }

    $sql .= " ORDER BY id DESC";
    return $sql;
}

function msedclSmartCount($where = 'Status=1')
{
    msedclSmartEnsureTables();
    return (int) getRow("SELECT id FROM tbl_rooftop_msedcl_smart_customers WHERE $where");
}

function msedclSmartAbstractFiltersFromRequest($request = null)
{
    if ($request === null) {
        $request = $_REQUEST;
    }
    $filterDistrict = isset($request['District']) ? trim((string) $request['District']) : '';
    $filterTaluka = isset($request['Taluka']) ? trim((string) $request['Taluka']) : '';
    $filterFromDate = isset($request['FromDate']) ? trim((string) $request['FromDate']) : '';
    $filterToDate = isset($request['ToDate']) ? trim((string) $request['ToDate']) : '';
    $filterDateMode = isset($request['DateMode']) ? trim((string) $request['DateMode']) : 'upload';

    $filters = [];
    if ($filterDistrict !== '') {
        $filters['District'] = $filterDistrict;
    }
    if ($filterTaluka !== '') {
        $filters['Taluka'] = $filterTaluka;
    }
    if ($filterFromDate !== '') {
        $filters['FromDate'] = $filterFromDate;
    }
    if ($filterToDate !== '') {
        $filters['ToDate'] = $filterToDate;
    }
    if ($filterDateMode === 'stage') {
        $filters['DateMode'] = 'stage';
    }

    return [
        'filters' => $filters,
        'District' => $filterDistrict,
        'Taluka' => $filterTaluka,
        'FromDate' => $filterFromDate,
        'ToDate' => $filterToDate,
        'DateMode' => $filterDateMode,
    ];
}

function msedclSmartAbstractTotals(array $rows)
{
    $totals = ['pmsgy_cnt' => 0, 'mahadiscom_cnt' => 0, 'payment_cnt' => 0, 'survey_cnt' => 0];
    foreach ($rows as $r) {
        $totals['pmsgy_cnt'] += (int) ($r['pmsgy_cnt'] ?? 0);
        $totals['mahadiscom_cnt'] += (int) ($r['mahadiscom_cnt'] ?? 0);
        $totals['payment_cnt'] += (int) ($r['payment_cnt'] ?? 0);
        $totals['survey_cnt'] += (int) ($r['survey_cnt'] ?? 0);
    }
    return $totals;
}

function msedclSmartAbstractExportQueryString(array $meta)
{
    $params = [];
    if ($meta['District'] !== '') {
        $params['District'] = $meta['District'];
    }
    if ($meta['Taluka'] !== '') {
        $params['Taluka'] = $meta['Taluka'];
    }
    if ($meta['FromDate'] !== '') {
        $params['FromDate'] = $meta['FromDate'];
    }
    if ($meta['ToDate'] !== '') {
        $params['ToDate'] = $meta['ToDate'];
    }
    if ($meta['DateMode'] === 'stage') {
        $params['DateMode'] = 'stage';
    }
    if (!empty($params)) {
        $params['Search'] = '1';
    }
    return http_build_query($params);
}

function msedclSmartAbstractSqlParts(array $filters = [], $rowDistrict = null)
{
    msedclSmartEnsureTables();
    global $conn;

    $where = ['Status=1'];
    $dateMode = isset($filters['DateMode']) ? (string) $filters['DateMode'] : 'upload';

    if (!empty($filters['District'])) {
        $esc = mysqli_real_escape_string($conn, (string) $filters['District']);
        $where[] = "TRIM(District)='$esc'";
    }
    if (!empty($filters['Taluka'])) {
        $esc = mysqli_real_escape_string($conn, (string) $filters['Taluka']);
        $where[] = "TRIM(Taluka)='$esc'";
    }
    if ($rowDistrict !== null && $rowDistrict !== '') {
        if ($rowDistrict === 'Unknown') {
            $where[] = "(District IS NULL OR TRIM(District)='')";
        } else {
            $esc = mysqli_real_escape_string($conn, (string) $rowDistrict);
            $where[] = "TRIM(District)='$esc'";
        }
    }
    if ($dateMode !== 'stage') {
        if (!empty($filters['FromDate'])) {
            $esc = mysqli_real_escape_string($conn, (string) $filters['FromDate']);
            $where[] = "CreatedDate>='$esc'";
        }
        if (!empty($filters['ToDate'])) {
            $esc = mysqli_real_escape_string($conn, (string) $filters['ToDate']);
            $where[] = "CreatedDate<='$esc'";
        }
    }

    $pmsgyDateCol = 'CreatedDate';
    if ($dateMode === 'stage') {
        $pmsgyDateCol = 'PmsgyAppliedDate';
    }

    $pmsgyExtra = '';
    $mahadiscomExtra = '';
    $paymentExtra = '';
    $surveyExtra = '';
    if ($dateMode === 'stage') {
        if (!empty($filters['FromDate'])) {
            $esc = mysqli_real_escape_string($conn, (string) $filters['FromDate']);
            $pmsgyExtra = " AND ($pmsgyDateCol IS NOT NULL AND $pmsgyDateCol != '0000-00-00' AND $pmsgyDateCol >= '$esc')";
            $mahadiscomExtra = " AND (MahadiscomAppliedDate IS NOT NULL AND MahadiscomAppliedDate != '0000-00-00' AND MahadiscomAppliedDate >= '$esc')";
            $paymentExtra = " AND (PaymentDoneDate IS NOT NULL AND PaymentDoneDate != '0000-00-00' AND PaymentDoneDate >= '$esc')";
            $surveyExtra = " AND (SurveyDoneDate IS NOT NULL AND SurveyDoneDate != '0000-00-00' AND SurveyDoneDate >= '$esc')";
        }
        if (!empty($filters['ToDate'])) {
            $esc = mysqli_real_escape_string($conn, (string) $filters['ToDate']);
            $pmsgyExtra .= " AND ($pmsgyDateCol IS NOT NULL AND $pmsgyDateCol != '0000-00-00' AND $pmsgyDateCol <= '$esc')";
            $mahadiscomExtra .= " AND (MahadiscomAppliedDate IS NOT NULL AND MahadiscomAppliedDate != '0000-00-00' AND MahadiscomAppliedDate <= '$esc')";
            $paymentExtra .= " AND (PaymentDoneDate IS NOT NULL AND PaymentDoneDate != '0000-00-00' AND PaymentDoneDate <= '$esc')";
            $surveyExtra .= " AND (SurveyDoneDate IS NOT NULL AND SurveyDoneDate != '0000-00-00' AND SurveyDoneDate <= '$esc')";
        }
    }

    return [
        'whereSql' => implode(' AND ', $where),
        'extras' => [
            'pmsgy' => $pmsgyExtra,
            'mahadiscom' => $mahadiscomExtra,
            'payment' => $paymentExtra,
            'survey' => $surveyExtra,
        ],
    ];
}

function msedclSmartAbstractMetricLabel($metric)
{
    $labels = [
        'pmsgy' => 'Applications on PMSGY',
        'mahadiscom' => 'Applications on MAHADISCOM',
        'payment' => 'Payment Done',
        'survey' => 'Survey Done',
    ];

    return isset($labels[$metric]) ? $labels[$metric] : ucfirst((string) $metric);
}

function msedclSmartAbstractRecords($metric, $rowDistrict, array $filters = [])
{
    $allowed = ['pmsgy', 'mahadiscom', 'payment', 'survey'];
    if (!in_array($metric, $allowed, true)) {
        return [];
    }

    $parts = msedclSmartAbstractSqlParts($filters, $rowDistrict === '' ? null : $rowDistrict);
    $flags = [
        'pmsgy' => 'PmsgyApplied=1',
        'mahadiscom' => 'MahadiscomApplied=1',
        'payment' => 'PaymentDone=1',
        'survey' => 'SurveyDone=1',
    ];
    $extra = isset($parts['extras'][$metric]) ? $parts['extras'][$metric] : '';

    $sql = "SELECT id, BeneficiaryId, CustName, CellNo, District, Taluka, Village, PumpCapacity, CurrentStage,
        PmsgyAppliedDate, MahadiscomAppliedDate, PaymentDoneDate, SurveyDoneDate, CreatedDate
        FROM tbl_rooftop_msedcl_smart_customers
        WHERE {$parts['whereSql']} AND {$flags[$metric]} $extra
        ORDER BY TRIM(District) ASC, CustName ASC, BeneficiaryId ASC";

    $rows = getList($sql);

    return is_array($rows) ? $rows : [];
}

function msedclSmartAbstractCountCell($count, $metric, $rowDistrict, array $meta)
{
    $count = (int) $count;
    if ($count < 1) {
        return '0';
    }

    $payload = [
        'metric' => $metric,
        'RowDistrict' => (string) $rowDistrict,
        'District' => isset($meta['District']) ? (string) $meta['District'] : '',
        'Taluka' => isset($meta['Taluka']) ? (string) $meta['Taluka'] : '',
        'FromDate' => isset($meta['FromDate']) ? (string) $meta['FromDate'] : '',
        'ToDate' => isset($meta['ToDate']) ? (string) $meta['ToDate'] : '',
        'DateMode' => isset($meta['DateMode']) ? (string) $meta['DateMode'] : 'upload',
    ];
    $json = htmlspecialchars(json_encode($payload), ENT_QUOTES, 'UTF-8');

    return '<a href="#" class="msedcl-abstract-count-link" data-filters="' . $json . '">' . number_format($count) . '</a>';
}

function msedclSmartAbstractByDistrict(array $filters = [])
{
    $parts = msedclSmartAbstractSqlParts($filters);
    $pmsgyExtra = $parts['extras']['pmsgy'];
    $mahadiscomExtra = $parts['extras']['mahadiscom'];
    $paymentExtra = $parts['extras']['payment'];
    $surveyExtra = $parts['extras']['survey'];
    $whereSql = $parts['whereSql'];

    $sql = "SELECT
        IFNULL(NULLIF(TRIM(District), ''), 'Unknown') AS District,
        SUM(CASE WHEN PmsgyApplied=1 $pmsgyExtra THEN 1 ELSE 0 END) AS pmsgy_cnt,
        SUM(CASE WHEN MahadiscomApplied=1 $mahadiscomExtra THEN 1 ELSE 0 END) AS mahadiscom_cnt,
        SUM(CASE WHEN PaymentDone=1 $paymentExtra THEN 1 ELSE 0 END) AS payment_cnt,
        SUM(CASE WHEN SurveyDone=1 $surveyExtra THEN 1 ELSE 0 END) AS survey_cnt
        FROM tbl_rooftop_msedcl_smart_customers
        WHERE $whereSql
        GROUP BY IFNULL(NULLIF(TRIM(District), ''), 'Unknown')
        ORDER BY District ASC";
    $rows = getList($sql);
    return is_array($rows) ? $rows : [];
}

function msedclSmartRenderImportButton($ajaxUrl, $redirectUrl)
{
    ob_start();
    ?>
<button type="button" class="btn btn-success btn-sm msedcl-smart-import-btn" data-ajax-url="<?php echo htmlspecialchars($ajaxUrl); ?>" data-redirect="<?php echo htmlspecialchars($redirectUrl); ?>">
    <i class="ion ion-md-cloud-upload mr-1"></i> Import Excel
</button>
<input type="file" class="d-none msedcl-smart-import-file" accept=".xlsx,.xls,.csv">
    <?php
    return ob_get_clean();
}
