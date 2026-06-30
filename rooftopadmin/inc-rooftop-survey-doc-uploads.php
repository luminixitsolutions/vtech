<?php
/**
 * Document / photo uploads with Yes/No toggles.
 * Requires: $surveyPrefix ('Field' or 'Tel'), $row78
 */
$p = $surveyPrefix ?? 'Field';

function rooftopDocYnSelected($row, $ynKey, $fileKey)
{
    $yn = trim((string) ($row[$ynKey] ?? ''));
    if ($yn === 'Yes' || $yn === 'No') {
        return $yn;
    }
    if (trim((string) ($row[$fileKey] ?? '')) !== '') {
        return 'Yes';
    }

    return '';
}

$sitePhotoYn = rooftopDocYnSelected($row78, $p . 'SitePhotoYn', $p . 'SitePhoto');
$panCardYn = rooftopDocYnSelected($row78, $p . 'PanCardYn', $p . 'PanCard');
$aadharCardYn = rooftopDocYnSelected($row78, $p . 'AadharCardYn', $p . 'AadharCard');
$electricBillYn = rooftopDocYnSelected($row78, $p . 'ElectricBillYn', $p . 'ElectricBill');

function rooftopDocYnSelect($name, $selected)
{
    ?>
<select class="form-control rooftop-doc-yn" name="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" data-rooftop-doc-yn="1">
    <option value="">Select</option>
    <option value="Yes" <?php if ($selected === 'Yes') { ?> selected <?php } ?>>Yes</option>
    <option value="No" <?php if ($selected === 'No') { ?> selected <?php } ?>>No</option>
</select>
    <?php
}

function rooftopDocPreview($fileName, $uploadsPrefix = '../uploads/')
{
    if (trim((string) $fileName) === '') {
        return;
    }
    $safe = htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8');
    ?>
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3">
    <a href="<?php echo $uploadsPrefix . $safe; ?>" target="_blank"><?php echo $safe; ?></a>
</div>
    <?php
}
?>

<style>
.rooftop-doc-section-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
}
.rooftop-doc-upload-wrap {
    display: none;
}
.rooftop-doc-upload-wrap.is-visible {
    display: block;
}
</style>

<div class="form-group col-md-12">
    <div class="rooftop-doc-section-title">Documents &amp; Photos</div>
    <div class="small text-muted">Select Yes to show upload option for each document.</div>
</div>

<div class="form-group col-md-3">
    <label class="form-label">Site Photo</label>
    <?php rooftopDocYnSelect($p . 'SitePhotoYn', $sitePhotoYn); ?>
</div>
<div class="form-group col-md-9 rooftop-doc-upload-wrap" id="<?php echo $p; ?>SitePhotoWrap" data-rooftop-doc-wrap="1">
    <label class="form-label">Upload Site Photo</label>
    <label class="custom-file d-block">
        <input type="file" class="custom-file-input" name="<?php echo $p; ?>SitePhoto" style="opacity: 1;">
        <input type="hidden" name="<?php echo $p; ?>SitePhotoOld" value="<?php echo htmlspecialchars($row78[$p . 'SitePhoto'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" id="<?php echo $p; ?>SitePhotoOld">
        <span class="custom-file-label"></span>
    </label>
    <?php rooftopDocPreview($row78[$p . 'SitePhoto'] ?? ''); ?>
</div>

<div class="form-group col-md-3">
    <label class="form-label">Upload Pan Card</label>
    <?php rooftopDocYnSelect($p . 'PanCardYn', $panCardYn); ?>
</div>
<div class="form-group col-md-9 rooftop-doc-upload-wrap" id="<?php echo $p; ?>PanCardWrap" data-rooftop-doc-wrap="1">
    <label class="form-label">Upload Pan Card</label>
    <label class="custom-file d-block">
        <input type="file" class="custom-file-input" name="<?php echo $p; ?>PanCard" style="opacity: 1;">
        <input type="hidden" name="PanCardOld" value="<?php echo htmlspecialchars($row78[$p . 'PanCard'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" id="PanCardOld">
        <span class="custom-file-label"></span>
    </label>
    <?php rooftopDocPreview($row78[$p . 'PanCard'] ?? ''); ?>
</div>

<div class="form-group col-md-3">
    <label class="form-label">Upload Front/Back Aadhar Card</label>
    <?php rooftopDocYnSelect($p . 'AadharCardYn', $aadharCardYn); ?>
</div>
<div class="form-group col-md-9 rooftop-doc-upload-wrap" id="<?php echo $p; ?>AadharCardWrap" data-rooftop-doc-wrap="1">
    <label class="form-label">Upload Front Aadhar Card</label>
    <label class="custom-file d-block mb-2">
        <input type="file" class="custom-file-input" name="<?php echo $p; ?>AadharCard" style="opacity: 1;">
        <input type="hidden" name="AadharCardOld" value="<?php echo htmlspecialchars($row78[$p . 'AadharCard'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" id="AadharCardOld">
        <span class="custom-file-label"></span>
    </label>
    <?php rooftopDocPreview($row78[$p . 'AadharCard'] ?? ''); ?>
    <label class="form-label mt-2">Upload Back Aadhar Card</label>
    <label class="custom-file d-block">
        <input type="file" class="custom-file-input" name="<?php echo $p; ?>AadharCard2" style="opacity: 1;">
        <input type="hidden" name="AadharCardOld2" value="<?php echo htmlspecialchars($row78[$p . 'AadharCard2'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" id="AadharCardOld2">
        <span class="custom-file-label"></span>
    </label>
    <?php rooftopDocPreview($row78[$p . 'AadharCard2'] ?? ''); ?>
</div>

<div class="form-group col-md-3">
    <label class="form-label">Electricity Bill (12 Month)</label>
    <?php rooftopDocYnSelect($p . 'ElectricBillYn', $electricBillYn); ?>
</div>
<div class="form-group col-md-9 rooftop-doc-upload-wrap" id="<?php echo $p; ?>ElectricBillWrap" data-rooftop-doc-wrap="1">
    <label class="form-label">Upload Electricity Bill (12 Month)</label>
    <label class="custom-file d-block">
        <input type="file" class="custom-file-input" name="<?php echo $p; ?>ElectricBill" style="opacity: 1;">
        <input type="hidden" name="ElectricBillOld" value="<?php echo htmlspecialchars($row78[$p . 'ElectricBill'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" id="ElectricBillOld">
        <span class="custom-file-label"></span>
    </label>
    <?php rooftopDocPreview($row78[$p . 'ElectricBill'] ?? ''); ?>
</div>

<script>
(function () {
    function syncRooftopDocWraps() {
        document.querySelectorAll('[data-rooftop-doc-yn]').forEach(function (sel) {
            var name = sel.getAttribute('name') || '';
            var wrapId = name.replace(/Yn$/, 'Wrap');
            var wrap = document.getElementById(wrapId);
            if (!wrap) {
                return;
            }
            if (sel.value === 'Yes') {
                wrap.classList.add('is-visible');
            } else {
                wrap.classList.remove('is-visible');
            }
        });
    }
    document.addEventListener('change', function (e) {
        if (e.target && e.target.getAttribute('data-rooftop-doc-yn')) {
            syncRooftopDocWraps();
        }
    });
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', syncRooftopDocWraps);
    } else {
        syncRooftopDocWraps();
    }
})();
</script>
