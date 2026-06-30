<?php

if (!function_exists('rooftopSurveyUploadPhoto')) {
    function rooftopSurveyUploadPhoto($orgfile, $tempfile)
    {
        $Photo = '';
        if ($orgfile === '' || $tempfile === '') {
            return $Photo;
        }
        $randno = rand(1, 100);
        $fnm = substr($orgfile, 0, strrpos($orgfile, '.'));
        $fnm = str_replace(' ', '_', $fnm);
        $ext = substr($orgfile, strpos($orgfile, '.'));
        $dest = '../uploads/' . $randno . '_' . $fnm . $ext;
        $imagepath = $randno . '_' . $fnm . $ext;
        if (move_uploaded_file($tempfile, $dest)) {
            $Photo = $imagepath;
        }

        return $Photo;
    }
}

if (!function_exists('rooftopSurveyResolveUpload')) {
    function rooftopSurveyResolveUpload($yn, $fileKey, $oldKey, $post, $files)
    {
        if ($yn !== 'Yes') {
            return '';
        }
        if (isset($files[$fileKey]['name']) && $files[$fileKey]['name'] !== '') {
            return rooftopSurveyUploadPhoto($files[$fileKey]['name'], $files[$fileKey]['tmp_name']);
        }

        return addslashes(trim($post[$oldKey] ?? ''));
    }
}

if (!function_exists('rooftopSurveyCollectDocFields')) {
    function rooftopSurveyCollectDocFields($prefix, $post, $files)
    {
        $p = $prefix;
        $ynVal = function ($field) use ($post, $p) {
            return addslashes(trim($post[$p . $field . 'Yn'] ?? ''));
        };

        $sitePhotoYn = $ynVal('SitePhoto');
        $panCardYn = $ynVal('PanCard');
        $aadharCardYn = $ynVal('AadharCard');
        $electricBillYn = $ynVal('ElectricBill');

        return [
            $p . 'SitePhotoYn' => $sitePhotoYn,
            $p . 'SitePhoto' => rooftopSurveyResolveUpload($sitePhotoYn, $p . 'SitePhoto', $p . 'SitePhotoOld', $post, $files),
            $p . 'PanCardYn' => $panCardYn,
            $p . 'PanCard' => rooftopSurveyResolveUpload($panCardYn, $p . 'PanCard', 'PanCardOld', $post, $files),
            $p . 'AadharCardYn' => $aadharCardYn,
            $p . 'AadharCard' => rooftopSurveyResolveUpload($aadharCardYn, $p . 'AadharCard', 'AadharCardOld', $post, $files),
            $p . 'AadharCard2' => rooftopSurveyResolveUpload($aadharCardYn, $p . 'AadharCard2', 'AadharCardOld2', $post, $files),
            $p . 'ElectricBillYn' => $electricBillYn,
            $p . 'ElectricBill' => rooftopSurveyResolveUpload($electricBillYn, $p . 'ElectricBill', 'ElectricBillOld', $post, $files),
        ];
    }
}

if (!function_exists('rooftopSurveyDocSqlSet')) {
    function rooftopSurveyDocSqlSet(array $docFields)
    {
        $parts = [];
        foreach ($docFields as $col => $val) {
            $parts[] = "`$col`='$val'";
        }

        return implode(',', $parts);
    }
}
