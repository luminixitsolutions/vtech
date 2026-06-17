<?php

function complaintEnggActionReportFieldDefinitions()
{
    return [
        'RmsNetwork' => ['label' => 'RMS No./Network', 'input' => 'text', 'sql' => 'VARCHAR(255) NULL'],
        'ArrayVoltage' => ['label' => 'Array Voltage (VDC)', 'input' => 'text', 'sql' => 'VARCHAR(100) NULL'],
        'ArrayCurrent' => ['label' => 'Array Current', 'input' => 'text', 'sql' => 'VARCHAR(100) NULL'],
        'Weather' => ['label' => 'Weather', 'input' => 'text', 'sql' => 'VARCHAR(255) NULL'],
        'PipeLength' => ['label' => 'Pipe Length', 'input' => 'text', 'sql' => 'VARCHAR(100) NULL'],
        'PipeSizeInch' => ['label' => 'Pipe Size Inch', 'input' => 'text', 'sql' => 'VARCHAR(100) NULL'],
        'WaterStatus' => ['label' => 'Water Status', 'input' => 'text', 'sql' => 'VARCHAR(255) NULL'],
        'RpmStatus' => ['label' => 'RPM Status', 'input' => 'text', 'sql' => 'VARCHAR(255) NULL'],
        'CableLengthPole' => ['label' => 'Cable Length of Pole', 'input' => 'text', 'sql' => 'VARCHAR(100) NULL'],
        'FaultType' => ['label' => 'Fault Type', 'input' => 'text', 'sql' => 'VARCHAR(255) NULL'],
        'PumpLocation' => ['label' => 'Pump Location', 'input' => 'text', 'sql' => 'VARCHAR(255) NULL'],
        'PumpInstalledDepth' => ['label' => 'Pump Installed Depth', 'input' => 'text', 'sql' => 'VARCHAR(100) NULL'],
        'WaterLevelHead' => ['label' => 'Water Level Head', 'input' => 'text', 'sql' => 'VARCHAR(100) NULL'],
        'LoweringOfPump' => ['label' => 'Lowering of Pump', 'input' => 'text', 'sql' => 'VARCHAR(100) NULL'],
        'MotorResistance' => ['label' => 'Resistance of Motor in Disconnected Condition (L1-L2)', 'input' => 'text', 'sql' => 'VARCHAR(255) NULL'],
        'ActualProblemFound' => ['label' => 'Actual Problem Found', 'input' => 'textarea', 'sql' => 'TEXT NULL'],
        'ProvidedSolution' => ['label' => 'Provided Solution', 'input' => 'textarea', 'sql' => 'TEXT NULL'],
        'FieldObservations' => ['label' => 'Field Observations and Remarks', 'input' => 'textarea', 'sql' => 'TEXT NULL'],
        'ActionTaken' => ['label' => 'Action Taken by Representative', 'input' => 'textarea', 'sql' => 'TEXT NULL'],
        'CustomerRemarks' => ['label' => 'Customer Remarks', 'input' => 'textarea', 'sql' => 'TEXT NULL'],
    ];
}

function complaintEnggActionReportEnsureSchema($conn)
{
    foreach (complaintEnggActionReportFieldDefinitions() as $column => $def) {
        $check = $conn->query("SHOW COLUMNS FROM tbl_complaint_engg_actions LIKE '$column'");
        if ($check && $check->num_rows > 0) {
            continue;
        }
        $sqlType = $def['sql'] ?? 'VARCHAR(255) NULL';
        @$conn->query("ALTER TABLE tbl_complaint_engg_actions ADD COLUMN $column $sqlType");
    }

    $auditColumns = [
        'ModifiedDate' => 'DATETIME NULL',
        'ModifiedBy' => 'INT NULL',
    ];
    foreach ($auditColumns as $column => $sqlType) {
        $check = $conn->query("SHOW COLUMNS FROM tbl_complaint_engg_actions LIKE '$column'");
        if ($check && $check->num_rows > 0) {
            continue;
        }
        @$conn->query("ALTER TABLE tbl_complaint_engg_actions ADD COLUMN $column $sqlType");
    }
}

function complaintEnggActionReportCollectPost($conn)
{
    $fields = [];
    foreach (array_keys(complaintEnggActionReportFieldDefinitions()) as $column) {
        $fields[$column] = $conn->real_escape_string(trim((string) ($_POST[$column] ?? '')));
    }
    return $fields;
}

function complaintEnggActionReportSqlSet(array $fields)
{
    $parts = [];
    foreach ($fields as $column => $value) {
        $parts[] = "$column='$value'";
    }
    return implode(', ', $parts);
}

/**
 * Upload one or more site photos; returns stored filenames for tbl_complaint_engg_actions.Photo (comma-separated).
 *
 * @param array $files $_FILES['Photo'] or $_FILES['Photo[]']
 * @param string $uploadDir Absolute directory path ending without slash
 * @return string[]
 */
function complaintEnggActionUploadPhotos(array $files, $uploadDir)
{
    $saved = [];
    if (empty($files['name'])) {
        return $saved;
    }

    $names = $files['name'];
    $tmpNames = $files['tmp_name'];
    $errors = $files['error'];

    if (!is_array($names)) {
        $names = [$names];
        $tmpNames = [$tmpNames];
        $errors = [$errors];
    }

    foreach ($names as $i => $name) {
        $name = trim((string) $name);
        if ($name === '' || ($errors[$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            continue;
        }
        $tmp = $tmpNames[$i] ?? '';
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            continue;
        }

        $dot = strrpos($name, '.');
        $fnm = $dot !== false ? substr($name, 0, $dot) : $name;
        $fnm = str_replace(' ', '_', $fnm);
        $ext = $dot !== false ? substr($name, $dot) : '';
        $filename = rand(1, 100) . '_' . $fnm . $ext;
        $dest = rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        if (@move_uploaded_file($tmp, $dest)) {
            $saved[] = $filename;
        }
    }

    return $saved;
}

function complaintEnggActionPhotosToDbValue(array $photos)
{
    $photos = array_values(array_filter(array_map('trim', $photos)));
    return implode(',', $photos);
}

function complaintEnggActionGetLatest($conn, $complaintId)
{
    $complaintId = (int) $complaintId;
    if ($complaintId <= 0) {
        return null;
    }

    return getRecord("SELECT * FROM tbl_complaint_engg_actions
        WHERE CompId = '$complaintId'
        ORDER BY id DESC
        LIMIT 1");
}

function complaintEnggActionFormValue($action, $field, $fallback = '')
{
    if (!is_array($action)) {
        return $fallback;
    }
    $value = trim((string) ($action[$field] ?? ''));
    return $value !== '' ? $value : $fallback;
}

function complaintEnggActionMergePhotos($existing, array $uploaded)
{
    $parts = [];
    foreach (explode(',', (string) $existing) as $file) {
        $file = trim($file);
        if ($file !== '') {
            $parts[] = $file;
        }
    }
    foreach ($uploaded as $file) {
        $file = trim((string) $file);
        if ($file !== '' && !in_array($file, $parts, true)) {
            $parts[] = $file;
        }
    }
    return implode(',', $parts);
}

function complaintEnggActionPhotoList($photoValue)
{
    $photos = [];
    foreach (explode(',', (string) $photoValue) as $file) {
        $file = trim($file);
        if ($file !== '') {
            $photos[] = $file;
        }
    }
    return $photos;
}

function complaintEnggActionDeleteRemovedPhotoFiles($uploadDir, $previousPhotos, $newPhotos)
{
    $previous = complaintEnggActionPhotoList($previousPhotos);
    $current = complaintEnggActionPhotoList($newPhotos);
    $removed = array_diff($previous, $current);
    if (empty($removed)) {
        return;
    }

    $uploadDir = rtrim((string) $uploadDir, '/\\');
    foreach ($removed as $file) {
        $file = basename((string) $file);
        if ($file === '' || $file === '.' || $file === '..') {
            continue;
        }
        $path = $uploadDir . DIRECTORY_SEPARATOR . $file;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
