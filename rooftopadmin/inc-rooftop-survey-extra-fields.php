<?php
/**
 * Extra rooftop survey fields (shared by field + telephonic survey forms).
 * Requires: $surveyPrefix ('Field' or 'Tel'), $row7, $row78
 * Optional: $applicantCategory, $surveySection ('customer'|'technical'|'bank'|'all')
 */
$p = $surveyPrefix ?? 'Field';
$section = $surveySection ?? 'all';
$roofType = $row78[$p . 'RoofType'] ?? '';
$earthingDistance = $row78[$p . 'EarthingDistance'] ?? '';
$phase1ph = $row78[$p . 'Phase1ph'] ?? '';
$ongridKw = $row78[$p . 'OngridKW'] ?? '';
$bankDetailsFile = $row78[$p . 'BankDetails'] ?? '';
$bankOldName = $p . 'BankDetailsOld';
$fullAddress = trim((string) ($row7['Address'] ?? ''));
$applicationNo = trim((string) ($row7['BeneficiaryId'] ?? ''));
$mobile1 = trim((string) ($row7['Phone'] ?? ''));
$mobile2 = trim((string) ($row7['Phone2'] ?? ''));
$categoryLabel = trim((string) ($applicantCategory ?? ''));

if ($section === 'customer' || $section === 'all') {
?>
<div class="form-group col-md-3">
<label class="form-label">Application Number</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($applicationNo, ENT_QUOTES, 'UTF-8'); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label">Full Address of Beneficiary</label>
<textarea class="form-control" rows="2" readonly><?php echo htmlspecialchars($fullAddress, ENT_QUOTES, 'UTF-8'); ?></textarea>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Category of Applicant</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($categoryLabel, ENT_QUOTES, 'UTF-8'); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Mobile Number 1</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($mobile1, ENT_QUOTES, 'UTF-8'); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Mobile Number 2</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($mobile2, ENT_QUOTES, 'UTF-8'); ?>" readonly>
<div class="clearfix"></div>
</div>
<?php
}

if ($section === 'technical' || $section === 'all') {
?>
<div class="form-group col-md-3">
<label class="form-label">Ongrid KW Capacity</label>
<input type="text" class="form-control" name="<?php echo $p; ?>OngridKW" value="<?php echo htmlspecialchars($ongridKw, ENT_QUOTES, 'UTF-8'); ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Roof Type (Slab/Tin/Kavelu)</label>
<select class="form-control" name="<?php echo $p; ?>RoofType" id="<?php echo $p; ?>RoofType">
<option value="">Select</option>
<option value="Slab" <?php if ($roofType === 'Slab') { ?> selected <?php } ?>>Slab</option>
<option value="Tin" <?php if ($roofType === 'Tin') { ?> selected <?php } ?>>Tin</option>
<option value="Kavelu" <?php if ($roofType === 'Kavelu') { ?> selected <?php } ?>>Kavelu</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Distance from rooftop system to earthing (in meters)</label>
<input type="text" class="form-control" name="<?php echo $p; ?>EarthingDistance" value="<?php echo htmlspecialchars($earthingDistance, ENT_QUOTES, 'UTF-8'); ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Phase 1ph (Yes/No)</label>
<select class="form-control" name="<?php echo $p; ?>Phase1ph" id="<?php echo $p; ?>Phase1ph">
<option value="">Select</option>
<option value="Yes" <?php if ($phase1ph === 'Yes') { ?> selected <?php } ?>>Yes</option>
<option value="No" <?php if ($phase1ph === 'No') { ?> selected <?php } ?>>No</option>
</select>
<div class="clearfix"></div>
</div>
<?php
}

if ($section === 'bank' || $section === 'all') {
?>
<div class="form-group col-md-6 maindoc">
<label class="form-label">Bank Details</label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="<?php echo $p; ?>BankDetails" style="opacity: 1;">
<input type="hidden" name="<?php echo $bankOldName; ?>" value="<?php echo htmlspecialchars($bankDetailsFile, ENT_QUOTES, 'UTF-8'); ?>" id="<?php echo $bankOldName; ?>">
<span class="custom-file-label"></span>
</label>
<?php if ($bankDetailsFile !== '') { ?>
<span id="show_<?php echo $p; ?>_bank">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3">
<a href="../uploads/<?php echo htmlspecialchars($bankDetailsFile, ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><?php echo htmlspecialchars($bankDetailsFile, ENT_QUOTES, 'UTF-8'); ?></a>
</div>
</span>
<?php } ?>
</div>
<?php
}
