<?php

function contractorPaymentEnsureTable($conn)
{
    static $done = false;
    if ($done) {
        return true;
    }
    $sql = "CREATE TABLE IF NOT EXISTS tbl_contractor_commission_payment (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      ContractorId INT NOT NULL,
      Amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      PaymentDate DATE NOT NULL,
      PaymentMode VARCHAR(50) DEFAULT NULL,
      ReferenceNo VARCHAR(100) DEFAULT NULL,
      Narration VARCHAR(500) DEFAULT NULL,
      Status TINYINT NOT NULL DEFAULT 1,
      CreatedBy INT DEFAULT NULL,
      CreatedDate DATETIME NOT NULL,
      ModifiedBy INT DEFAULT NULL,
      ModifiedDate DATETIME DEFAULT NULL,
      PRIMARY KEY (id),
      KEY idx_contractor (ContractorId),
      KEY idx_payment_date (PaymentDate),
      KEY idx_status (Status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $ok = (bool) $conn->query($sql);
    contractorPaymentEnsureAdvanceTable($conn);
    $done = $ok;
    return $ok;
}

function contractorPaymentEnsureAdvanceTable($conn)
{
    static $done = false;
    if ($done) {
        return true;
    }
    $sql = "CREATE TABLE IF NOT EXISTS tbl_contractor_advance_payment (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      ContractorId INT NOT NULL,
      Amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      PaymentDate DATE NOT NULL,
      PaymentMode VARCHAR(50) DEFAULT NULL,
      ReferenceNo VARCHAR(100) DEFAULT NULL,
      Narration VARCHAR(500) DEFAULT NULL,
      Status TINYINT NOT NULL DEFAULT 1,
      CreatedBy INT DEFAULT NULL,
      CreatedDate DATETIME NOT NULL,
      ModifiedBy INT DEFAULT NULL,
      ModifiedDate DATETIME DEFAULT NULL,
      PRIMARY KEY (id),
      KEY idx_contractor (ContractorId),
      KEY idx_payment_date (PaymentDate),
      KEY idx_status (Status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $ok = (bool) $conn->query($sql);
    $done = $ok;
    return $ok;
}

function contractorPaymentAmountValue($raw)
{
    if ($raw === null || $raw === '') {
        return 0.0;
    }
    return (float) $raw;
}

function contractorPaymentFormatMoney($amount)
{
    return number_format((float) $amount, 2);
}

function contractorPaymentCommissionTotal($conn, $contractorId)
{
    $contractorId = (int) $contractorId;
    if ($contractorId <= 0) {
        return 0.0;
    }
    $sql = "SELECT Amount FROM tbl_made_contractor_commision WHERE ContractorId='$contractorId'";
    $res = $conn->query($sql);
    $total = 0.0;
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $total += contractorPaymentAmountValue($row['Amount'] ?? 0);
        }
    }
    return $total;
}

function contractorPaymentPaidTotal($conn, $contractorId)
{
    contractorPaymentEnsureTable($conn);
    $contractorId = (int) $contractorId;
    if ($contractorId <= 0) {
        return 0.0;
    }
    $row = getRecord("SELECT COALESCE(SUM(Amount), 0) AS total FROM tbl_contractor_commission_payment WHERE ContractorId='$contractorId' AND Status='1'");
    return contractorPaymentAmountValue($row['total'] ?? 0);
}

function contractorPaymentAdvanceTotal($conn, $contractorId)
{
    contractorPaymentEnsureAdvanceTable($conn);
    $contractorId = (int) $contractorId;
    if ($contractorId <= 0) {
        return 0.0;
    }
    $row = getRecord("SELECT COALESCE(SUM(Amount), 0) AS total FROM tbl_contractor_advance_payment WHERE ContractorId='$contractorId' AND Status='1'");
    return contractorPaymentAmountValue($row['total'] ?? 0);
}

function contractorPaymentBalance($conn, $contractorId)
{
    $commission = contractorPaymentCommissionTotal($conn, $contractorId);
    $paid = contractorPaymentPaidTotal($conn, $contractorId);
    $advance = contractorPaymentAdvanceTotal($conn, $contractorId);
    $balance = $commission - $paid - $advance;
    return $balance > 0 ? $balance : 0.0;
}

function contractorPaymentGetContractor($conn, $contractorId)
{
    $contractorId = (int) $contractorId;
    if ($contractorId <= 0) {
        return null;
    }
    return getRecord("SELECT id, Fname, Lname, Phone, Phone2, BeneficiaryId FROM tbl_users WHERE id='$contractorId' AND Roll='40' LIMIT 1");
}

function contractorPaymentModes()
{
    return [
        'Cash' => 'Cash',
        'Bank Transfer' => 'Bank Transfer',
        'UPI' => 'UPI',
        'Cheque' => 'Cheque',
        'NEFT' => 'NEFT',
        'RTGS' => 'RTGS',
        'Other' => 'Other',
    ];
}

function contractorPaymentSummaryAll($conn)
{
    contractorPaymentEnsureTable($conn);
    $contractors = getList("SELECT id, Fname, Lname, Phone FROM tbl_users WHERE Roll='40' AND Status='1' ORDER BY Fname ASC");
    $rows = [];
    $totCommission = 0.0;
    $totPaid = 0.0;
    $totAdvance = 0.0;
    $totBalance = 0.0;
    $pendingCount = 0;

    foreach ($contractors as $c) {
        $id = (int) $c['id'];
        $commission = contractorPaymentCommissionTotal($conn, $id);
        $advance = contractorPaymentAdvanceTotal($conn, $id);
        if ($commission <= 0 && $advance <= 0) {
            continue;
        }
        $paid = contractorPaymentPaidTotal($conn, $id);
        $balance = contractorPaymentBalance($conn, $id);
        $rows[] = [
            'id' => $id,
            'name' => trim((string) ($c['Fname'] ?? '') . ' ' . (string) ($c['Lname'] ?? '')),
            'phone' => trim((string) ($c['Phone'] ?? '')),
            'commission' => $commission,
            'paid' => $paid,
            'advance' => $advance,
            'balance' => $balance,
        ];
        $totCommission += $commission;
        $totPaid += $paid;
        $totAdvance += $advance;
        $totBalance += $balance;
        if ($balance > 0) {
            $pendingCount++;
        }
    }

    return [
        'rows' => $rows,
        'totCommission' => $totCommission,
        'totPaid' => $totPaid,
        'totAdvance' => $totAdvance,
        'totBalance' => $totBalance,
        'pendingCount' => $pendingCount,
        'contractorCount' => count($rows),
    ];
}

function contractorPaymentSave($conn, $contractorId, $amount, $paymentDate, $paymentMode, $referenceNo, $narration, $userId)
{
    contractorPaymentEnsureTable($conn);
    $contractorId = (int) $contractorId;
    $userId = (int) $userId;
    $amount = round((float) $amount, 2);

    if ($contractorId <= 0 || $amount <= 0) {
        return ['ok' => false, 'message' => 'Invalid contractor or payment amount.'];
    }

    $contractor = contractorPaymentGetContractor($conn, $contractorId);
    if (!$contractor) {
        return ['ok' => false, 'message' => 'Contractor not found.'];
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $paymentDate)) {
        return ['ok' => false, 'message' => 'Invalid payment date.'];
    }

    $balance = contractorPaymentBalance($conn, $contractorId);
    if ($amount > $balance + 0.001) {
        return ['ok' => false, 'message' => 'Payment amount cannot exceed balance (' . contractorPaymentFormatMoney($balance) . ').'];
    }

    $paymentMode = substr(trim((string) $paymentMode), 0, 50);
    $referenceNo = substr(trim((string) $referenceNo), 0, 100);
    $narration = substr(trim((string) $narration), 0, 500);
    $now = date('Y-m-d H:i:s');

    $paymentModeEsc = $conn->real_escape_string($paymentMode);
    $referenceNoEsc = $conn->real_escape_string($referenceNo);
    $narrationEsc = $conn->real_escape_string($narration);
    $paymentDateEsc = $conn->real_escape_string($paymentDate);

    $sql = "INSERT INTO tbl_contractor_commission_payment SET
        ContractorId='$contractorId',
        Amount='$amount',
        PaymentDate='$paymentDateEsc',
        PaymentMode='$paymentModeEsc',
        ReferenceNo='$referenceNoEsc',
        Narration='$narrationEsc',
        Status='1',
        CreatedBy='$userId',
        CreatedDate='$now'";

    if (!$conn->query($sql)) {
        return ['ok' => false, 'message' => 'Could not save payment.'];
    }

    return [
        'ok' => true,
        'message' => 'Payment saved successfully.',
        'id' => (int) mysqli_insert_id($conn),
    ];
}

function contractorPaymentHistoryRows($conn, $contractorId)
{
    contractorPaymentEnsureTable($conn);
    $contractorId = (int) $contractorId;
    if ($contractorId <= 0) {
        return [];
    }
    return getList("SELECT p.*, u.Fname AS CreatedByName
        FROM tbl_contractor_commission_payment p
        LEFT JOIN tbl_users u ON u.id = p.CreatedBy
        WHERE p.ContractorId='$contractorId' AND p.Status='1'
        ORDER BY p.PaymentDate DESC, p.id DESC");
}

function contractorPaymentAdvanceSave($conn, $contractorId, $amount, $paymentDate, $paymentMode, $referenceNo, $narration, $userId)
{
    contractorPaymentEnsureAdvanceTable($conn);
    $contractorId = (int) $contractorId;
    $userId = (int) $userId;
    $amount = round((float) $amount, 2);

    if ($contractorId <= 0 || $amount <= 0) {
        return ['ok' => false, 'message' => 'Invalid contractor or advance amount.'];
    }

    $contractor = contractorPaymentGetContractor($conn, $contractorId);
    if (!$contractor) {
        return ['ok' => false, 'message' => 'Contractor not found.'];
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $paymentDate)) {
        return ['ok' => false, 'message' => 'Invalid payment date.'];
    }

    $paymentMode = substr(trim((string) $paymentMode), 0, 50);
    $referenceNo = substr(trim((string) $referenceNo), 0, 100);
    $narration = substr(trim((string) $narration), 0, 500);
    $now = date('Y-m-d H:i:s');

    $paymentModeEsc = $conn->real_escape_string($paymentMode);
    $referenceNoEsc = $conn->real_escape_string($referenceNo);
    $narrationEsc = $conn->real_escape_string($narration);
    $paymentDateEsc = $conn->real_escape_string($paymentDate);

    $sql = "INSERT INTO tbl_contractor_advance_payment SET
        ContractorId='$contractorId',
        Amount='$amount',
        PaymentDate='$paymentDateEsc',
        PaymentMode='$paymentModeEsc',
        ReferenceNo='$referenceNoEsc',
        Narration='$narrationEsc',
        Status='1',
        CreatedBy='$userId',
        CreatedDate='$now'";

    if (!$conn->query($sql)) {
        return ['ok' => false, 'message' => 'Could not save advance payment.'];
    }

    return [
        'ok' => true,
        'message' => 'Advance payment saved successfully.',
        'id' => (int) mysqli_insert_id($conn),
    ];
}

function contractorPaymentAdvanceHistoryRows($conn, $contractorId)
{
    contractorPaymentEnsureAdvanceTable($conn);
    $contractorId = (int) $contractorId;
    if ($contractorId <= 0) {
        return [];
    }
    return getList("SELECT p.*, u.Fname AS CreatedByName
        FROM tbl_contractor_advance_payment p
        LEFT JOIN tbl_users u ON u.id = p.CreatedBy
        WHERE p.ContractorId='$contractorId' AND p.Status='1'
        ORDER BY p.PaymentDate DESC, p.id DESC");
}

function contractorCommissionScopeColumns()
{
    return [
        'Selection',
        'Field Survey',
        'Material Unloading',
        'Foundation',
        'Installation',
        'Inspection',
        'Inspection Approval',
        'Documentation',
    ];
}

function contractorCommissionFormatDate($raw)
{
    $raw = trim((string) $raw);
    if ($raw === '' || strpos($raw, '0000-00-00') === 0) {
        return '';
    }
    $ts = strtotime(str_replace('-', '/', $raw));
    if (!$ts) {
        return '';
    }
    return date('d/m/Y', $ts);
}

function contractorCommissionInspectionApprovalLabel($installationRow)
{
    if (!is_array($installationRow)) {
        return '—';
    }

    if (($installationRow['PoInspection'] ?? '') === 'Yes') {
        $date = contractorCommissionFormatDate($installationRow['PoInspectionDate'] ?? '');
        return $date !== '' ? 'Yes (' . $date . ')' : 'Yes';
    }

    if (($installationRow['DgmApproval'] ?? '') === 'Yes') {
        $date = contractorCommissionFormatDate($installationRow['DgmApprovalDate'] ?? '');
        return $date !== '' ? 'Approved (' . $date . ')' : 'Approved';
    }

    $pending = trim((string) ($installationRow['PoInspection'] ?? ''));
    if ($pending === 'No') {
        return 'No';
    }

    return 'Pending';
}

function contractorCommissionCustomerMetaMap($conn, array $customerIds)
{
    $customerIds = array_values(array_unique(array_filter(array_map('intval', $customerIds))));
    if (!$customerIds) {
        return [];
    }

    $idList = implode(',', $customerIds);
    $sql = "SELECT tu.id, tu.District, tu.ProjectId, tu.ProjectSubHeadId,
            tcm.Name AS ProjectName,
            tps.Name AS SubProjectName,
            tc.Name AS CapacityName,
            ti.PoInspection, ti.PoInspectionDate, ti.DgmApproval, ti.DgmApprovalDate
        FROM tbl_users tu
        LEFT JOIN tbl_common_master tcm ON tcm.id = tu.ProjectId
        LEFT JOIN tbl_project_sub_head tps ON tps.id = tu.ProjectSubHeadId
        LEFT JOIN tbl_common_master tc ON tc.id = tu.PumpCapacity
        LEFT JOIN tbl_installations ti ON ti.id = (
            SELECT ti2.id FROM tbl_installations ti2
            WHERE ti2.CustId = tu.id AND ti2.Type = 2
            ORDER BY ti2.id DESC LIMIT 1
        )
        WHERE tu.id IN ($idList)";
    $res = $conn->query($sql);
    $map = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $map[(int) $row['id']] = [
                'project_id' => (int) ($row['ProjectId'] ?? 0),
                'subhead_id' => (int) ($row['ProjectSubHeadId'] ?? 0),
                'project_name' => trim((string) ($row['ProjectName'] ?? '')),
                'sub_project_name' => trim((string) ($row['SubProjectName'] ?? '')),
                'capacity' => trim((string) ($row['CapacityName'] ?? '')),
                'district' => trim((string) ($row['District'] ?? '')),
                'inspection_approval' => contractorCommissionInspectionApprovalLabel($row),
            ];
        }
    }
    return $map;
}

function contractorBillingProjectsList($conn)
{
    return getList("SELECT cm.id, cm.Name,
            (SELECT COUNT(DISTINCT tc.CustId)
                FROM tbl_made_contractor_commision tc
                INNER JOIN tbl_users cust ON cust.id = tc.CustId
                WHERE cust.ProjectId = cm.id) AS site_count
        FROM tbl_common_master cm
        WHERE cm.Status = '1' AND cm.Roll = '24'
        ORDER BY site_count DESC, cm.Name ASC");
}

function contractorBillingSubHeadsList($conn, $projectId)
{
    $projectId = (int) $projectId;
    if ($projectId <= 0) {
        return [];
    }
    return getList("SELECT tps.id, tps.Name,
            (SELECT COUNT(DISTINCT tc.CustId)
                FROM tbl_made_contractor_commision tc
                INNER JOIN tbl_users cust ON cust.id = tc.CustId
                WHERE cust.ProjectId = '$projectId' AND cust.ProjectSubHeadId = tps.id) AS site_count
        FROM tbl_project_sub_head tps
        WHERE tps.UnderBy = '$projectId'
        ORDER BY site_count DESC, tps.Name ASC");
}

function contractorBillingGetProjectName($conn, $projectId)
{
    $projectId = (int) $projectId;
    if ($projectId <= 0) {
        return '';
    }
    $row = getRecord("SELECT Name FROM tbl_common_master WHERE id = '$projectId' LIMIT 1");
    return trim((string) ($row['Name'] ?? ''));
}

function contractorBillingGetSubHeadName($conn, $subheadId)
{
    $subheadId = (int) $subheadId;
    if ($subheadId <= 0) {
        return '';
    }
    $row = getRecord("SELECT Name FROM tbl_project_sub_head WHERE id = '$subheadId' LIMIT 1");
    return trim((string) ($row['Name'] ?? ''));
}

function contractorBillingContractorsByProjectSubHead($conn, $projectId, $subheadId)
{
    $projectId = (int) $projectId;
    $subheadId = (int) $subheadId;
    if ($projectId <= 0 || $subheadId <= 0) {
        return [];
    }

    $sql = "SELECT tc.ContractorId AS id,
            tu.Fname, tu.Lname,
            COUNT(DISTINCT tc.CustId) AS total_sites,
            COALESCE(SUM(tc.Amount), 0) AS total_commission
        FROM tbl_made_contractor_commision tc
        INNER JOIN tbl_users cust ON cust.id = tc.CustId
        INNER JOIN tbl_users tu ON tu.id = tc.ContractorId AND tu.Roll = '40'
        WHERE cust.ProjectId = '$projectId' AND cust.ProjectSubHeadId = '$subheadId'
        GROUP BY tc.ContractorId, tu.Fname, tu.Lname
        ORDER BY total_commission DESC, total_sites DESC, tu.Fname ASC, tu.Lname ASC";

    contractorPaymentEnsureTable($conn);
    $rows = getList($sql);
    foreach ($rows as &$row) {
        $cid = (int) ($row['id'] ?? 0);
        $commission = contractorPaymentAmountValue($row['total_commission'] ?? 0);
        $paid = contractorPaymentPaidTotal($conn, $cid);
        $advance = contractorPaymentAdvanceTotal($conn, $cid);
        $balance = $commission - $paid - $advance;
        if ($balance < 0) {
            $balance = 0.0;
        }
        $row['total_paid'] = $paid;
        $row['total_advance'] = $advance;
        $row['balance'] = $balance;
    }
    unset($row);

    return $rows;
}

function contractorBillingProjectSubHeadSummaryList($conn, $projectId = 0)
{
    contractorPaymentEnsureTable($conn);
    $projectId = (int) $projectId;
    $filterSql = '';
    if ($projectId > 0) {
        $filterSql = " AND cust.ProjectId = '$projectId'";
    }

    $sql = "SELECT proj.id AS project_id, proj.Name AS project_name,
            psh.id AS subhead_id, psh.Name AS subhead_name,
            COUNT(DISTINCT tc.CustId) AS total_sites,
            COUNT(DISTINCT tc.ContractorId) AS total_contractors,
            COALESCE(SUM(tc.Amount), 0) AS total_billing
        FROM tbl_made_contractor_commision tc
        INNER JOIN tbl_users cust ON cust.id = tc.CustId
        INNER JOIN tbl_common_master proj ON proj.id = cust.ProjectId AND proj.Status = '1' AND proj.Roll = '24'
        INNER JOIN tbl_project_sub_head psh ON psh.id = cust.ProjectSubHeadId
        INNER JOIN tbl_users tu ON tu.id = tc.ContractorId AND tu.Roll = '40'
        WHERE 1=1 $filterSql
        GROUP BY proj.id, proj.Name, psh.id, psh.Name
        HAVING total_billing > 0
        ORDER BY total_billing DESC, total_sites DESC, proj.Name ASC, psh.Name ASC";

    $rows = getList($sql);
    if (!is_array($rows)) {
        $rows = [];
    }

    foreach ($rows as &$row) {
        $pid = (int) ($row['project_id'] ?? 0);
        $sid = (int) ($row['subhead_id'] ?? 0);
        $summary = contractorBillingSubHeadPaymentSummary($conn, $pid, $sid);
        $row['total_sites'] = (int) ($row['total_sites'] ?? 0);
        $row['total_contractors'] = (int) ($row['total_contractors'] ?? 0);
        $row['total_billing'] = $summary['total_commission'];
        $row['total_advance'] = $summary['total_advance'];
        $row['total_paid'] = $summary['total_paid'];
        $row['total_balance'] = $summary['balance'];
    }
    unset($row);

    return $rows;
}

function contractorBillingSubHeadPaymentSummary($conn, $projectId, $subheadId)
{
    contractorPaymentEnsureTable($conn);
    $projectId = (int) $projectId;
    $subheadId = (int) $subheadId;
    if ($projectId <= 0 || $subheadId <= 0) {
        return [
            'total_commission' => 0.0,
            'total_paid' => 0.0,
            'total_advance' => 0.0,
            'balance' => 0.0,
        ];
    }

    $commissionRow = getRecord("SELECT COALESCE(SUM(tc.Amount), 0) AS total
        FROM tbl_made_contractor_commision tc
        INNER JOIN tbl_users cust ON cust.id = tc.CustId
        WHERE cust.ProjectId = '$projectId' AND cust.ProjectSubHeadId = '$subheadId'");
    $totalCommission = contractorPaymentAmountValue($commissionRow['total'] ?? 0);

    $contractorRows = getList("SELECT DISTINCT tc.ContractorId AS id
        FROM tbl_made_contractor_commision tc
        INNER JOIN tbl_users cust ON cust.id = tc.CustId
        WHERE cust.ProjectId = '$projectId' AND cust.ProjectSubHeadId = '$subheadId'");

    $totalPaid = 0.0;
    $totalAdvance = 0.0;
    foreach ($contractorRows as $row) {
        $cid = (int) ($row['id'] ?? 0);
        if ($cid <= 0) {
            continue;
        }
        $totalPaid += contractorPaymentPaidTotal($conn, $cid);
        $totalAdvance += contractorPaymentAdvanceTotal($conn, $cid);
    }

    $balance = $totalCommission - $totalPaid - $totalAdvance;
    if ($balance < 0) {
        $balance = 0.0;
    }

    return [
        'total_commission' => $totalCommission,
        'total_paid' => $totalPaid,
        'total_advance' => $totalAdvance,
        'balance' => $balance,
    ];
}

function contractorCommissionPivotByCustomer($conn, $contractorId, $projectId = 0, $subheadId = 0)
{
    $contractorId = (int) $contractorId;
    $projectId = (int) $projectId;
    $subheadId = (int) $subheadId;
    $defaultScopes = contractorCommissionScopeColumns();
    $rowsByCustomer = [];
    $extraScopes = [];

    if ($contractorId <= 0) {
        return [
            'rows' => [],
            'scopes' => $defaultScopes,
            'scopeTotals' => array_fill_keys($defaultScopes, 0.0),
            'grandTotal' => 0.0,
        ];
    }

    $filterSql = '';
    if ($projectId > 0) {
        $filterSql .= " AND cust.ProjectId = '$projectId'";
    }
    if ($subheadId > 0) {
        $filterSql .= " AND cust.ProjectSubHeadId = '$subheadId'";
    }

    $sql = "SELECT tc.CustId, tc.ScopeOfWork, tc.Amount, tu.Fname, tu.BeneficiaryId
        FROM tbl_made_contractor_commision tc
        INNER JOIN tbl_users tu ON tu.id = tc.CustId
        INNER JOIN tbl_users cust ON cust.id = tc.CustId
        WHERE tc.ContractorId='$contractorId' $filterSql
        ORDER BY tu.BeneficiaryId ASC, tc.Roll ASC, tc.id ASC";
    $res = $conn->query($sql);

    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $custId = (int) ($row['CustId'] ?? 0);
            $scope = trim((string) ($row['ScopeOfWork'] ?? ''));
            if ($custId <= 0 || $scope === '') {
                continue;
            }

            if (!isset($rowsByCustomer[$custId])) {
                $rowsByCustomer[$custId] = [
                    'cust_id' => $custId,
                    'beneficiary_id' => (string) ($row['BeneficiaryId'] ?? ''),
                    'customer_name' => (string) ($row['Fname'] ?? ''),
                    'project_id' => 0,
                    'subhead_id' => 0,
                    'project_name' => '',
                    'sub_project_name' => '',
                    'capacity' => '',
                    'district' => '',
                    'inspection_approval' => '—',
                    'scopes' => [],
                    'total' => 0.0,
                ];
            }

            $amount = contractorPaymentAmountValue($row['Amount'] ?? 0);
            if (!isset($rowsByCustomer[$custId]['scopes'][$scope])) {
                $rowsByCustomer[$custId]['scopes'][$scope] = 0.0;
            }
            $rowsByCustomer[$custId]['scopes'][$scope] += $amount;
            $rowsByCustomer[$custId]['total'] += $amount;

            if (!in_array($scope, $defaultScopes, true)) {
                $extraScopes[$scope] = true;
            }
        }
    }

    if ($rowsByCustomer) {
        $metaMap = contractorCommissionCustomerMetaMap($conn, array_keys($rowsByCustomer));
        foreach ($rowsByCustomer as $custId => &$customerRow) {
            if (!isset($metaMap[$custId])) {
                continue;
            }
            $customerRow['project_id'] = (int) ($metaMap[$custId]['project_id'] ?? 0);
            $customerRow['subhead_id'] = (int) ($metaMap[$custId]['subhead_id'] ?? 0);
            $customerRow['project_name'] = $metaMap[$custId]['project_name'] !== ''
                ? $metaMap[$custId]['project_name'] : '—';
            $customerRow['sub_project_name'] = $metaMap[$custId]['sub_project_name'] !== ''
                ? $metaMap[$custId]['sub_project_name'] : '—';
            $customerRow['capacity'] = $metaMap[$custId]['capacity'] !== ''
                ? $metaMap[$custId]['capacity'] : '—';
            $customerRow['district'] = $metaMap[$custId]['district'] !== ''
                ? $metaMap[$custId]['district'] : '—';
            $customerRow['inspection_approval'] = $metaMap[$custId]['inspection_approval'];
        }
        unset($customerRow);
    }

    usort($rowsByCustomer, function ($a, $b) {
        $projectCmp = strcasecmp((string) $a['project_name'], (string) $b['project_name']);
        if ($projectCmp !== 0) {
            return $projectCmp;
        }
        $subCmp = strcasecmp((string) $a['sub_project_name'], (string) $b['sub_project_name']);
        if ($subCmp !== 0) {
            return $subCmp;
        }
        return strcasecmp((string) $a['beneficiary_id'], (string) $b['beneficiary_id']);
    });

    $scopeColumns = $defaultScopes;
    foreach (array_keys($extraScopes) as $scopeName) {
        $scopeColumns[] = $scopeName;
    }

    $scopeTotals = [];
    $grandTotal = 0.0;
    foreach ($scopeColumns as $scopeName) {
        $scopeTotals[$scopeName] = 0.0;
    }

    $rows = [];
    foreach ($rowsByCustomer as $customerRow) {
        $rows[] = $customerRow;
        $grandTotal += $customerRow['total'];
        foreach ($scopeColumns as $scopeName) {
            $scopeTotals[$scopeName] += (float) ($customerRow['scopes'][$scopeName] ?? 0);
        }
    }

    return [
        'rows' => $rows,
        'scopes' => $scopeColumns,
        'scopeTotals' => $scopeTotals,
        'grandTotal' => $grandTotal,
    ];
}

function contractorCommissionScopeCell($amount)
{
    return contractorPaymentFormatMoney($amount);
}
