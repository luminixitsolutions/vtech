<?php

function insuranceEnsureHistoryTable()
{
    global $conn;

    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;

    $sql = "CREATE TABLE IF NOT EXISTS tbl_insurance_site_history (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      CustId INT NOT NULL,
      BeneficiaryId VARCHAR(50) DEFAULT NULL,
      CustName VARCHAR(255) DEFAULT NULL,
      CellNo VARCHAR(50) DEFAULT NULL,
      ProjectType TINYINT DEFAULT NULL,
      Taluka VARCHAR(100) DEFAULT NULL,
      Village VARCHAR(100) DEFAULT NULL,
      District VARCHAR(100) DEFAULT NULL,
      Address TEXT,
      SiteDispatchDate DATE DEFAULT NULL,
      InsuranceCompany VARCHAR(255) DEFAULT NULL,
      PolicyNo VARCHAR(100) DEFAULT NULL,
      DateOfIssue DATE DEFAULT NULL,
      DateOfExpiry DATE DEFAULT NULL,
      NoOfYear VARCHAR(20) DEFAULT NULL,
      ProcessType VARCHAR(50) NOT NULL DEFAULT 'Excel Import',
      ProcessStatus VARCHAR(50) NOT NULL DEFAULT 'Completed',
      CompletedDate DATE NOT NULL,
      CompletedDateTime DATETIME NOT NULL,
      ProcessedBy INT NOT NULL,
      ProcessedByName VARCHAR(255) DEFAULT NULL,
      SourceFile VARCHAR(255) DEFAULT NULL,
      Remarks VARCHAR(500) DEFAULT NULL,
      CreatedDate DATETIME NOT NULL,
      PRIMARY KEY (id),
      KEY idx_cust (CustId),
      KEY idx_beneficiary (BeneficiaryId),
      KEY idx_completed (CompletedDate)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $conn->query($sql);
}

function insuranceSiteBaseEligibleSqlCondition()
{
    return 'tdo.Inst_Dispatcher_Otp_Verify = 1 AND tu.Roll = 5';
}

/** Core FROM/JOIN (sell + customer + tbl_user2). Avoid DATE '' comparisons in WHERE via helpers below. */
function insuranceSiteInsuranceFromSqlCore()
{
    return 'FROM tbl_sell tdo
        INNER JOIN tbl_users tu ON tdo.CustId = tu.id
        ' . insuranceSiteUser2JoinSql();
}

/** Full FROM including latest installation row (heavier; omit for dropdowns/counts when ti is unused). */
function insuranceSiteInsuranceFromSql()
{
    return insuranceSiteInsuranceFromSqlCore() . '
        ' . insuranceSiteInstallationJoinSql();
}

function insuranceSiteCompanyFilledSql()
{
    return "TRIM(IFNULL(tu.InsuranceAgency, '')) != ''";
}

function insuranceSitePolicyFilledSql()
{
    return "TRIM(IFNULL(tu.InsuranceNumber, '')) != ''";
}

function insuranceSiteIssueDateFilledSql($field = 'tu2.InsuranceIssueDate')
{
    // Avoid '' / '0000-00-00' literals on DATE columns (strict sql_mode).
    return "(YEAR($field) > 0)";
}

function insuranceSiteYearsFilledSql($field = 'tu2.InsuranceYears')
{
    return "TRIM(IFNULL($field, '')) != ''";
}

function insuranceSiteExpiryFilledSql()
{
    return '(' . insuranceSiteExpiryHasDateSql() . ')';
}

/** All five insurance fields present → completed insurance lists. */
function insuranceSiteAllDetailsFilledSqlCondition()
{
    return insuranceSiteCompanyFilledSql() . '
      AND ' . insuranceSitePolicyFilledSql() . '
      AND ' . insuranceSiteIssueDateFilledSql() . '
      AND ' . insuranceSiteExpiryFilledSql() . '
      AND ' . insuranceSiteYearsFilledSql();
}

/** Missing any required insurance field → pending insurance list. */
function insuranceSitePendingSqlCondition()
{
    return insuranceSiteBaseEligibleSqlCondition() . '
      AND NOT (' . insuranceSiteAllDetailsFilledSqlCondition() . ')';
}

function insuranceSiteCompletedSqlCondition()
{
    return insuranceSiteBaseEligibleSqlCondition() . '
      AND (' . insuranceSiteAllDetailsFilledSqlCondition() . ')';
}

function insuranceSiteExpiryDateSql($field = 'tu.InsuranceValidity')
{
    return "STR_TO_DATE($field, '%Y-%m-%d')";
}

function insuranceSiteExpiryYmdSql($field = 'tu.InsuranceValidity')
{
    return "STR_TO_DATE($field, '%Y-%m-%d')";
}

function insuranceSiteExpiryHasDateSql($field = 'tu.InsuranceValidity')
{
    return "(STR_TO_DATE($field, '%Y-%m-%d') IS NOT NULL OR ($field LIKE '%/%/%' AND STR_TO_DATE($field, '%d/%m/%Y') IS NOT NULL))";
}

function insuranceSiteExpiryBeforeSql($dateExpr, $field = 'tu.InsuranceValidity')
{
    return "(
        (STR_TO_DATE($field, '%Y-%m-%d') IS NOT NULL AND STR_TO_DATE($field, '%Y-%m-%d') < $dateExpr)
        OR ($field LIKE '%/%/%' AND STR_TO_DATE($field, '%d/%m/%Y') IS NOT NULL AND STR_TO_DATE($field, '%d/%m/%Y') < $dateExpr)
    )";
}

function insuranceSiteExpiryAfterSql($dateExpr, $field = 'tu.InsuranceValidity')
{
    return "(
        (STR_TO_DATE($field, '%Y-%m-%d') IS NOT NULL AND STR_TO_DATE($field, '%Y-%m-%d') > $dateExpr)
        OR ($field LIKE '%/%/%' AND STR_TO_DATE($field, '%d/%m/%Y') IS NOT NULL AND STR_TO_DATE($field, '%d/%m/%Y') > $dateExpr)
    )";
}

function insuranceSiteExpiryOnOrAfterSql($dateExpr, $field = 'tu.InsuranceValidity')
{
    return "(
        (STR_TO_DATE($field, '%Y-%m-%d') IS NOT NULL AND STR_TO_DATE($field, '%Y-%m-%d') >= $dateExpr)
        OR ($field LIKE '%/%/%' AND STR_TO_DATE($field, '%d/%m/%Y') IS NOT NULL AND STR_TO_DATE($field, '%d/%m/%Y') >= $dateExpr)
    )";
}

function insuranceSiteExpiryOnOrBeforeSql($dateExpr, $field = 'tu.InsuranceValidity')
{
    return "(
        (STR_TO_DATE($field, '%Y-%m-%d') IS NOT NULL AND STR_TO_DATE($field, '%Y-%m-%d') <= $dateExpr)
        OR ($field LIKE '%/%/%' AND STR_TO_DATE($field, '%d/%m/%Y') IS NOT NULL AND STR_TO_DATE($field, '%d/%m/%Y') <= $dateExpr)
    )";
}

function insuranceSiteActiveCompletedSqlCondition()
{
    $oneMonthAhead = 'DATE_ADD(CURDATE(), INTERVAL 1 MONTH)';

    return insuranceSiteCompletedSqlCondition() . "
      AND (
          NOT (" . insuranceSiteExpiryHasDateSql() . ")
          OR (" . insuranceSiteExpiryAfterSql($oneMonthAhead) . ")
      )";
}

function insuranceSiteExpiredSqlCondition()
{
    return insuranceSiteCompletedSqlCondition() . "
      AND (" . insuranceSiteExpiryBeforeSql('CURDATE()') . ")";
}

function insuranceSiteRenewalSqlCondition()
{
    $oneMonthAhead = 'DATE_ADD(CURDATE(), INTERVAL 1 MONTH)';

    return insuranceSiteCompletedSqlCondition() . "
      AND (" . insuranceSiteExpiryHasDateSql() . ")
      AND (" . insuranceSiteExpiryOnOrAfterSql('CURDATE()') . ")
      AND (" . insuranceSiteExpiryOnOrBeforeSql($oneMonthAhead) . ")";
}

function insuranceSiteRenewedSqlCondition()
{
    insuranceEnsureHistoryTable();

    return insuranceSiteCompletedSqlCondition() . "
      AND EXISTS (
          SELECT 1 FROM tbl_insurance_site_history h
          WHERE h.CustId = tu.id
            AND h.ProcessStatus = 'Renewed'
      )";
}

function insuranceSiteAppendListFilters($sql, $filters)
{
    global $conn;

    if (!empty($filters['cust_id']) && $filters['cust_id'] !== 'all') {
        $sql .= " AND tdo.CustId = '" . (int) $filters['cust_id'] . "'";
    }
    if (!empty($filters['district'])) {
        $district = mysqli_real_escape_string($conn, $filters['district']);
        $sql .= " AND tu.District = '$district'";
    }
    if (!empty($filters['taluka'])) {
        $taluka = mysqli_real_escape_string($conn, $filters['taluka']);
        $sql .= " AND tu.Taluka = '$taluka'";
    }
    if (!empty($filters['village'])) {
        $village = mysqli_real_escape_string($conn, $filters['village']);
        $sql .= " AND tu.Village = '$village'";
    }
    if (!empty($filters['from_date'])) {
        $fromDate = mysqli_real_escape_string($conn, $filters['from_date']);
        $sql .= " AND tdo.Inst_Dispatcher_Date >= '$fromDate'";
    }
    if (!empty($filters['to_date'])) {
        $toDate = mysqli_real_escape_string($conn, $filters['to_date']);
        $sql .= " AND tdo.Inst_Dispatcher_Date <= '$toDate'";
    }
    if (!empty($filters['project_id']) && $filters['project_id'] !== 'all') {
        $sql .= " AND tu.ProjectId = '" . (int) $filters['project_id'] . "'";
    }
    if (!empty($filters['project_sub_head_id']) && $filters['project_sub_head_id'] !== 'all') {
        $sql .= " AND tu.ProjectSubHeadId = '" . (int) $filters['project_sub_head_id'] . "'";
    }

    return $sql;
}

function insuranceSiteListFiltersFromRequest()
{
    $projectId = isset($_REQUEST['ProjectId']) ? trim((string) $_REQUEST['ProjectId']) : 'all';
    $projectSubHeadId = isset($_REQUEST['ProjectSubHeadId']) ? trim((string) $_REQUEST['ProjectSubHeadId']) : 'all';
    if ($projectId === '') {
        $projectId = 'all';
    }
    if ($projectSubHeadId === '') {
        $projectSubHeadId = 'all';
    }

    return array(
        'cust_id' => isset($_REQUEST['CustId']) ? trim((string) $_REQUEST['CustId']) : 'all',
        'from_date' => isset($_REQUEST['FromDate']) ? trim((string) $_REQUEST['FromDate']) : '',
        'to_date' => isset($_REQUEST['ToDate']) ? trim((string) $_REQUEST['ToDate']) : '',
        'district' => isset($_REQUEST['District']) ? trim((string) $_REQUEST['District']) : '',
        'taluka' => isset($_REQUEST['Taluka']) ? trim((string) $_REQUEST['Taluka']) : '',
        'village' => isset($_REQUEST['Village']) ? trim((string) $_REQUEST['Village']) : '',
        'project_id' => $projectId,
        'project_sub_head_id' => $projectSubHeadId,
    );
}

/**
 * Project + Project Sub Head filter dropdowns for insurance list pages.
 */
function insuranceSiteRenderProjectFilterFields($filterProjectId = 'all', $filterProjectSubHeadId = 'all')
{
    global $conn;
    $filterProjectId = ($filterProjectId === '' || $filterProjectId === null) ? 'all' : (string) $filterProjectId;
    $filterProjectSubHeadId = ($filterProjectSubHeadId === '' || $filterProjectSubHeadId === null) ? 'all' : (string) $filterProjectSubHeadId;
    ?>
                            <div class="form-group col-md-2">
                                <label class="form-label">Project</label>
                                <select class="form-control" id="ProjectId" name="ProjectId" onchange="insuranceSiteGetSubHead(this.value)">
                                    <option value="all" <?php if ($filterProjectId === 'all') { ?> selected <?php } ?>>All Project</option>
                                    <?php
                                    $projectRes = $conn->query("SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC");
                                    if ($projectRes) {
                                        while ($projectRow = $projectRes->fetch_assoc()) {
                                            ?>
                                            <option <?php if ($filterProjectId === (string) $projectRow['id']) { ?> selected <?php } ?> value="<?php echo (int) $projectRow['id']; ?>">
                                                <?php echo htmlspecialchars($projectRow['Name']); ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label">Project Sub Head</label>
                                <select class="form-control" id="ProjectSubHeadId" name="ProjectSubHeadId">
                                    <option value="all" <?php if ($filterProjectSubHeadId === 'all') { ?> selected <?php } ?>>All Sub Head</option>
                                    <?php
                                    if ($filterProjectId !== 'all') {
                                        $projectEsc = $conn->real_escape_string($filterProjectId);
                                        $subHeadRes = $conn->query("SELECT * FROM tbl_project_sub_head WHERE UnderBy='$projectEsc' AND Status='1' ORDER BY Name ASC");
                                        if ($subHeadRes) {
                                            while ($subHeadRow = $subHeadRes->fetch_assoc()) {
                                                ?>
                                                <option <?php if ($filterProjectSubHeadId === (string) $subHeadRow['id']) { ?> selected <?php } ?> value="<?php echo (int) $subHeadRow['id']; ?>">
                                                    <?php echo htmlspecialchars($subHeadRow['Name']); ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
    <?php
}

function insuranceSiteRenderProjectFilterScript()
{
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $loaded = true;
    ?>
<script type="text/javascript">
function insuranceSiteGetSubHead(projectId) {
    var $sub = $('#ProjectSubHeadId');
    if (!projectId || projectId === 'all') {
        $sub.html('<option value="all">All Sub Head</option>');
        return;
    }
    $.ajax({
        type: 'POST',
        url: 'ajax_files/ajax_dropdown.php',
        data: { action: 'getSubHead', id: projectId },
        success: function(data) {
            $sub.html('<option value="all">All Sub Head</option>');
            $(data).find('option').each(function() {
                var val = $(this).attr('value');
                if (val && val !== '') {
                    $sub.append($(this).clone());
                }
            });
        }
    });
}
</script>
    <?php
}

function insuranceSiteCustomerDropdownSql($whereCondition)
{
    return "SELECT DISTINCT tu.id, tu.Fname, tu.BeneficiaryId
        " . insuranceSiteInsuranceFromSqlCore() . "
        WHERE $whereCondition
        ORDER BY tu.Fname ASC";
}

function insuranceSiteListSelectSql($whereCondition, $filters, $orderBy = 'tdo.Inst_Dispatcher_Date DESC, tdo.id DESC')
{
    $sql = "SELECT tdo.*, tu.id AS UserId, tu.BeneficiaryId, tu.Taluka, tu.Village, tu.District, tu.ProjectType,
                   tu.InsuranceNumber, tu.InsuranceAgency, tu.InsuranceValidity,
                   tu2.InsuranceIssueDate, tu2.InsuranceYears
            " . insuranceSiteInsuranceFromSqlCore() . "
            WHERE $whereCondition";

    $sql = insuranceSiteAppendListFilters($sql, $filters);
    $sql .= " ORDER BY $orderBy";

    return $sql;
}

function insuranceSiteInstallationJoinSql()
{
    return "LEFT JOIN tbl_installations ti ON ti.id = (
        SELECT MAX(ti2.id) FROM tbl_installations ti2
        WHERE ti2.CustId = tu.id AND ti2.Type = 2
    )";
}

/** Extended customer fields (insurance issue date / years live in tbl_user2). */
function insuranceSiteUser2JoinSql()
{
    return 'LEFT JOIN tbl_user2 tu2 ON tu2.id = tu.id';
}

function insuranceEnsureUser2InsuranceColumns()
{
    global $conn;

    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    $res = $conn->query("SHOW COLUMNS FROM tbl_user2 LIKE 'InsuranceIssueDate'");
    if (!$res || $res->num_rows === 0) {
        $conn->query('ALTER TABLE tbl_user2 ADD COLUMN InsuranceIssueDate DATE NULL DEFAULT NULL');
    }
    $res = $conn->query("SHOW COLUMNS FROM tbl_user2 LIKE 'InsuranceYears'");
    if (!$res || $res->num_rows === 0) {
        $conn->query('ALTER TABLE tbl_user2 ADD COLUMN InsuranceYears VARCHAR(20) NULL DEFAULT NULL');
    }
}

function insuranceSaveUser2InsuranceFields($custId, $issueDate, $years)
{
    global $conn;

    insuranceEnsureUser2InsuranceColumns();

    $custId = (int) $custId;
    if ($custId <= 0) {
        return false;
    }

    $issueDate = mysqli_real_escape_string($conn, (string) $issueDate);
    $years = mysqli_real_escape_string($conn, (string) $years);
    $issueSql = $issueDate !== '' && $issueDate !== '0000-00-00' ? "'$issueDate'" : 'NULL';

    $conn->query("INSERT INTO tbl_user2 SET
        id = '$custId',
        InsuranceIssueDate = $issueSql,
        InsuranceYears = '$years'
        ON DUPLICATE KEY UPDATE
        InsuranceIssueDate = $issueSql,
        InsuranceYears = '$years'");

    return true;
}

function formatInsuranceDate($value)
{
    if ($value === null || $value === '' || $value === '0000-00-00') {
        return '';
    }

    $timestamp = strtotime(str_replace('-', '/', $value));
    if ($timestamp === false) {
        return $value;
    }

    return date('d/m/Y', $timestamp);
}

function getInsuranceYears($issueDate, $expiryDate, $storedYears)
{
    if ($storedYears !== null && $storedYears !== '') {
        return $storedYears;
    }

    if ($issueDate === null || $issueDate === '' || $expiryDate === null || $expiryDate === '') {
        return '';
    }

    $issueTimestamp = strtotime(str_replace('-', '/', $issueDate));
    $expiryTimestamp = strtotime(str_replace('-', '/', $expiryDate));
    if ($issueTimestamp === false || $expiryTimestamp === false || $expiryTimestamp < $issueTimestamp) {
        return '';
    }

    $years = (int) round(($expiryTimestamp - $issueTimestamp) / (365.25 * 24 * 60 * 60));
    return $years > 0 ? (string) $years : '';
}

function parseInsuranceDateForDb($value)
{
    if ($value instanceof DateTimeInterface) {
        return $value->format('Y-m-d');
    }

    if (is_int($value) || is_float($value)) {
        $serial = (float) $value;
        if ($serial > 1000 && $serial < 1000000) {
            $unix = ($serial - 25569) * 86400;
            if ($unix > 0) {
                return gmdate('Y-m-d', (int) $unix);
            }
        }
    }

    $value = str_replace(array("\xC2\xA0", '–', '—', '−'), array(' ', '-', '-', '-'), trim((string) $value));
    if ($value === '') {
        return '';
    }

    // Excel serial date as string (e.g. "45896" or "45896.0")
    if (is_numeric($value)) {
        $serial = (float) $value;
        if ($serial > 1000 && $serial < 1000000) {
            $unix = ($serial - 25569) * 86400;
            if ($unix > 0) {
                return gmdate('Y-m-d', (int) $unix);
            }
        }
    }

    // Drop time portion if present (26-08-2026 00:00:00)
    if (preg_match('/^(\d{1,4}[\/\-\.]\d{1,2}[\/\-\.]\d{1,4})/', $value, $datePart)) {
        $value = $datePart[1];
    }

    // Already YYYY-MM-DD
    if (preg_match('/^(\d{4})[\/\-\.](\d{1,2})[\/\-\.](\d{1,2})$/', $value, $m)) {
        $year = (int) $m[1];
        $month = (int) $m[2];
        $day = (int) $m[3];
        if (checkdate($month, $day, $year)) {
            return sprintf('%04d-%02d-%02d', $year, $month, $day);
        }
    }

    // DD-MM-YYYY or DD/MM/YYYY (day-month-year — common Excel format in India)
    if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/', $value, $m)) {
        $day = (int) $m[1];
        $month = (int) $m[2];
        $year = (int) $m[3];
        if (checkdate($month, $day, $year)) {
            return sprintf('%04d-%02d-%02d', $year, $month, $day);
        }
    }

    // DD-MM-YY (two-digit year)
    if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{2})$/', $value, $m)) {
        $day = (int) $m[1];
        $month = (int) $m[2];
        $year = (int) $m[3];
        $year += ($year >= 70) ? 1900 : 2000;
        if (checkdate($month, $day, $year)) {
            return sprintf('%04d-%02d-%02d', $year, $month, $day);
        }
    }

    $formats = array('d-m-Y', 'd/m/Y', 'j-n-Y', 'j/n/Y', 'd.m.Y', 'Y-m-d', 'm/d/Y', 'd-M-Y', 'd/M/Y', 'j-M-Y');
    foreach ($formats as $format) {
        $dt = DateTime::createFromFormat('!' . $format, $value);
        if ($dt instanceof DateTime) {
            $errors = DateTime::getLastErrors();
            if (empty($errors['warning_count']) && empty($errors['error_count'])) {
                return $dt->format('Y-m-d');
            }
        }
    }

    $timestamp = strtotime(str_replace('.', '/', str_replace('-', '/', $value)));
    if ($timestamp !== false) {
        return date('Y-m-d', $timestamp);
    }

    return '';
}

function insuranceImportDefaultColumnMap()
{
    return array(
        'beneficiary_id' => 1,
        'company' => 9,
        'policy_no' => 10,
        'issue_date' => 11,
        'expiry_date' => 12,
        'years' => 13,
    );
}

/** Trimmed export: Beneficiary ID + insurance columns only (no address block). */
function insuranceImportCompactColumnMap()
{
    return array(
        'beneficiary_id' => 1,
        'company' => 3,
        'policy_no' => 4,
        'issue_date' => 5,
        'expiry_date' => 6,
        'years' => 7,
    );
}

function insuranceImportColumnMapCandidates($primaryMap)
{
    $maps = array();
    if (is_array($primaryMap) && !empty($primaryMap)) {
        $maps[] = $primaryMap;
    }
    $maps[] = insuranceImportCompactColumnMap();
    $maps[] = insuranceImportDefaultColumnMap();

    $unique = array();
    $out = array();
    foreach ($maps as $map) {
        $key = json_encode($map);
        if (!isset($unique[$key])) {
            $unique[$key] = true;
            $out[] = $map;
        }
    }
    return $out;
}

function insuranceImportNormalizeHeader($header)
{
    $header = (string) $header;
    $header = preg_replace('/[\x{FEFF}\x00-\x1F}]/u', '', $header);
    $header = strtolower(trim(preg_replace('/\s+/', ' ', $header)));
    $header = rtrim($header, '.:');

    $map = array(
        'beneficiary id' => 'beneficiary_id',
        'beneficiaryid' => 'beneficiary_id',
        'insurance company name' => 'company',
        'insurance company' => 'company',
        'company name' => 'company',
        'policy no' => 'policy_no',
        'policy number' => 'policy_no',
        'policy no.' => 'policy_no',
        'date of issue' => 'issue_date',
        'issue date' => 'issue_date',
        'date of expiry' => 'expiry_date',
        'expiry date' => 'expiry_date',
        'no of year' => 'years',
        'no of years' => 'years',
        'number of year' => 'years',
    );

    return isset($map[$header]) ? $map[$header] : null;
}

function insuranceImportNormalizeBeneficiaryId($value)
{
    return strtoupper(trim((string) $value));
}

function insuranceImportNormalizeRow($row)
{
    if (!is_array($row)) {
        return array();
    }

    $normalized = array();
    foreach ($row as $index => $cell) {
        $normalized[(int) $index] = $cell;
    }

    if (!empty($normalized)) {
        $maxIndex = max(array_keys($normalized));
        for ($i = 0; $i <= $maxIndex; $i++) {
            if (!array_key_exists($i, $normalized)) {
                $normalized[$i] = '';
            }
        }
        ksort($normalized);
    }

    return $normalized;
}

function insuranceImportIsHeaderRow($row)
{
    return insuranceImportColumnMapScore(insuranceImportBuildColumnMap($row)) >= 2;
}

function insuranceImportBuildColumnMap($row)
{
    $map = array();
    $row = insuranceImportNormalizeRow($row);
    if (empty($row)) {
        return $map;
    }

    foreach ($row as $index => $cell) {
        $key = insuranceImportNormalizeHeader($cell);
        if ($key !== null) {
            $map[$key] = (int) $index;
        }
    }

    return $map;
}

function insuranceImportColumnMapScore($map)
{
    $score = 0;
    foreach (array('beneficiary_id', 'company', 'policy_no', 'expiry_date') as $key) {
        if (isset($map[$key])) {
            $score++;
        }
    }
    return $score;
}

function insuranceImportDetectColumnMap($rows)
{
    $defaultMap = insuranceImportDefaultColumnMap();
    $bestMap = null;
    $bestScore = 0;

    if (is_array($rows)) {
        foreach (array_slice($rows, 0, 10) as $row) {
            $candidate = insuranceImportBuildColumnMap($row);
            $score = insuranceImportColumnMapScore($candidate);
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMap = $candidate;
            }
        }
    }

    if ($bestScore >= 2 && is_array($bestMap)) {
        return array_merge($defaultMap, $bestMap);
    }

    return array_merge($defaultMap, insuranceImportCompactColumnMap());
}

function insuranceImportRawCellValue($row, $columnMap, $key)
{
    if (!isset($columnMap[$key])) {
        return '';
    }

    $row = insuranceImportNormalizeRow($row);
    $index = (int) $columnMap[$key];
    if (!array_key_exists($index, $row)) {
        return '';
    }

    $value = $row[$index];
    if ($value === null) {
        return '';
    }

    return $value;
}

function insuranceImportCellValue($row, $columnMap, $key)
{
    $value = insuranceImportRawCellValue($row, $columnMap, $key);
    if ($value === '') {
        return '';
    }

    if ($value instanceof DateTimeInterface) {
        return $value->format('Y-m-d');
    }

    if (is_bool($value)) {
        return $value ? '1' : '0';
    }

    if (is_int($value) || is_float($value)) {
        if (in_array($key, array('issue_date', 'expiry_date'), true)) {
            $parsed = parseInsuranceDateForDb($value);
            return $parsed !== '' ? $parsed : trim((string) $value);
        }
        if ($key === 'policy_no') {
            $num = (float) $value;
            if (abs($num - round($num)) < 0.00001) {
                return (string) (int) round($num);
            }
        }
        return trim(rtrim((string) $value, '.0'));
    }

    return trim((string) $value);
}

function insuranceImportColumnLetter($index)
{
    $index = (int) $index;
    $letter = '';
    do {
        $letter = chr(65 + ($index % 26)) . $letter;
        $index = (int) floor($index / 26) - 1;
    } while ($index >= 0);

    return $letter;
}

function insuranceImportDebugCellValue($raw)
{
    if ($raw === null || $raw === '') {
        return '(blank — cell is empty in file)';
    }
    if (is_float($raw) || is_int($raw)) {
        $parsed = parseInsuranceDateForDb($raw);
        if ($parsed !== '') {
            return $parsed . ' (Excel date number)';
        }
        return '(number: ' . $raw . ')';
    }
    return '"' . substr(trim((string) $raw), 0, 50) . '"';
}

function insuranceImportScanExpiryFromRow($row, $issueDate, $preferredIndex = null)
{
    $row = insuranceImportNormalizeRow($row);
    $candidates = array();

    foreach ($row as $idx => $cell) {
        if ($cell === null || $cell === '') {
            continue;
        }
        $parsed = parseInsuranceDateForDb($cell);
        if ($parsed !== '') {
            $candidates[(int) $idx] = $parsed;
        }
    }

    if (empty($candidates)) {
        return '';
    }

    $preferOrder = array();
    if ($preferredIndex !== null) {
        $preferOrder[] = (int) $preferredIndex;
    }
    $preferOrder = array_merge($preferOrder, array(6, 12, 7, 13, 5, 11));

    foreach ($preferOrder as $idx) {
        if (isset($candidates[$idx])) {
            return $candidates[$idx];
        }
    }

    foreach ($candidates as $parsed) {
        if ($issueDate === '' || $parsed !== $issueDate) {
            return $parsed;
        }
    }

    return '';
}

function insuranceImportGetDateValue($row, $columnMap, $key)
{
    $raw = insuranceImportRawCellValue($row, $columnMap, $key);
    if ($raw !== null && $raw !== '') {
        if ($raw instanceof DateTimeInterface) {
            return $raw->format('Y-m-d');
        }
        $parsed = parseInsuranceDateForDb($raw);
        if ($parsed !== '') {
            return $parsed;
        }
    }

    $cell = insuranceImportCellValue($row, $columnMap, $key);
    if ($cell !== '') {
        $parsed = parseInsuranceDateForDb($cell);
        return $parsed !== '' ? $parsed : $cell;
    }

    if ($key === 'expiry_date') {
        $issueDate = '';
        if (isset($columnMap['issue_date'])) {
            $issueRaw = insuranceImportRawCellValue($row, $columnMap, 'issue_date');
            if ($issueRaw !== null && $issueRaw !== '') {
                $issueDate = parseInsuranceDateForDb($issueRaw);
            }
        }
        $preferred = isset($columnMap['expiry_date']) ? (int) $columnMap['expiry_date'] : null;
        return insuranceImportScanExpiryFromRow($row, $issueDate, $preferred);
    }

    return '';
}

function insuranceImportExplainMissing($beneficiaryId, $row, $columnMap, $missing)
{
    $parts = array();

    if (in_array('Insurance Company Name', $missing, true)) {
        $idx = isset($columnMap['company']) ? (int) $columnMap['company'] : -1;
        $parts[] = 'Insurance Company Name col ' . insuranceImportColumnLetter($idx) . ': ' . insuranceImportDebugCellValue(insuranceImportRawCellValue($row, $columnMap, 'company'));
    }
    if (in_array('Policy No', $missing, true)) {
        $idx = isset($columnMap['policy_no']) ? (int) $columnMap['policy_no'] : -1;
        $parts[] = 'Policy No col ' . insuranceImportColumnLetter($idx) . ': ' . insuranceImportDebugCellValue(insuranceImportRawCellValue($row, $columnMap, 'policy_no'));
    }
    if (in_array('Date Of Expiry', $missing, true)) {
        $idx = isset($columnMap['expiry_date']) ? (int) $columnMap['expiry_date'] : -1;
        $raw = insuranceImportRawCellValue($row, $columnMap, 'expiry_date');
        $parts[] = 'Date Of Expiry col ' . insuranceImportColumnLetter($idx) . ' (header "Date Of Expiry"): ' . insuranceImportDebugCellValue($raw) . '. Use dd-mm-yyyy e.g. 27-08-2026';
    }
    if (in_array('Date Of Issue', $missing, true)) {
        $idx = isset($columnMap['issue_date']) ? (int) $columnMap['issue_date'] : -1;
        $raw = insuranceImportRawCellValue($row, $columnMap, 'issue_date');
        $parts[] = 'Date Of Issue col ' . insuranceImportColumnLetter($idx) . ' (header "Date Of Issue"): ' . insuranceImportDebugCellValue($raw) . '. Use dd-mm-yyyy e.g. 26-08-2026';
    }
    if (in_array('No of Year', $missing, true)) {
        $idx = isset($columnMap['years']) ? (int) $columnMap['years'] : -1;
        $parts[] = 'No of Year col ' . insuranceImportColumnLetter($idx) . ': ' . insuranceImportDebugCellValue(insuranceImportRawCellValue($row, $columnMap, 'years'));
    }

    return $beneficiaryId . ': ' . implode(' | ', $parts);
}

function insuranceImportFieldValue($row, $primaryMap, $fallbackMap, $key)
{
    unset($fallbackMap);
    foreach (insuranceImportColumnMapCandidates($primaryMap) as $map) {
        $value = insuranceImportCellValue($row, $map, $key);
        if ($value !== '') {
            return $value;
        }
    }
    return '';
}

function insuranceImportDateFieldValue($row, $primaryMap, $fallbackMap, $key)
{
    unset($fallbackMap);
    foreach (insuranceImportColumnMapCandidates($primaryMap) as $map) {
        $value = insuranceImportGetDateValue($row, $map, $key);
        if ($value !== '') {
            return $value;
        }
    }
    return '';
}

function insuranceImportMissingFields($company, $policyNo, $expiryDate, $issueDate = '', $years = '', $options = array())
{
    $missing = array();
    if ($company === '') {
        $missing[] = 'Insurance Company Name';
    }
    if ($policyNo === '') {
        $missing[] = 'Policy No';
    }
    if (!empty($options['require_issue_date']) && $issueDate === '') {
        $missing[] = 'Date Of Issue';
    }
    if ($expiryDate === '') {
        $missing[] = 'Date Of Expiry';
    }
    if (!empty($options['require_years']) && $years === '') {
        $missing[] = 'No of Year';
    }
    return $missing;
}

function insuranceImportProcessSpreadsheet($targetPath, $originalName, $fileType, $resolveCustomerCallback, $importRowCallback, $options = array())
{
    require_once __DIR__ . '/vendor/php-excel-reader/excel_reader2.php';
    require_once __DIR__ . '/vendor/SpreadsheetReader.php';

    $imported = 0;
    $skipped = 0;
    $errors = array();
    $allRows = array();
    $defaultMap = insuranceImportDefaultColumnMap();

    try {
        $Reader = new SpreadsheetReader($targetPath, $originalName, $fileType);
        $sheetCount = count($Reader->sheets());

        for ($s = 0; $s < $sheetCount; $s++) {
            $Reader->ChangeSheet($s);
            foreach ($Reader as $Row) {
                if (is_array($Row)) {
                    $allRows[] = insuranceImportNormalizeRow($Row);
                }
            }
        }
    } catch (Exception $e) {
        return array(
            'success' => false,
            'message' => 'Could not read Excel file.',
            'imported' => 0,
            'skipped' => 0,
            'errors' => array(),
        );
    }

    if (empty($allRows)) {
        return array(
            'success' => false,
            'message' => 'Excel file is empty.',
            'imported' => 0,
            'skipped' => 0,
            'errors' => array(),
        );
    }

    $columnMap = insuranceImportDetectColumnMap($allRows);
    if (!isset($columnMap['beneficiary_id'])) {
        return array(
            'success' => false,
            'message' => 'Beneficiary ID column not found in Excel file.',
            'imported' => 0,
            'skipped' => 0,
            'errors' => array(),
        );
    }

    foreach ($allRows as $Row) {
        if (insuranceImportIsHeaderRow($Row)) {
            continue;
        }

        $beneficiaryId = insuranceImportNormalizeBeneficiaryId(
            insuranceImportFieldValue($Row, $columnMap, $defaultMap, 'beneficiary_id')
        );
        if ($beneficiaryId === '' || preg_match('/^beneficiary\s*id$/i', $beneficiaryId)) {
            continue;
        }

        $company = insuranceImportFieldValue($Row, $columnMap, $defaultMap, 'company');
        $policyNo = insuranceImportFieldValue($Row, $columnMap, $defaultMap, 'policy_no');
        $issueDate = insuranceImportDateFieldValue($Row, $columnMap, $defaultMap, 'issue_date');
        $expiryDate = insuranceImportDateFieldValue($Row, $columnMap, $defaultMap, 'expiry_date');
        $years = insuranceImportFieldValue($Row, $columnMap, $defaultMap, 'years');

        $missing = insuranceImportMissingFields($company, $policyNo, $expiryDate, $issueDate, $years, $options);
        if (!empty($missing)) {
            $skipped++;
            $errors[] = insuranceImportExplainMissing($beneficiaryId, $Row, $columnMap, $missing);
            continue;
        }

        $customer = call_user_func($resolveCustomerCallback, $beneficiaryId);
        if (empty($customer['CustId'])) {
            $skipped++;
            $notFoundMsg = !empty($options['not_found_message'])
                ? $options['not_found_message']
                : 'Not found in insurance list.';
            $errors[] = $beneficiaryId . ': ' . $notFoundMsg;
            continue;
        }

        if ($years === '' && $issueDate !== '' && $expiryDate !== '') {
            $years = getInsuranceYears($issueDate, $expiryDate, '');
        }

        call_user_func($importRowCallback, $customer, array(
            'company' => $company,
            'policy_no' => $policyNo,
            'issue_date' => $issueDate,
            'expiry_date' => $expiryDate,
            'years' => $years,
        ));

        $imported++;
    }

    return array(
        'success' => true,
        'imported' => $imported,
        'skipped' => $skipped,
        'errors' => $errors,
    );
}

function insuranceGetPendingCustomerByBeneficiaryId($beneficiaryId)
{
    global $conn;

    $beneficiaryId = insuranceImportNormalizeBeneficiaryId($beneficiaryId);
    if ($beneficiaryId === '') {
        return null;
    }

    $beneficiaryIdEsc = mysqli_real_escape_string($conn, $beneficiaryId);
    $sql = "SELECT tu.id AS CustId, tu.BeneficiaryId, tu.Fname, tu.ProjectId, tu.ProjectSubHeadId,
                   tu.ProjectType, tu.Taluka, tu.Village, tu.District, tu.Address,
                   tdo.CustName, tdo.CellNo, tdo.Inst_Dispatcher_Date,
                   ti.id AS InstId
            " . insuranceSiteInsuranceFromSql() . "
            WHERE " . insuranceSitePendingSqlCondition() . "
              AND tu.BeneficiaryId = '$beneficiaryIdEsc'
            LIMIT 1";

    return getRecord($sql);
}

function insuranceGetExpiredCustomerByBeneficiaryId($beneficiaryId)
{
    global $conn;

    $beneficiaryId = insuranceImportNormalizeBeneficiaryId($beneficiaryId);
    if ($beneficiaryId === '') {
        return null;
    }

    $beneficiaryIdEsc = mysqli_real_escape_string($conn, $beneficiaryId);
    $sql = "SELECT tu.id AS CustId, tu.BeneficiaryId, tu.Fname, tu.ProjectId, tu.ProjectSubHeadId,
                   tu.ProjectType, tu.Taluka, tu.Village, tu.District, tu.Address,
                   tdo.CustName, tdo.CellNo, tdo.Inst_Dispatcher_Date,
                   ti.id AS InstId
            " . insuranceSiteInsuranceFromSql() . "
            WHERE " . insuranceSiteExpiredSqlCondition() . "
              AND tu.BeneficiaryId = '$beneficiaryIdEsc'
            LIMIT 1";

    return getRecord($sql);
}

function insuranceGetRenewalCustomerByBeneficiaryId($beneficiaryId)
{
    global $conn;

    $beneficiaryId = insuranceImportNormalizeBeneficiaryId($beneficiaryId);
    if ($beneficiaryId === '') {
        return null;
    }

    $beneficiaryIdEsc = mysqli_real_escape_string($conn, $beneficiaryId);
    $sql = "SELECT tu.id AS CustId, tu.BeneficiaryId, tu.Fname, tu.ProjectId, tu.ProjectSubHeadId,
                   tu.ProjectType, tu.Taluka, tu.Village, tu.District, tu.Address,
                   tdo.CustName, tdo.CellNo, tdo.Inst_Dispatcher_Date,
                   ti.id AS InstId
            " . insuranceSiteInsuranceFromSql() . "
            WHERE " . insuranceSiteRenewalSqlCondition() . "
              AND tu.BeneficiaryId = '$beneficiaryIdEsc'
            LIMIT 1";

    return getRecord($sql);
}

function insuranceSaveSiteHistory($customer, $data, $userId, $meta = array())
{
    global $conn;

    insuranceEnsureHistoryTable();

    $custId = (int) $customer['CustId'];
    $userId = (int) $userId;
    if ($custId <= 0) {
        return false;
    }

    $processedByName = '';
    if ($userId > 0) {
        $userRow = getRecord("SELECT CONCAT(Fname, ' ', Lname) AS FullName FROM tbl_users WHERE id = '$userId' LIMIT 1");
        $processedByName = !empty($userRow['FullName']) ? trim($userRow['FullName']) : '';
    }

    $beneficiaryId = mysqli_real_escape_string($conn, $customer['BeneficiaryId']);
    $custName = mysqli_real_escape_string($conn, !empty($customer['CustName']) ? $customer['CustName'] : $customer['Fname']);
    $cellNo = mysqli_real_escape_string($conn, $customer['CellNo']);
    $projectType = isset($customer['ProjectType']) ? (int) $customer['ProjectType'] : 0;
    $taluka = mysqli_real_escape_string($conn, $customer['Taluka']);
    $village = mysqli_real_escape_string($conn, $customer['Village']);
    $district = mysqli_real_escape_string($conn, $customer['District']);
    $address = mysqli_real_escape_string($conn, $customer['Address']);
    $dispatchDate = '';
    if (!empty($customer['Inst_Dispatcher_Date']) && $customer['Inst_Dispatcher_Date'] !== '0000-00-00') {
        $dispatchDate = mysqli_real_escape_string($conn, $customer['Inst_Dispatcher_Date']);
    }

    $company = mysqli_real_escape_string($conn, $data['company']);
    $policyNo = mysqli_real_escape_string($conn, $data['policy_no']);
    $issueDate = mysqli_real_escape_string($conn, $data['issue_date']);
    $expiryDate = mysqli_real_escape_string($conn, $data['expiry_date']);
    $years = mysqli_real_escape_string($conn, $data['years']);
    $processType = mysqli_real_escape_string($conn, !empty($meta['process_type']) ? $meta['process_type'] : 'Excel Import');
    $processStatus = mysqli_real_escape_string($conn, !empty($meta['process_status']) ? $meta['process_status'] : 'Completed');
    $sourceFile = mysqli_real_escape_string($conn, !empty($meta['source_file']) ? $meta['source_file'] : '');
    $remarks = mysqli_real_escape_string($conn, !empty($meta['remarks']) ? $meta['remarks'] : '');
    $processedByNameEsc = mysqli_real_escape_string($conn, $processedByName);
    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');

    $dispatchSql = $dispatchDate !== '' ? "'$dispatchDate'" : 'NULL';
    $issueSql = $issueDate !== '' ? "'$issueDate'" : 'NULL';
    $expirySql = $expiryDate !== '' ? "'$expiryDate'" : 'NULL';

    $conn->query("INSERT INTO tbl_insurance_site_history SET
        CustId = '$custId',
        BeneficiaryId = '$beneficiaryId',
        CustName = '$custName',
        CellNo = '$cellNo',
        ProjectType = '$projectType',
        Taluka = '$taluka',
        Village = '$village',
        District = '$district',
        Address = '$address',
        SiteDispatchDate = $dispatchSql,
        InsuranceCompany = '$company',
        PolicyNo = '$policyNo',
        DateOfIssue = $issueSql,
        DateOfExpiry = $expirySql,
        NoOfYear = '$years',
        ProcessType = '$processType',
        ProcessStatus = '$processStatus',
        CompletedDate = '$today',
        CompletedDateTime = '$now',
        ProcessedBy = '$userId',
        ProcessedByName = '$processedByNameEsc',
        SourceFile = '$sourceFile',
        Remarks = '$remarks',
        CreatedDate = '$now'");

    return true;
}

function insuranceGetSiteHistoryByCustomer($custId)
{
    global $conn;

    insuranceEnsureHistoryTable();

    $custId = (int) $custId;
    if ($custId <= 0) {
        return array();
    }

    return getList("SELECT h.*, u.Fname AS ProcessorFname, u.Lname AS ProcessorLname
        FROM tbl_insurance_site_history h
        LEFT JOIN tbl_users u ON u.id = h.ProcessedBy
        WHERE h.CustId = '$custId'
        ORDER BY h.CompletedDateTime DESC, h.id DESC");
}

/**
 * Latest insurance snapshot for a customer (completed / renewed lists + history).
 *
 * @return array|null Keys: insurance_no, company_name, date_of_issue, date_of_expiry, no_of_years, source_label
 */
function insuranceGetLatestCustomerInsurance($custId)
{
    global $conn;

    insuranceEnsureUser2InsuranceColumns();
    insuranceEnsureHistoryTable();

    $custId = (int) $custId;
    if ($custId <= 0) {
        return null;
    }

    $current = getRecord("SELECT tu.InsuranceAgency, tu.InsuranceNumber, tu.InsuranceValidity,
            tu2.InsuranceIssueDate, tu2.InsuranceYears
        FROM tbl_users tu
        LEFT JOIN tbl_user2 tu2 ON tu2.id = tu.id
        WHERE tu.id = '$custId'
        LIMIT 1");

    $history = getRecord("SELECT InsuranceCompany, PolicyNo, DateOfIssue, DateOfExpiry, NoOfYear,
            ProcessStatus, CompletedDateTime
        FROM tbl_insurance_site_history
        WHERE CustId = '$custId'
        ORDER BY CompletedDateTime DESC, id DESC
        LIMIT 1");

    $candidates = array();

    if (!empty($current)) {
        $issue = !empty($current['InsuranceIssueDate']) && $current['InsuranceIssueDate'] !== '0000-00-00'
            ? $current['InsuranceIssueDate'] : '';
        $expiry = !empty($current['InsuranceValidity']) ? parseInsuranceDateForDb($current['InsuranceValidity']) : '';
        if ($expiry === '' && !empty($current['InsuranceValidity'])) {
            $expiry = trim((string) $current['InsuranceValidity']);
        }
        $hasAny = trim((string) ($current['InsuranceAgency'] ?? '')) !== ''
            || trim((string) ($current['InsuranceNumber'] ?? '')) !== ''
            || $issue !== ''
            || $expiry !== '';
        if ($hasAny) {
            $candidates[] = array(
                'insurance_no' => trim((string) ($current['InsuranceNumber'] ?? '')),
                'company_name' => trim((string) ($current['InsuranceAgency'] ?? '')),
                'date_of_issue' => $issue,
                'date_of_expiry' => $expiry,
                'no_of_years' => getInsuranceYears($issue, $expiry, $current['InsuranceYears'] ?? ''),
                'source_label' => 'Current Insurance',
                'sort_key' => insuranceLatestInsuranceSortKey($expiry, $issue, ''),
            );
        }
    }

    if (!empty($history)) {
        $issue = !empty($history['DateOfIssue']) && $history['DateOfIssue'] !== '0000-00-00'
            ? $history['DateOfIssue'] : '';
        $expiry = !empty($history['DateOfExpiry']) && $history['DateOfExpiry'] !== '0000-00-00'
            ? $history['DateOfExpiry'] : '';
        $source = ($history['ProcessStatus'] ?? '') === 'Renewed' ? 'Renewed Insurance' : 'Completed Insurance';
        $candidates[] = array(
            'insurance_no' => trim((string) ($history['PolicyNo'] ?? '')),
            'company_name' => trim((string) ($history['InsuranceCompany'] ?? '')),
            'date_of_issue' => $issue,
            'date_of_expiry' => $expiry,
            'no_of_years' => trim((string) ($history['NoOfYear'] ?? '')),
            'source_label' => $source,
            'sort_key' => insuranceLatestInsuranceSortKey($expiry, $issue, $history['CompletedDateTime'] ?? ''),
        );
    }

    if (empty($candidates)) {
        return null;
    }

    usort($candidates, function ($a, $b) {
        return strcmp($b['sort_key'], $a['sort_key']);
    });

    $best = $candidates[0];
    $best['date_of_issue_display'] = formatInsuranceDate($best['date_of_issue']);
    $best['date_of_expiry_display'] = formatInsuranceDate($best['date_of_expiry']);
    if ($best['no_of_years'] === '' && $best['date_of_issue'] !== '' && $best['date_of_expiry'] !== '') {
        $best['no_of_years'] = getInsuranceYears($best['date_of_issue'], $best['date_of_expiry'], '');
    }

    unset($best['sort_key']);

    return $best;
}

function insuranceLatestInsuranceSortKey($expiry, $issue, $completedDateTime)
{
    $expiryKey = parseInsuranceDateForDb($expiry);
    if ($expiryKey !== '') {
        return '9_' . $expiryKey;
    }
    $issueKey = parseInsuranceDateForDb($issue);
    if ($issueKey !== '') {
        return '8_' . $issueKey;
    }
    $dt = trim((string) $completedDateTime);
    if ($dt !== '' && $dt !== '0000-00-00 00:00:00') {
        return '7_' . $dt;
    }

    return '0';
}

function insuranceMarkCustomerCompleted($custId, $data, $userId, $customer = array(), $meta = array())
{
    global $conn;

    $custId = (int) $custId;
    $userId = (int) $userId;
    if ($custId <= 0) {
        return false;
    }

    $company = mysqli_real_escape_string($conn, $data['company']);
    $policyNo = mysqli_real_escape_string($conn, $data['policy_no']);
    $issueDate = mysqli_real_escape_string($conn, $data['issue_date']);
    $expiryDate = mysqli_real_escape_string($conn, $data['expiry_date']);
    $years = mysqli_real_escape_string($conn, $data['years']);
    $today = date('Y-m-d');

    $conn->query("UPDATE tbl_users SET
        InsuranceAgency = '$company',
        InsuranceNumber = '$policyNo',
        InsuranceValidity = '$expiryDate'
        WHERE id = '$custId'");

    insuranceSaveUser2InsuranceFields($custId, $data['issue_date'], $data['years']);

    $inst = getRecord("SELECT id FROM tbl_installations WHERE CustId = '$custId' AND Type = 2 ORDER BY id DESC LIMIT 1");
    if (!empty($inst['id'])) {
        $instId = (int) $inst['id'];
        $conn->query("UPDATE tbl_installations SET
            InsuranceApproval = 'Yes',
            InsuranceApprovalDate = '$today',
            ModifiedBy = '$userId',
            ModifiedDate = NOW()
            WHERE id = '$instId'");
    }

    if (!empty($customer)) {
        insuranceSaveSiteHistory($customer, $data, $userId, $meta);
    }

    return true;
}

insuranceEnsureUser2InsuranceColumns();
