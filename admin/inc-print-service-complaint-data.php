<?php

function printServiceComplaintDisplay($value, $fallback = '—')
{
    if ($value === null || $value === '' || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
        return $fallback;
    }
    return $value;
}

function printServiceComplaintActionField($latestAction, $field, $fallback = '')
{
    $val = trim((string) (($latestAction ?? [])[$field] ?? ''));
    if ($val === '') {
        $val = trim((string) $fallback);
    }
    return printServiceComplaintDisplay($val);
}

function printServiceComplaintFormatDate($value, $withTime = false)
{
    if ($value === null || $value === '' || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
        return '—';
    }
    $ts = strtotime($value);
    if ($ts === false) {
        return $value;
    }
    return $withTime ? date('d-m-Y h:i A', $ts) : date('d-m-Y', $ts);
}

function printServiceComplaintFormatBookDate($value)
{
    if ($value === null || $value === '' || $value === '0000-00-00') {
        return '—';
    }
    $ts = strtotime($value);
    if ($ts === false) {
        return $value;
    }
    return date('d-M-Y h:i A', $ts);
}

function printServiceComplaintResolveProblems(array $complaint)
{
    if (($complaint['ServiceType'] ?? '') === 'Insurance' && !empty($complaint['InsuranceComplaint'])) {
        $row = getRecord("SELECT Name FROM tbl_common_master WHERE id='" . (int) $complaint['InsuranceComplaint'] . "'");
        return $row['Name'] ?? '';
    }
    $problem = trim((string) ($complaint['Problem'] ?? ''));
    if ($problem !== '') {
        return str_replace(',', ', ', $problem);
    }
    return trim((string) ($complaint['Message'] ?? ''));
}

function printServiceComplaintResolveCallType(array $complaint)
{
    if (!empty($complaint['IssueName'])) {
        return $complaint['IssueName'];
    }
    if (!empty($complaint['RelatedIssue'])) {
        return $complaint['RelatedIssue'];
    }
    if (!empty($complaint['Priority'])) {
        return $complaint['Priority'];
    }
    return $complaint['ServiceType'] ?? '';
}

function printServiceComplaintResolveComplaintType(array $complaint, $installation)
{
    if (!empty($installation['WarrantyReg'])) {
        return $installation['WarrantyReg'] === 'Yes' ? 'Under Warranty' : 'After Warranty';
    }
    if (!empty($complaint['SystemWorking'])) {
        return $complaint['SystemWorking'];
    }
    return '';
}

function printServiceComplaintResolveFaultType(array $complaint)
{
    $parts = array_filter([
        trim((string) ($complaint['VfdProblem'] ?? '')),
        trim((string) ($complaint['PumpProblem'] ?? '')),
        trim((string) ($complaint['MotorProblem'] ?? '')),
    ]);
    return implode(', ', $parts);
}

function printServiceComplaintResolveAddress(array $complaint)
{
    if (!empty($complaint['Address'])) {
        return $complaint['Address'];
    }
    $parts = array_filter([
        trim((string) ($complaint['Village'] ?? '')),
        trim((string) ($complaint['District'] ?? '')),
        trim((string) ($complaint['Taluka'] ?? '')),
    ]);
    return implode(', ', $parts);
}

/**
 * Serial from customer dispatch (tbl_sell_products), same logic as dispatch-customer-csv-report.php.
 */
function printServiceComplaintResolveSellProductSerial($custId, $productKeyword)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return '';
    }

    $keyword = trim((string) $productKeyword);
    if ($keyword === '') {
        return '';
    }

    $row = getRecord("SELECT SerialNo FROM tbl_sell_products
        WHERE SerialNo != 'N/A'
            AND ProductId != 0
            AND UserId = '$custId'
            AND ProductName LIKE '%$keyword%'
        LIMIT 1");

    return trim((string) ($row['SerialNo'] ?? ''));
}

function printServiceComplaintResolvePumpSetItem($custId)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return null;
    }

    $pumpSql = "SELECT tsp.ProductName, tsp.SerialNo, ts.OemVedName
        FROM tbl_sell_products tsp
        LEFT JOIN tbl_stocks ts ON ts.SerialNo = tsp.SerialNo
            AND ts.SellType = 'Purchase'
            AND ts.ProdType IN ('1', '2')
        WHERE tsp.UserId = '$custId'
            AND tsp.SerialNo IS NOT NULL
            AND tsp.SerialNo != ''
            AND tsp.SerialNo != 'N/A'
            AND tsp.ProductName LIKE '%PUMPSET%'
        ORDER BY tsp.id DESC
        LIMIT 1";
    $row = getRecord($pumpSql);
    if (!empty($row['SerialNo'])) {
        return $row;
    }

    return getRecord("SELECT tsp.ProductName, tsp.SerialNo, ts.OemVedName
        FROM tbl_sell_products tsp
        INNER JOIN tbl_sell tsell ON tsell.id = tsp.SellId
        LEFT JOIN tbl_stocks ts ON ts.SerialNo = tsp.SerialNo
            AND ts.SellType = 'Purchase'
            AND ts.ProdType IN ('1', '2')
        WHERE tsell.CustId = '$custId'
            AND tsp.SerialNo IS NOT NULL
            AND tsp.SerialNo != ''
            AND tsp.SerialNo != 'N/A'
            AND tsp.ProductName LIKE '%PUMPSET%'
        ORDER BY tsp.id DESC
        LIMIT 1");
}

function printServiceComplaintResolveProduct(array $complaint, $custId = 0)
{
    $pump = printServiceComplaintResolvePumpSetItem((int) $custId);
    if ($pump) {
        $name = trim((string) ($pump['ProductName'] ?? ''));
        $serial = trim((string) ($pump['SerialNo'] ?? ''));
        if ($name !== '' && $serial !== '') {
            return $name . ' - ' . $serial;
        }
        if ($name !== '') {
            return $name;
        }
        if ($serial !== '') {
            return $serial;
        }
    }

    $system = trim((string) ($complaint['ServiceSystem'] ?? ''));
    $registration = trim((string) ($complaint['RegistrationId'] ?? ''));
    $parts = array_filter([$system, $registration]);
    return implode(' - ', $parts);
}

function printServiceComplaintResolveBrandOem($custId, $latestAction = [])
{
    global $conn;

    $brandLabel = 'V-TECH';
    $oem = '';

    $pump = printServiceComplaintResolvePumpSetItem((int) $custId);
    if ($pump) {
        $oem = trim((string) ($pump['OemVedName'] ?? ''));
    }

    if ($oem === '' && !empty($latestAction['SerialNo'])) {
        $serial = trim((string) $latestAction['SerialNo']);
        if ($serial !== '' && strcasecmp($serial, 'N/A') !== 0) {
            $serialEsc = mysqli_real_escape_string($conn, $serial);
            $stock = getRecord("SELECT OemVedName FROM tbl_stocks
                WHERE SerialNo = '$serialEsc'
                AND SellType = 'Purchase'
                AND ProdType IN ('1', '2')
                ORDER BY id DESC
                LIMIT 1");
            $oem = trim((string) ($stock['OemVedName'] ?? ''));
        }
    }

    if ($oem === '0') {
        $oem = '';
    }

    if ($oem !== '') {
        return $brandLabel . ' / ' . $oem;
    }

    return $brandLabel;
}

function printServiceComplaintResolveEngineer(array $complaint, $latestAction, $assignedEngineer)
{
    if (!empty($latestAction['EnggName'])) {
        return $latestAction['EnggName'];
    }
    if (!empty($assignedEngineer['Fname'])) {
        return $assignedEngineer['Fname'];
    }
    return '';
}

function printServiceComplaintResolveJobStatus(array $complaint, $latestAction)
{
    if (!empty($latestAction['ClainStatus'])) {
        return $latestAction['ClainStatus'];
    }
    $status = trim((string) ($complaint['ClainStatus'] ?? ''));
    if ($status === 'Issue Solved') {
        return 'Closed';
    }
    return $status;
}

function printServiceComplaintCollectPhotos($complaintId)
{
    $photos = [];
    $rows = getList("SELECT Photo, Lattitude, Longitude, ServiceDate, CreatedDate
        FROM tbl_complaint_engg_actions
        WHERE CompId='" . (int) $complaintId . "' AND Photo<>'' AND Photo IS NOT NULL
        ORDER BY id ASC");
    foreach ($rows as $row) {
        foreach (explode(',', (string) $row['Photo']) as $file) {
            $file = trim($file);
            if ($file === '') {
                continue;
            }
            $photos[] = [
                'file' => $file,
                'latitude' => $row['Lattitude'] ?? '',
                'longitude' => $row['Longitude'] ?? '',
                'date' => $row['ServiceDate'] ?: $row['CreatedDate'],
            ];
        }
    }
    if (empty($photos) && !empty($complaintId)) {
        $complaint = getRecord("SELECT Photos FROM tbl_service_complaint WHERE id='" . (int) $complaintId . "'");
        if (!empty($complaint['Photos'])) {
            foreach (explode(',', (string) $complaint['Photos']) as $file) {
                $file = trim($file);
                if ($file !== '') {
                    $photos[] = ['file' => $file, 'latitude' => '', 'longitude' => '', 'date' => ''];
                }
            }
        }
    }
    return $photos;
}

function printServiceComplaintDefaultCompanyHeader()
{
    return [
        'name' => 'VTECH',
        'gstin' => '27AAHCV1408DIZU',
        'address' => 'Shub Vinayak, Kumbharpura, Badkas Chowk, Small Aychit Mandir, Mahal, Nagpur-440032 (MH)',
        'email' => 'vtech.enquiry@gmail.com',
        'phone' => '9923870005',
        'phone2' => '',
        'logo' => 'logo.jpg',
        'logo_is_upload' => false,
    ];
}

function printServiceComplaintResolveCompanyHeader($custId)
{
    $defaults = printServiceComplaintDefaultCompanyHeader();
    $custId = (int) $custId;
    if ($custId <= 0) {
        return $defaults;
    }

    $row = getRecord("SELECT tu.CompId, comp.Fname AS CompName, comp.GstNo, comp.Address,
            comp.EmailId, comp.Phone, comp.Phone2, comp.Photo
        FROM tbl_users tu
        LEFT JOIN tbl_users comp ON comp.id = tu.CompId
        WHERE tu.id = '$custId'
        LIMIT 1");

    if (empty($row) || empty($row['CompId'])) {
        return $defaults;
    }

    $phone = trim((string) ($row['Phone'] ?? ''));
    $phone2 = trim((string) ($row['Phone2'] ?? ''));
    $logo = trim((string) ($row['Photo'] ?? ''));

    return [
        'name' => trim((string) ($row['CompName'] ?? '')) ?: $defaults['name'],
        'gstin' => trim((string) ($row['GstNo'] ?? '')) ?: $defaults['gstin'],
        'address' => trim((string) ($row['Address'] ?? '')) ?: $defaults['address'],
        'email' => trim((string) ($row['EmailId'] ?? '')) ?: $defaults['email'],
        'phone' => $phone !== '' ? $phone : $defaults['phone'],
        'phone2' => $phone2,
        'logo' => $logo !== '' ? $logo : $defaults['logo'],
        'logo_is_upload' => $logo !== '',
    ];
}

function printServiceComplaintCompanyCareNo(array $companyHeader)
{
    $parts = array_filter([
        trim((string) ($companyHeader['phone'] ?? '')),
        trim((string) ($companyHeader['phone2'] ?? '')),
    ]);
    return implode(', ', $parts);
}

function printServiceComplaintResolveVisitDate($latestAction, $complaint)
{
    if (!empty($latestAction['CreatedDate']) && $latestAction['CreatedDate'] !== '0000-00-00 00:00:00') {
        return printServiceComplaintFormatDate($latestAction['CreatedDate'], true);
    }
    if (!empty($latestAction['ServiceDate']) && $latestAction['ServiceDate'] !== '0000-00-00') {
        return printServiceComplaintFormatDate($latestAction['ServiceDate'], true);
    }
    if (!empty($complaint['ComplaintDate']) && $complaint['ComplaintDate'] !== '0000-00-00') {
        return printServiceComplaintFormatDate($complaint['ComplaintDate'], false);
    }

    return '—';
}

function printServiceComplaintFirstNonEmptyValue(array $candidates)
{
    foreach ($candidates as $value) {
        $value = trim((string) $value);
        if ($value !== '' && $value !== '0000-00-00' && $value !== '0000-00-00 00:00:00') {
            return $value;
        }
    }
    return '';
}

function printServiceComplaintResolveInstallationDate(array $complaint, $installation)
{
    return printServiceComplaintFirstNonEmptyValue([
        $complaint['InstallationDate'] ?? '',
        ($installation ?: [])['InstallationDate'] ?? '',
        $complaint['CustInstallationDate'] ?? '',
    ]);
}

function printServiceComplaintResolveSourceAcDc(array $complaint)
{
    return printServiceComplaintFirstNonEmptyValue([
        $complaint['AcDc'] ?? '',
        $complaint['CustAcDc'] ?? '',
    ]);
}

function printServiceComplaintResolvePumpHead(array $complaint)
{
    return printServiceComplaintFirstNonEmptyValue([
        $complaint['Depth'] ?? '',
        $complaint['CustFieldPumpHead'] ?? '',
        $complaint['CustTelPumpHead'] ?? '',
        $complaint['CustPumpHeadName'] ?? '',
    ]);
}

function printServiceComplaintLoad($complaintId)
{
    $complaintId = (int) $complaintId;
    if ($complaintId <= 0) {
        return null;
    }

    $complaint = getRecord("SELECT tsc.*, ti.Name AS IssueName,
        tu.PumpCapacity AS PumpCapacityId, tcm.Name AS PumpCapacityName,
        tu.AcDc AS CustAcDc, tu.InstallationDate AS CustInstallationDate,
        tu.TelPumpHead AS CustTelPumpHead, tu.FieldPumpHead AS CustFieldPumpHead,
        tcm_ph.Name AS CustPumpHeadName
        FROM tbl_service_complaint tsc
        LEFT JOIN tbl_issues ti ON ti.id = tsc.Issue
        LEFT JOIN tbl_users tu ON tu.id = tsc.CustId
        LEFT JOIN tbl_common_master tcm ON tcm.id = tu.PumpCapacity
        LEFT JOIN tbl_common_master tcm_ph ON tcm_ph.id = tu.PumpHead
        WHERE tsc.id = '$complaintId'");
    if (!$complaint) {
        return null;
    }

    $installation = null;
    if (!empty($complaint['CustId'])) {
        $installation = getRecord("SELECT * FROM tbl_installations WHERE CustId='" . (int) $complaint['CustId'] . "' ORDER BY id DESC LIMIT 1");
    }

    $latestAction = getRecord("SELECT cea.*, tu.Fname AS EnggName
        FROM tbl_complaint_engg_actions cea
        LEFT JOIN tbl_users tu ON tu.id = cea.EnggId
        WHERE cea.CompId = '$complaintId'
        ORDER BY cea.id DESC
        LIMIT 1");

    $assignedEngineer = null;
    if (!empty($complaint['EnggAssignId'])) {
        $assignedEngineer = getRecord("SELECT Fname FROM tbl_users WHERE id='" . (int) $complaint['EnggAssignId'] . "'");
    }

    $visitDate = printServiceComplaintResolveVisitDate($latestAction ?: [], $complaint);

    $bookDate = printServiceComplaintFormatBookDate($complaint['CreatedDate'] ?? '');
    if ($bookDate === '—' && !empty($complaint['ComplaintDate'])) {
        $bookDate = printServiceComplaintFormatBookDate($complaint['ComplaintDate']);
    }

    $pumpCapacity = $complaint['PumpCapacityName'] ?? '';
    if ($pumpCapacity === '' && !empty($installation['PumpCapacity'])) {
        $cap = getRecord("SELECT Name FROM tbl_common_master WHERE id='" . (int) $installation['PumpCapacity'] . "'");
        $pumpCapacity = $cap['Name'] ?? '';
    }

    $companyHeader = printServiceComplaintResolveCompanyHeader((int) ($complaint['CustId'] ?? 0));
    $action = $latestAction ?: [];
    $custId = (int) ($complaint['CustId'] ?? 0);
    $pumpSetSerial = printServiceComplaintResolveSellProductSerial($custId, 'PUMPSET');
    $controllerSerial = printServiceComplaintResolveSellProductSerial($custId, 'CONTROLLER');

    return [
        'complaint' => $complaint,
        'installation' => $installation ?: [],
        'latestAction' => $latestAction ?: [],
        'assignedEngineer' => $assignedEngineer ?: [],
        'companyHeader' => $companyHeader,
        'photos' => printServiceComplaintCollectPhotos($complaintId),
        'workOrderNo' => printServiceComplaintDisplay($complaint['TicketNo'] ?? '', ''),
        'beneficiaryId' => printServiceComplaintDisplay($complaint['BeneficiaryId'] ?? $complaint['RegistrationId'] ?? ''),
        'visitDate' => $visitDate,
        'customerName' => printServiceComplaintDisplay($complaint['CustName'] ?? ''),
        'contactNo' => printServiceComplaintDisplay($complaint['CellNo'] ?? ''),
        'alternateNo' => printServiceComplaintDisplay($complaint['CellNo2'] ?? ''),
        'address' => printServiceComplaintDisplay(printServiceComplaintResolveAddress($complaint)),
        'bookDate' => $bookDate,
        'callType' => printServiceComplaintDisplay(printServiceComplaintResolveCallType($complaint)),
        'engineer' => printServiceComplaintDisplay(printServiceComplaintResolveEngineer($complaint, $latestAction ?: [], $assignedEngineer ?: [])),
        'jobStatus' => printServiceComplaintDisplay(printServiceComplaintResolveJobStatus($complaint, $latestAction ?: [])),
        'natureOfComplaint' => printServiceComplaintDisplay(printServiceComplaintResolveProblems($complaint)),
        'brand' => printServiceComplaintResolveBrandOem((int) ($complaint['CustId'] ?? 0), $latestAction ?: []),
        'category' => 'Solar',
        'product' => printServiceComplaintDisplay(printServiceComplaintResolveProduct($complaint, (int) ($complaint['CustId'] ?? 0))),
        'pumpCapacity' => printServiceComplaintDisplay($pumpCapacity),
        'installationDate' => printServiceComplaintFormatDate(printServiceComplaintResolveInstallationDate($complaint, $installation ?: [])),
        'arrayVoltage' => printServiceComplaintActionField($action, 'ArrayVoltage'),
        'arrayCurrent' => printServiceComplaintActionField($action, 'ArrayCurrent'),
        'weather' => printServiceComplaintActionField($action, 'Weather', $complaint['SystemStatus'] ?? ''),
        'rms' => printServiceComplaintActionField($action, 'RmsNetwork', $complaint['Rms'] ?? ''),
        'controllerSrNo' => printServiceComplaintDisplay($controllerSerial !== '' ? $controllerSerial : ($complaint['RecentVfdNo'] ?? '')),
        'motorSrNo' => printServiceComplaintDisplay($pumpSetSerial !== '' ? $pumpSetSerial : ($complaint['RecentMotorNo'] ?? '')),
        'pumpSrNo' => printServiceComplaintDisplay($pumpSetSerial !== '' ? $pumpSetSerial : ($complaint['RecentPumpNo'] ?? '')),
        'pumpLocation' => printServiceComplaintActionField($action, 'PumpLocation', $complaint['Surface'] ?? ''),
        'pumpInstalledDepth' => printServiceComplaintActionField($action, 'PumpInstalledDepth'),
        'waterLevelHead' => printServiceComplaintActionField($action, 'WaterLevelHead'),
        'loweringOfPump' => printServiceComplaintActionField($action, 'LoweringOfPump'),
        'complaintType' => printServiceComplaintDisplay(printServiceComplaintResolveComplaintType($complaint, $installation ?: [])),
        'sourceAcDc' => printServiceComplaintDisplay(printServiceComplaintResolveSourceAcDc($complaint)),
        'pumpHead' => printServiceComplaintDisplay(printServiceComplaintResolvePumpHead($complaint)),
        'pipeLength' => printServiceComplaintActionField($action, 'PipeLength'),
        'pipeSizeInch' => printServiceComplaintActionField($action, 'PipeSizeInch'),
        'waterStatus' => printServiceComplaintActionField($action, 'WaterStatus', $complaint['WaterSource'] ?? ''),
        'rpmStatus' => printServiceComplaintActionField($action, 'RpmStatus'),
        'cableLength' => printServiceComplaintActionField($action, 'CableLengthPole'),
        'faultType' => printServiceComplaintActionField($action, 'FaultType', printServiceComplaintResolveFaultType($complaint)),
        'motorResistance' => printServiceComplaintActionField($action, 'MotorResistance'),
        'actualProblem' => printServiceComplaintActionField($action, 'ActualProblemFound', $complaint['RecentProblem'] ?? ''),
        'providedSolution' => printServiceComplaintActionField($action, 'ProvidedSolution', $action['Remark'] ?? ''),
        'fieldObservations' => printServiceComplaintActionField($action, 'FieldObservations', $action['Specify'] ?? ($complaint['Message'] ?? '')),
        'actionTaken' => printServiceComplaintActionField($action, 'ActionTaken', $action['Remark'] ?? ''),
        'customerRemarks' => printServiceComplaintActionField($action, 'CustomerRemarks', $complaint['Remark'] ?? ''),
    ];
}
