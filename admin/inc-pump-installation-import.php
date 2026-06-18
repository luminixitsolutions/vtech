<?php

require_once __DIR__ . '/inc-work-order-customer.php';
require_once __DIR__ . '/inc-insurance-site.php';

function pumpInstallImportMaxFileBytes()
{
    return 5 * 1024 * 1024;
}

function pumpInstallImportSampleHeaders()
{
    return array(
        'Beneficiary ID',
        'Customer Name',
        'Contact No',
        'Email',
        'Address',
        'Latitude',
        'Longitude',
        'Taluka',
        'Village',
        'District',
        'Pump Capacity',
        'Google Drive Link',
        'Installation Date',
        'IMEI No',
        'Water Outflow',
        'Farmer NOC',
        'Farmer Video',
        'Data Upload',
        'PO Inspection',
        'Insurance Approval',
        'File In Hand',
        'RMS Integration',
        'Work Order Done',
        'Foundation Done',
        'Documentation Done',
        'DCR Verification',
        'JV Invoice Number',
        'JV Invoice Date',
        'Warranty Registration',
        'Payment Released 90%',
        'Payment Released 10%',
        'Sent To HO',
        'PO Note Putup Approval',
        'DGM Approval',
        'Bill Submit To Circle Office',
    );
}

function pumpInstallImportNormalizeHeader($header)
{
    $header = (string) $header;
    $header = preg_replace('/[\x{FEFF}\x00-\x1F}]/u', '', $header);
    $header = strtolower(trim(preg_replace('/\s+/', ' ', $header)));
    $header = rtrim($header, '.:');

    $map = array(
        'beneficiary id' => 'beneficiary_id',
        'beneficiaryid' => 'beneficiary_id',
        'customer name' => 'customer_name',
        'cust name' => 'customer_name',
        'name' => 'customer_name',
        'contact no' => 'contact_no',
        'cell no' => 'contact_no',
        'mobile' => 'contact_no',
        'phone' => 'contact_no',
        'email' => 'email',
        'email id' => 'email',
        'address' => 'address',
        'latitude' => 'latitude',
        'lattitude' => 'latitude',
        'longitude' => 'longitude',
        'taluka' => 'taluka',
        'village' => 'village',
        'district' => 'district',
        'pump capacity' => 'pump_capacity',
        'google drive link' => 'drive_link',
        'drive link' => 'drive_link',
        'installation date' => 'installation_date',
        'imei no' => 'imei_no',
        'imei' => 'imei_no',
        'water outflow' => 'water_outflow',
        'farmer noc' => 'farmer_noc',
        'farmer video' => 'farmer_video',
        'data upload' => 'data_upload',
        'po inspection' => 'po_inspection',
        'insurance approval' => 'insurance_approval',
        'file in hand' => 'file_in_hand',
        'rms integration' => 'rms_integration',
        'work order done' => 'work_order_done',
        'foundation done' => 'foundation',
        'documentation done' => 'documentation',
        'dcr verification' => 'dcr_verify',
        'jv invoice number' => 'jv_inv_no',
        'jv invoice date' => 'jv_inv_date',
        'warranty registration' => 'warranty_reg',
        'payment released 90%' => 'payment_90',
        'payment released 10%' => 'payment_10',
        'sent to ho' => 'sent_to_ho',
        'po note putup approval' => 'po_approval',
        'dgm approval' => 'dgm_approval',
        'bill submit to circle office' => 'circle_office_status',
        'inspection discrepancy' => 'inspection_discrepancy',
        'forward to payment' => 'forward_to_payment',
        'payment date' => 'payment_date',
        'data upload date' => 'data_upload_date',
        'po inspection date' => 'po_inspection_date',
        'insurance approval date' => 'insurance_approval_date',
        'file in hand date' => 'file_in_hand_date',
        'rms integration date' => 'rms_integration_date',
        'work order date' => 'work_order_done_date',
        'farmer noc date' => 'farmer_noc_date',
        'farmer video date' => 'farmer_video_date',
        'dcr verification date' => 'dcr_verify_date',
        'warranty till date' => 'warranty_reg_date',
    );

    return isset($map[$header]) ? $map[$header] : null;
}

function pumpInstallImportBuildColumnMap($row)
{
    $map = array();
    $row = insuranceImportNormalizeRow($row);
    foreach ($row as $index => $cell) {
        $key = pumpInstallImportNormalizeHeader($cell);
        if ($key !== null) {
            $map[$key] = (int) $index;
        }
    }

    return $map;
}

function pumpInstallImportColumnMapScore($map)
{
    $score = 0;
    foreach (array('beneficiary_id', 'customer_name') as $key) {
        if (isset($map[$key])) {
            $score++;
        }
    }

    return $score;
}

function pumpInstallImportIsHeaderRow($row)
{
    return pumpInstallImportColumnMapScore(pumpInstallImportBuildColumnMap($row)) >= 2;
}

function pumpInstallImportDetectColumnMap($rows)
{
    $bestMap = null;
    $bestScore = 0;

    if (is_array($rows)) {
        foreach (array_slice($rows, 0, 10) as $row) {
            $candidate = pumpInstallImportBuildColumnMap($row);
            $score = pumpInstallImportColumnMapScore($candidate);
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMap = $candidate;
            }
        }
    }

    return is_array($bestMap) ? $bestMap : array();
}

function pumpInstallImportFieldValue($row, $columnMap, $key)
{
    if (!isset($columnMap[$key])) {
        return '';
    }

    $row = insuranceImportNormalizeRow($row);
    $index = (int) $columnMap[$key];
    if (!array_key_exists($index, $row) || $row[$index] === null) {
        return '';
    }

    $value = $row[$index];
    if ($value instanceof DateTimeInterface) {
        return $value->format('Y-m-d');
    }

    if (is_int($value) || is_float($value)) {
        return trim(rtrim((string) $value, '.0'));
    }

    return trim((string) $value);
}

function pumpInstallImportDateValue($row, $columnMap, $key)
{
    $raw = pumpInstallImportFieldValue($row, $columnMap, $key);
    if ($raw === '') {
        return '';
    }

    $parsed = parseInsuranceDateForDb($raw);
    return $parsed !== '' ? $parsed : $raw;
}

function pumpInstallImportYesNo($value, $default = 'No')
{
    $value = strtolower(trim((string) $value));
    if ($value === '') {
        return $default;
    }
    if (in_array($value, array('yes', 'y', '1', 'true', 'done'), true)) {
        return 'Yes';
    }
    if (in_array($value, array('no', 'n', '0', 'false'), true)) {
        return 'No';
    }

    return $default;
}

function pumpInstallImportWarrantyTillDate($installationDate)
{
    $installationDate = trim((string) $installationDate);
    if ($installationDate === '' || $installationDate === '0000-00-00') {
        return '';
    }

    $ts = strtotime($installationDate);
    if ($ts === false) {
        return '';
    }

    return date('Y-m-d', strtotime('+5 years', $ts));
}

function pumpInstallImportResolveWarranty($installStatus, $installationDate, $warrantyReg, $warrantyRegDate)
{
    if (pumpInstallImportYesNo($installStatus, 'Yes') !== 'Yes') {
        return array('No', '');
    }

    $warrantyReg = pumpInstallImportYesNo($warrantyReg, 'Yes');
    $warrantyRegDate = trim((string) $warrantyRegDate);
    if ($warrantyRegDate === '' || $warrantyRegDate === '0000-00-00') {
        $warrantyRegDate = pumpInstallImportWarrantyTillDate($installationDate);
    }

    if ($warrantyReg === 'Yes' && $warrantyRegDate !== '') {
        return array('Yes', $warrantyRegDate);
    }

    return array('No', '');
}

function pumpInstallImportFindCustomer($conn, $beneficiaryId, $phone, $email, $projectId, $subHeadId)
{
    $beneficiaryId = insuranceImportNormalizeBeneficiaryId($beneficiaryId);
    $projectId = (int) $projectId;
    $subHeadId = (int) $subHeadId;
    $phone = preg_replace('/\s+/', '', trim((string) $phone));
    $email = strtolower(trim((string) $email));

    if ($beneficiaryId !== '') {
        $stmt = $conn->prepare(
            'SELECT id FROM tbl_users WHERE Roll=5 AND ProjectType=1 AND BeneficiaryId=? AND ProjectId=? AND ProjectSubHeadId=? LIMIT 1'
        );
        if ($stmt) {
            $stmt->bind_param('sii', $beneficiaryId, $projectId, $subHeadId);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && ($row = $res->fetch_assoc())) {
                $stmt->close();
                return (int) $row['id'];
            }
            $stmt->close();
        }
    }

    if ($phone !== '') {
        $stmt = $conn->prepare(
            'SELECT id FROM tbl_users WHERE Roll=5 AND ProjectType=1 AND Phone=? AND ProjectId=? AND ProjectSubHeadId=? LIMIT 1'
        );
        if ($stmt) {
            $stmt->bind_param('sii', $phone, $projectId, $subHeadId);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && ($row = $res->fetch_assoc())) {
                $stmt->close();
                return (int) $row['id'];
            }
            $stmt->close();
        }
    }

    if ($email !== '') {
        $stmt = $conn->prepare(
            'SELECT id FROM tbl_users WHERE Roll=5 AND ProjectType=1 AND LOWER(EmailId)=? AND ProjectId=? AND ProjectSubHeadId=? LIMIT 1'
        );
        if ($stmt) {
            $stmt->bind_param('sii', $email, $projectId, $subHeadId);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && ($row = $res->fetch_assoc())) {
                $stmt->close();
                return (int) $row['id'];
            }
            $stmt->close();
        }
    }

    if ($beneficiaryId !== '') {
        $stmt = $conn->prepare(
            'SELECT id FROM tbl_users WHERE Roll=5 AND ProjectType=1 AND BeneficiaryId=? LIMIT 1'
        );
        if ($stmt) {
            $stmt->bind_param('s', $beneficiaryId);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && ($row = $res->fetch_assoc())) {
                $stmt->close();
                return (int) $row['id'];
            }
            $stmt->close();
        }
    }

    if ($phone !== '') {
        $stmt = $conn->prepare(
            'SELECT id FROM tbl_users WHERE Roll=5 AND ProjectType=1 AND Phone=? LIMIT 1'
        );
        if ($stmt) {
            $stmt->bind_param('s', $phone);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && ($row = $res->fetch_assoc())) {
                $stmt->close();
                return (int) $row['id'];
            }
            $stmt->close();
        }
    }

    if ($email !== '') {
        $stmt = $conn->prepare(
            'SELECT id FROM tbl_users WHERE Roll=5 AND ProjectType=1 AND LOWER(EmailId)=? LIMIT 1'
        );
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && ($row = $res->fetch_assoc())) {
                $stmt->close();
                return (int) $row['id'];
            }
            $stmt->close();
        }
    }

    return 0;
}

function pumpInstallImportDuplicateExists($conn, $beneficiaryId, $projectId, $subHeadId)
{
    $beneficiaryId = insuranceImportNormalizeBeneficiaryId($beneficiaryId);
    $projectId = (int) $projectId;
    $subHeadId = (int) $subHeadId;

    $stmt = $conn->prepare(
        'SELECT ti.id FROM tbl_installations ti
         INNER JOIN tbl_users tu ON ti.CustId = tu.id
         WHERE tu.Roll=5 AND tu.BeneficiaryId=? AND tu.ProjectId=? AND tu.ProjectSubHeadId=? AND ti.Type=2
         LIMIT 1'
    );
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param('sii', $beneficiaryId, $projectId, $subHeadId);
    $stmt->execute();
    $res = $stmt->get_result();
    $exists = $res && $res->num_rows > 0;
    $stmt->close();

    return $exists;
}

function pumpInstallImportCreateCustomer($conn, $data, $projectId, $subHeadId, $userId, $createdDate)
{
    $fname = trim((string) ($data['customer_name'] ?? ''));
    $beneficiaryId = insuranceImportNormalizeBeneficiaryId($data['beneficiary_id'] ?? '');
    $phone = preg_replace('/\s+/', '', trim((string) ($data['contact_no'] ?? '')));
    $email = trim((string) ($data['email'] ?? ''));
    $address = trim((string) ($data['address'] ?? ''));
    $latitude = trim((string) ($data['latitude'] ?? ''));
    $longitude = trim((string) ($data['longitude'] ?? ''));
    $taluka = trim((string) ($data['taluka'] ?? ''));
    $village = trim((string) ($data['village'] ?? ''));
    $district = trim((string) ($data['district'] ?? ''));
    $pumpCapacity = trim((string) ($data['pump_capacity'] ?? ''));
    $projectId = (int) $projectId;
    $subHeadId = (int) $subHeadId;
    $userId = (int) $userId;
    $projectType = 1;
    $roll = 5;
    $status = 1;
    $fieldSurveyDetails = 1;
    $password = md5($phone !== '' ? $phone : $beneficiaryId);
    $workOrderDone = pumpInstallImportYesNo($data['work_order_done'] ?? '', 'Yes');

    $stmt = $conn->prepare(
        'INSERT INTO tbl_users (
            Fname, Phone, EmailId, Address, Lattitude, Longitude, Taluka, Village, District,
            PumpCapacity, BeneficiaryId, ProjectId, ProjectSubHeadId, ProjectType, Roll, Status,
            FieldSurveyDetails, Password, CreatedDate, CreatedBy
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    if (!$stmt) {
        return 0;
    }

    $stmt->bind_param(
        'sssssssssssiiiiiissi',
        $fname,
        $phone,
        $email,
        $address,
        $latitude,
        $longitude,
        $taluka,
        $village,
        $district,
        $pumpCapacity,
        $beneficiaryId,
        $projectId,
        $subHeadId,
        $projectType,
        $roll,
        $status,
        $fieldSurveyDetails,
        $password,
        $createdDate,
        $userId
    );

    if (!$stmt->execute()) {
        $stmt->close();
        return 0;
    }

    $custId = (int) $stmt->insert_id;
    $stmt->close();

    if ($custId > 0) {
        $customerCode = 'VTECH-C' . $custId;
        $upd = $conn->prepare('UPDATE tbl_users SET CustomerId=? WHERE id=?');
        if ($upd) {
            $upd->bind_param('si', $customerCode, $custId);
            $upd->execute();
            $upd->close();
        }

        if (workOrderUsersHasColumn($conn, 'WorkOrderDone')) {
            $doneEsc = $workOrderDone;
            if (workOrderUsersHasColumn($conn, 'WorkOrderDoneDate')) {
                $woDate = trim((string) ($data['work_order_done_date'] ?? ''));
                $woDateSql = ($workOrderDone === 'Yes' && $woDate !== '') ? $woDate : null;
                $updWo = $conn->prepare('UPDATE tbl_users SET WorkOrderDone=?, WorkOrderDoneDate=? WHERE id=?');
                if ($updWo) {
                    $updWo->bind_param('ssi', $doneEsc, $woDateSql, $custId);
                    $updWo->execute();
                    $updWo->close();
                }
            } else {
                $updWo = $conn->prepare('UPDATE tbl_users SET WorkOrderDone=? WHERE id=?');
                if ($updWo) {
                    $updWo->bind_param('si', $doneEsc, $custId);
                    $updWo->execute();
                    $updWo->close();
                }
            }
        }
    }

    return $custId;
}

function pumpInstallImportUpdateCustomerFromRow($conn, $custId, $data)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return;
    }

    $fname = trim((string) ($data['customer_name'] ?? ''));
    $phone = preg_replace('/\s+/', '', trim((string) ($data['contact_no'] ?? '')));
    $email = trim((string) ($data['email'] ?? ''));
    $address = trim((string) ($data['address'] ?? ''));
    $latitude = trim((string) ($data['latitude'] ?? ''));
    $longitude = trim((string) ($data['longitude'] ?? ''));
    $taluka = trim((string) ($data['taluka'] ?? ''));
    $village = trim((string) ($data['village'] ?? ''));
    $district = trim((string) ($data['district'] ?? ''));
    $pumpCapacity = trim((string) ($data['pump_capacity'] ?? ''));
    $beneficiaryId = insuranceImportNormalizeBeneficiaryId($data['beneficiary_id'] ?? '');

    $stmt = $conn->prepare(
        'UPDATE tbl_users SET
            Fname = IF(? <> \'\', ?, Fname),
            Phone = IF(? <> \'\', ?, Phone),
            EmailId = IF(? <> \'\', ?, EmailId),
            Address = IF(? <> \'\', ?, Address),
            Lattitude = IF(? <> \'\', ?, Lattitude),
            Longitude = IF(? <> \'\', ?, Longitude),
            Taluka = IF(? <> \'\', ?, Taluka),
            Village = IF(? <> \'\', ?, Village),
            District = IF(? <> \'\', ?, District),
            PumpCapacity = IF(? <> \'\', ?, PumpCapacity),
            BeneficiaryId = IF(? <> \'\', ?, BeneficiaryId)
         WHERE id=? AND Roll=5 LIMIT 1'
    );
    if (!$stmt) {
        return;
    }

    $stmt->bind_param(
        'ssssssssssssssssssssi',
        $fname, $fname,
        $phone, $phone,
        $email, $email,
        $address, $address,
        $latitude, $latitude,
        $longitude, $longitude,
        $taluka, $taluka,
        $village, $village,
        $district, $district,
        $pumpCapacity, $pumpCapacity,
        $beneficiaryId, $beneficiaryId,
        $custId
    );
    $stmt->execute();
    $stmt->close();
}

function pumpInstallImportInsertInstallation($conn, $custId, $data, $userId, $createdDate)
{
    $custId = (int) $custId;
    $userId = (int) $userId;
    $installStatus = 'Yes';
    $installationDate = trim((string) ($data['installation_date'] ?? ''));
    if ($installationDate === '' || $installationDate === '0000-00-00') {
        $installationDate = $createdDate;
    }

    list($warrantyReg, $warrantyRegDate) = pumpInstallImportResolveWarranty(
        $installStatus,
        $installationDate,
        $data['warranty_reg'] ?? 'Yes',
        $data['warranty_reg_date'] ?? ''
    );

    $workOrderDone = pumpInstallImportYesNo($data['work_order_done'] ?? '', 'Yes');
    $workOrderDoneDate = trim((string) ($data['work_order_done_date'] ?? ''));
    if ($workOrderDone === 'Yes' && $workOrderDoneDate === '') {
        $workOrderDoneDate = $installationDate;
    }

    $fields = array(
        'ImeiNo' => trim((string) ($data['imei_no'] ?? '')),
        'DcrVerify' => pumpInstallImportYesNo($data['dcr_verify'] ?? '', 'No'),
        'DcrVerifyDate' => trim((string) ($data['dcr_verify_date'] ?? '')),
        'JvInvNo' => trim((string) ($data['jv_inv_no'] ?? '')),
        'JvInvDate' => trim((string) ($data['jv_inv_date'] ?? '')),
        'DriveLink' => trim((string) ($data['drive_link'] ?? '')),
        'PaymentDone' => 'No',
        'PaymentDate' => trim((string) ($data['payment_date'] ?? '')),
        'CustId' => (string) $custId,
        'CellNo' => preg_replace('/\s+/', '', trim((string) ($data['contact_no'] ?? ''))),
        'CustName' => trim((string) ($data['customer_name'] ?? '')),
        'Address' => trim((string) ($data['address'] ?? '')),
        'Lattitude' => trim((string) ($data['latitude'] ?? '')),
        'Longitude' => trim((string) ($data['longitude'] ?? '')),
        'WaterOutflow' => pumpInstallImportYesNo($data['water_outflow'] ?? '', 'No'),
        'FarmerNoc' => pumpInstallImportYesNo($data['farmer_noc'] ?? '', 'No'),
        'FarmerNocDate' => trim((string) ($data['farmer_noc_date'] ?? '')),
        'FarmerVideo' => pumpInstallImportYesNo($data['farmer_video'] ?? '', 'No'),
        'FarmerVideoDate' => trim((string) ($data['farmer_video_date'] ?? '')),
        'PoInspection' => pumpInstallImportYesNo($data['po_inspection'] ?? '', 'No'),
        'PoInspectionDate' => trim((string) ($data['po_inspection_date'] ?? '')),
        'PoApproval' => pumpInstallImportYesNo($data['po_approval'] ?? '', 'No'),
        'PoApprovalDate' => trim((string) ($data['po_approval_date'] ?? '')),
        'InstStatus' => '',
        'InstallStatus' => $installStatus,
        'InstallationDate' => $installationDate,
        'DgmApproval' => pumpInstallImportYesNo($data['dgm_approval'] ?? '', 'No'),
        'DgmApprovalDate' => trim((string) ($data['dgm_approval_date'] ?? '')),
        'InsuranceApproval' => pumpInstallImportYesNo($data['insurance_approval'] ?? '', 'No'),
        'InsuranceApprovalDate' => trim((string) ($data['insurance_approval_date'] ?? '')),
        'CircleOfficeStatus' => pumpInstallImportYesNo($data['circle_office_status'] ?? '', 'No'),
        'CircleOfficeDate' => trim((string) ($data['circle_office_date'] ?? '')),
        'RmsIntegrationStatus' => pumpInstallImportYesNo($data['rms_integration'] ?? '', 'No'),
        'RmsIntegrationDate' => trim((string) ($data['rms_integration_date'] ?? '')),
        'RmsIntegration7Days' => pumpInstallImportYesNo($data['rms_integration_7days'] ?? '', 'No'),
        'RmsIntegration90Days' => pumpInstallImportYesNo($data['rms_integration_90days'] ?? '', 'No'),
        'IcrSignDoOffice' => pumpInstallImportYesNo($data['icr_sign_do_office'] ?? '', 'No'),
        'IcrSignDoOfficeDate' => trim((string) ($data['icr_sign_do_office_date'] ?? '')),
        'BillForward' => pumpInstallImportYesNo($data['bill_forward'] ?? '', 'No'),
        'BillForwardDate' => trim((string) ($data['bill_forward_date'] ?? '')),
        'RoToRoAccts' => pumpInstallImportYesNo($data['ro_to_ro_accts'] ?? '', 'No'),
        'RoToRoAcctsDate' => trim((string) ($data['ro_to_ro_accts_date'] ?? '')),
        'RoAcctsToZo' => pumpInstallImportYesNo($data['ro_accts_to_zo'] ?? '', 'No'),
        'RoAcctsToZoDate' => trim((string) ($data['ro_accts_to_zo_date'] ?? '')),
        'ZoToHo' => pumpInstallImportYesNo($data['zo_to_ho'] ?? '', 'No'),
        'ZoToHoDate' => trim((string) ($data['zo_to_ho_date'] ?? '')),
        'HoToHoAccts' => pumpInstallImportYesNo($data['ho_to_ho_accts'] ?? '', 'No'),
        'HoToHoAcctsDate' => trim((string) ($data['ho_to_ho_accts_date'] ?? '')),
        'ForwardToPayment' => pumpInstallImportYesNo($data['forward_to_payment'] ?? '', 'No'),
        'ForwardToPaymentDate' => trim((string) ($data['forward_to_payment_date'] ?? '')),
        'SentToHo' => pumpInstallImportYesNo($data['sent_to_ho'] ?? '', 'No'),
        'SentToHoDate' => trim((string) ($data['sent_to_ho_date'] ?? '')),
        'FileInHand' => pumpInstallImportYesNo($data['file_in_hand'] ?? '', 'No'),
        'FileInHandDate' => trim((string) ($data['file_in_hand_date'] ?? '')),
        'Payment90' => pumpInstallImportYesNo($data['payment_90'] ?? '', 'No'),
        'Payment90Amt' => trim((string) ($data['payment_90_amt'] ?? '')),
        'Payment10' => pumpInstallImportYesNo($data['payment_10'] ?? '', 'No'),
        'Payment10Amt' => trim((string) ($data['payment_10_amt'] ?? '')),
        'InspectionDiscrepancy' => pumpInstallImportYesNo($data['inspection_discrepancy'] ?? '', 'No'),
        'InspectionDiscrepancyDate' => trim((string) ($data['inspection_discrepancy_date'] ?? '')),
        'InspectionDiscrepancyRemark' => trim((string) ($data['inspection_discrepancy_remark'] ?? '')),
        'WarrantyReg' => $warrantyReg,
        'WarrantyRegDate' => $warrantyRegDate,
        'DataUploadStatus' => pumpInstallImportYesNo($data['data_upload'] ?? '', 'No'),
        'DataUploadDate' => trim((string) ($data['data_upload_date'] ?? '')),
        'Foundation' => pumpInstallImportYesNo($data['foundation'] ?? '', 'No'),
        'FoundationContractorId' => '',
        'FoundationDate' => trim((string) ($data['foundation_date'] ?? '')),
        'Documentation' => pumpInstallImportYesNo($data['documentation'] ?? '', 'No'),
        'DocumentationContractorId' => '',
        'DocumentationDate' => trim((string) ($data['documentation_date'] ?? '')),
        'Status' => '1',
        'CreatedBy' => (string) $userId,
        'CreatedDate' => $createdDate,
        'Type' => '2',
    );

    for ($i = 1; $i <= 18; $i++) {
        $fields['Photo' . $i] = '';
    }

    if (workOrderInstallHasColumn($conn, 'WorkOrderDone')) {
        $fields['WorkOrderDone'] = $workOrderDone;
        if (workOrderInstallHasColumn($conn, 'WorkOrderDoneDate')) {
            $fields['WorkOrderDoneDate'] = $workOrderDoneDate;
        }
    }

    $setParts = array();
    $types = '';
    $values = array();

    foreach ($fields as $column => $value) {
        $safeColumn = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
        $setParts[] = '`' . $safeColumn . '`=?' ;
        $values[] = (string) $value;
        $types .= 's';
    }

    $sql = 'INSERT INTO tbl_installations SET ' . implode(', ', $setParts);
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return 0;
    }

    $bind = array($types);
    foreach ($values as $idx => $val) {
        $bind[] = &$values[$idx];
    }
    call_user_func_array(array($stmt, 'bind_param'), $bind);

    if (!$stmt->execute()) {
        $stmt->close();
        return 0;
    }

    $instId = (int) $stmt->insert_id;
    $stmt->close();

    if ($instId > 0 && workOrderCustomerIsSupported($conn)) {
        workOrderCustomerSave($conn, $custId, $workOrderDone, $workOrderDoneDate);
    }

    return $instId;
}

function pumpInstallImportRowFromSheet($row, $columnMap)
{
    return array(
        'beneficiary_id' => insuranceImportNormalizeBeneficiaryId(
            pumpInstallImportFieldValue($row, $columnMap, 'beneficiary_id')
        ),
        'customer_name' => pumpInstallImportFieldValue($row, $columnMap, 'customer_name'),
        'contact_no' => pumpInstallImportFieldValue($row, $columnMap, 'contact_no'),
        'email' => pumpInstallImportFieldValue($row, $columnMap, 'email'),
        'address' => pumpInstallImportFieldValue($row, $columnMap, 'address'),
        'latitude' => pumpInstallImportFieldValue($row, $columnMap, 'latitude'),
        'longitude' => pumpInstallImportFieldValue($row, $columnMap, 'longitude'),
        'taluka' => pumpInstallImportFieldValue($row, $columnMap, 'taluka'),
        'village' => pumpInstallImportFieldValue($row, $columnMap, 'village'),
        'district' => pumpInstallImportFieldValue($row, $columnMap, 'district'),
        'pump_capacity' => pumpInstallImportFieldValue($row, $columnMap, 'pump_capacity'),
        'drive_link' => pumpInstallImportFieldValue($row, $columnMap, 'drive_link'),
        'installation_date' => pumpInstallImportDateValue($row, $columnMap, 'installation_date'),
        'imei_no' => pumpInstallImportFieldValue($row, $columnMap, 'imei_no'),
        'water_outflow' => pumpInstallImportFieldValue($row, $columnMap, 'water_outflow'),
        'farmer_noc' => pumpInstallImportFieldValue($row, $columnMap, 'farmer_noc'),
        'farmer_noc_date' => pumpInstallImportDateValue($row, $columnMap, 'farmer_noc_date'),
        'farmer_video' => pumpInstallImportFieldValue($row, $columnMap, 'farmer_video'),
        'farmer_video_date' => pumpInstallImportDateValue($row, $columnMap, 'farmer_video_date'),
        'data_upload' => pumpInstallImportFieldValue($row, $columnMap, 'data_upload'),
        'data_upload_date' => pumpInstallImportDateValue($row, $columnMap, 'data_upload_date'),
        'po_inspection' => pumpInstallImportFieldValue($row, $columnMap, 'po_inspection'),
        'po_inspection_date' => pumpInstallImportDateValue($row, $columnMap, 'po_inspection_date'),
        'insurance_approval' => pumpInstallImportFieldValue($row, $columnMap, 'insurance_approval'),
        'insurance_approval_date' => pumpInstallImportDateValue($row, $columnMap, 'insurance_approval_date'),
        'file_in_hand' => pumpInstallImportFieldValue($row, $columnMap, 'file_in_hand'),
        'file_in_hand_date' => pumpInstallImportDateValue($row, $columnMap, 'file_in_hand_date'),
        'rms_integration' => pumpInstallImportFieldValue($row, $columnMap, 'rms_integration'),
        'rms_integration_date' => pumpInstallImportDateValue($row, $columnMap, 'rms_integration_date'),
        'work_order_done' => pumpInstallImportFieldValue($row, $columnMap, 'work_order_done'),
        'work_order_done_date' => pumpInstallImportDateValue($row, $columnMap, 'work_order_done_date'),
        'foundation' => pumpInstallImportFieldValue($row, $columnMap, 'foundation'),
        'documentation' => pumpInstallImportFieldValue($row, $columnMap, 'documentation'),
        'dcr_verify' => pumpInstallImportFieldValue($row, $columnMap, 'dcr_verify'),
        'dcr_verify_date' => pumpInstallImportDateValue($row, $columnMap, 'dcr_verify_date'),
        'jv_inv_no' => pumpInstallImportFieldValue($row, $columnMap, 'jv_inv_no'),
        'jv_inv_date' => pumpInstallImportDateValue($row, $columnMap, 'jv_inv_date'),
        'warranty_reg' => pumpInstallImportFieldValue($row, $columnMap, 'warranty_reg'),
        'warranty_reg_date' => pumpInstallImportDateValue($row, $columnMap, 'warranty_reg_date'),
        'payment_90' => pumpInstallImportFieldValue($row, $columnMap, 'payment_90'),
        'payment_10' => pumpInstallImportFieldValue($row, $columnMap, 'payment_10'),
        'sent_to_ho' => pumpInstallImportFieldValue($row, $columnMap, 'sent_to_ho'),
        'po_approval' => pumpInstallImportFieldValue($row, $columnMap, 'po_approval'),
        'dgm_approval' => pumpInstallImportFieldValue($row, $columnMap, 'dgm_approval'),
        'circle_office_status' => pumpInstallImportFieldValue($row, $columnMap, 'circle_office_status'),
        'inspection_discrepancy' => pumpInstallImportFieldValue($row, $columnMap, 'inspection_discrepancy'),
        'forward_to_payment' => pumpInstallImportFieldValue($row, $columnMap, 'forward_to_payment'),
        'payment_date' => pumpInstallImportDateValue($row, $columnMap, 'payment_date'),
    );
}

function pumpInstallImportProcessSpreadsheet($targetPath, $originalName, $fileType, $projectId, $subHeadId, $userId)
{
    require_once __DIR__ . '/vendor/php-excel-reader/excel_reader2.php';
    require_once __DIR__ . '/vendor/SpreadsheetReader.php';

    workOrderCustomerEnsureSchema($conn = $GLOBALS['conn']);

    $projectId = (int) $projectId;
    $subHeadId = (int) $subHeadId;
    $userId = (int) $userId;
    $createdDate = date('Y-m-d');

    $summary = array(
        'success' => false,
        'message' => '',
        'total_rows' => 0,
        'customers_created' => 0,
        'customers_matched' => 0,
        'installations_inserted' => 0,
        'skipped' => 0,
        'errors' => array(),
    );

    $allRows = array();

    try {
        $reader = new SpreadsheetReader($targetPath, $originalName, $fileType);
        $sheetCount = count($reader->sheets());
        for ($s = 0; $s < $sheetCount; $s++) {
            $reader->ChangeSheet($s);
            foreach ($reader as $row) {
                if (is_array($row)) {
                    $allRows[] = insuranceImportNormalizeRow($row);
                }
            }
        }
    } catch (Exception $e) {
        $summary['message'] = 'Could not read Excel file.';
        return $summary;
    }

    if (empty($allRows)) {
        $summary['message'] = 'Excel file is empty.';
        return $summary;
    }

    $columnMap = pumpInstallImportDetectColumnMap($allRows);
    if (!isset($columnMap['beneficiary_id'])) {
        $summary['message'] = 'Beneficiary ID column not found in Excel file.';
        return $summary;
    }

    $rowNumber = 0;
    foreach ($allRows as $row) {
        $rowNumber++;
        if (pumpInstallImportIsHeaderRow($row)) {
            continue;
        }

        $parsed = pumpInstallImportRowFromSheet($row, $columnMap);
        $beneficiaryId = $parsed['beneficiary_id'];
        $customerName = trim((string) $parsed['customer_name']);

        if ($beneficiaryId === '' && $customerName === '') {
            continue;
        }

        $summary['total_rows']++;

        if ($beneficiaryId === '') {
            $summary['skipped']++;
            $summary['errors'][] = array('row' => $rowNumber, 'reason' => 'Beneficiary ID is required.');
            continue;
        }

        if ($customerName === '') {
            $summary['skipped']++;
            $summary['errors'][] = array('row' => $rowNumber, 'reason' => 'Customer Name is required.');
            continue;
        }

        if (pumpInstallImportDuplicateExists($conn, $beneficiaryId, $projectId, $subHeadId)) {
            $summary['skipped']++;
            $summary['errors'][] = array(
                'row' => $rowNumber,
                'reason' => 'Duplicate installation already exists for Beneficiary ID ' . $beneficiaryId . ' in this project/site.',
            );
            continue;
        }

        $existingCustId = pumpInstallImportFindCustomer(
            $conn,
            $beneficiaryId,
            $parsed['contact_no'],
            $parsed['email'],
            $projectId,
            $subHeadId
        );

        $custId = 0;
        $customerCreated = false;

        if ($existingCustId > 0) {
            $custId = $existingCustId;
            pumpInstallImportUpdateCustomerFromRow($conn, $custId, $parsed);
            $summary['customers_matched']++;
        } else {
            $custId = pumpInstallImportCreateCustomer($conn, $parsed, $projectId, $subHeadId, $userId, $createdDate);
            if ($custId <= 0) {
                $summary['skipped']++;
                $summary['errors'][] = array(
                    'row' => $rowNumber,
                    'reason' => 'Failed to create customer account for Beneficiary ID ' . $beneficiaryId . '.',
                );
                continue;
            }
            $customerCreated = true;
            $summary['customers_created']++;
        }

        $instId = pumpInstallImportInsertInstallation($conn, $custId, $parsed, $userId, $createdDate);
        if ($instId <= 0) {
            $summary['skipped']++;
            if ($customerCreated) {
                $summary['customers_created']--;
            } else {
                $summary['customers_matched']--;
            }
            $summary['errors'][] = array(
                'row' => $rowNumber,
                'reason' => 'Failed to insert installation record for Beneficiary ID ' . $beneficiaryId . '.',
            );
            continue;
        }

        $summary['installations_inserted']++;
    }

    $summary['success'] = true;
    $summary['message'] = 'Import completed.';

    return $summary;
}
