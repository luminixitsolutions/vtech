<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$sampleDir = __DIR__ . '/sample_files';
if (!is_dir($sampleDir)) {
    @mkdir($sampleDir, 0755, true);
}

$staticCsv = $sampleDir . '/sample_coordinator_assign_beneficiary.csv';
if (!is_file($staticCsv)) {
    file_put_contents($staticCsv, "\xEF\xBB\xBFBeneficiary ID\nBENEFICIARY_ID_1\nBENEFICIARY_ID_2\n");
}

if (!class_exists('ZipArchive')) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="sample_coordinator_assign_beneficiary.csv"');
    readfile($staticCsv);
    exit;
}

$rows = [
    ['Beneficiary ID'],
    ['BENEFICIARY_ID_1'],
    ['BENEFICIARY_ID_2'],
];

$sharedStrings = [];
$stringIndex = [];
foreach ($rows as $row) {
    foreach ($row as $cell) {
        $cell = (string) $cell;
        if (!array_key_exists($cell, $stringIndex)) {
            $stringIndex[$cell] = count($sharedStrings);
            $sharedStrings[] = $cell;
        }
    }
}

$sharedStringsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="'
    . count($sharedStrings) . '" uniqueCount="' . count($sharedStrings) . '">';
foreach ($sharedStrings as $text) {
    $sharedStringsXml .= '<si><t>' . htmlspecialchars($text, ENT_XML1, 'UTF-8') . '</t></si>';
}
$sharedStringsXml .= '</sst>';

$sheetRowsXml = '';
$rowNum = 1;
foreach ($rows as $row) {
    $sheetRowsXml .= '<row r="' . $rowNum . '">';
    $colNum = 0;
    foreach ($row as $cell) {
        $colLetter = chr(65 + $colNum);
        $cellRef = $colLetter . $rowNum;
        $idx = $stringIndex[(string) $cell];
        $sheetRowsXml .= '<c r="' . $cellRef . '" t="s"><v>' . $idx . '</v></c>';
        $colNum++;
    }
    $sheetRowsXml .= '</row>';
    $rowNum++;
}

$sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
    . '<sheetData>' . $sheetRowsXml . '</sheetData></worksheet>';

$workbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
    . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
    . '<sheets><sheet name="Sheet1" sheetId="1" r:id="rId1"/></sheets></workbook>';

$contentTypesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
    . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
    . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
    . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
    . '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>'
    . '</Types>';

$relsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
    . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
    . '</Relationships>';

$workbookRelsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
    . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
    . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>'
    . '</Relationships>';

$tmpFile = tempnam(sys_get_temp_dir(), 'coord_sample_');
if ($tmpFile === false) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="sample_coordinator_assign_beneficiary.csv"');
    readfile($staticCsv);
    exit;
}

$zip = new ZipArchive();
if ($zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    @unlink($tmpFile);
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="sample_coordinator_assign_beneficiary.csv"');
    readfile($staticCsv);
    exit;
}

$zip->addFromString('[Content_Types].xml', $contentTypesXml);
$zip->addFromString('_rels/.rels', $relsXml);
$zip->addFromString('xl/workbook.xml', $workbookXml);
$zip->addFromString('xl/_rels/workbook.xml.rels', $workbookRelsXml);
$zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
$zip->addFromString('xl/sharedStrings.xml', $sharedStringsXml);
$zip->close();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="sample_coordinator_assign_beneficiary.xlsx"');
header('Content-Length: ' . filesize($tmpFile));
header('Pragma: no-cache');
header('Expires: 0');
readfile($tmpFile);
@unlink($tmpFile);
