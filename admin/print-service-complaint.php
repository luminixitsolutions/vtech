<?php
session_start();
include_once 'config.php';
require_once __DIR__ . '/inc-print-service-complaint-data.php';

$complaintId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$data = printServiceComplaintLoad($complaintId);

if (!$data) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><body><h1>Complaint not found</h1><p>No service complaint record exists for this id.</p></body></html>';
    exit;
}

$d = $data;
$photos = $d['photos'];
$company = $d['companyHeader'] ?? printServiceComplaintDefaultCompanyHeader();
$companyLogoSrc = !empty($company['logo_is_upload'])
    ? '../uploads/' . $company['logo']
    : 'logo.jpg';
$customerCareNo = printServiceComplaintCompanyCareNo($company);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Field Service Report - <?php echo htmlspecialchars($d['workOrderNo']); ?></title>
<style>
*{ font-family: Arial, Helvetica, sans-serif; box-sizing: border-box; }
body{ margin: 0; padding: 10px; background: #f5f5f5; }
#printBtn{
  position: fixed;
  top: 12px;
  right: 12px;
  z-index: 10;
  padding: 8px 16px;
  cursor: pointer;
}
.page{
  width: 900px;
  margin: auto;
  padding: 15px;
  border: 1px solid #000;
  background: #fff;
}
.header{
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 12px;
}
.logo img{
  max-width: 140px;
  max-height: 70px;
}
.header-right{
  text-align: right;
  font-size: 12px;
  line-height: 1.45;
}
.header-right .company-name{
  font-size: 22px;
  font-weight: bold;
}
.report-heading{
  border: 1px solid #000;
  background: #e8e8e8;
  text-align: center;
  font-size: 18px;
  font-weight: bold;
  padding: 8px 12px;
  margin-top: 10px;
  letter-spacing: 0.3px;
}
.box{
  border: 1px solid #000;
  margin-top: 0;
  border-top: none;
}
.box-title{
  font-weight: bold;
  font-size: 13px;
  border-bottom: 1px solid #000;
  padding: 6px 10px;
  text-transform: uppercase;
}
.box-title.center{ text-align: center; }
.box-title.gray{ background: #e8e8e8; }
.split-headings{
  display: flex;
  padding: 0;
  border-bottom: 1px solid #000;
}
.split-headings > div{
  width: 50%;
  text-align: center;
  padding: 6px 10px;
  font-weight: bold;
  font-size: 13px;
  text-transform: uppercase;
  background: #e8e8e8;
}
.split-headings > div:first-child{
  border-right: 1px solid #000;
}
.box-body{ padding: 8px 10px; }
.split{ display: flex; }
.left, .right{ width: 50%; }
.left{ padding-right: 10px; border-right: 1px solid #000; }
.right{ padding-left: 10px; }
.row{
  font-size: 13px;
  padding: 3px 0;
  display: flex;
  gap: 6px;
}
.row b{
  min-width: 190px;
  display: inline-block;
  text-transform: uppercase;
}
.cols{ display: flex; }
.col{ width: 50%; padding: 3px 0; }
.col:first-child{ border-right: 1px solid #000; padding-right: 10px; }
.col:last-child{ padding-left: 10px; }
.line{
  font-size: 13px;
  margin: 2px 0;
  display: flex;
  gap: 6px;
}
.line b{
  min-width: 210px;
  display: inline-block;
  text-transform: uppercase;
}
.hr{ border-top: 1px solid #000; margin: 0; }
.full-row{
  font-size: 13px;
  padding: 6px 10px;
  border-top: 1px solid #000;
}
.full-row b{ text-transform: uppercase; }
.two-col-row{
  display: flex;
  border-top: 1px solid #000;
}
.two-col-row > div{
  width: 50%;
  padding: 8px 10px;
  font-size: 13px;
}
.two-col-row > div:first-child{ border-right: 1px solid #000; }
.two-col-row b{ text-transform: uppercase; display: block; margin-bottom: 4px; }
.photos-header{
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.photos{
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 6px;
  margin-top: 8px;
}
.photo{
  border: 1px solid #000;
  min-height: 90px;
  position: relative;
  overflow: hidden;
  background: #fafafa;
}
.photo img{
  width: 100%;
  height: 90px;
  object-fit: cover;
  display: block;
}
.photo .meta{
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,.55);
  color: #fff;
  font-size: 9px;
  line-height: 1.2;
  padding: 2px 3px;
}
.footer{
  text-align: center;
  font-size: 12px;
  margin-top: 10px;
}
@media print{
  #printBtn{ display: none; }
  body{ background: #fff; padding: 0; }
  .page{ width: 100%; border: none; }
}
</style>
</head>
<body>

<button id="printBtn" onclick="window.print()">Print</button>

<div class="page">

  <div class="header">
    <div class="logo">
      <img src="<?php echo htmlspecialchars($companyLogoSrc); ?>" alt="<?php echo htmlspecialchars($company['name']); ?>">
    </div>
    <div class="header-right">
      <?php if (!empty($company['gstin'])) { ?>
      <div><strong>GSTIN:</strong> <?php echo htmlspecialchars($company['gstin']); ?></div>
      <?php } ?>
      <div class="company-name"><?php echo htmlspecialchars($company['name']); ?></div>
      <?php if (!empty($company['address'])) { ?>
      <div>Registered Office: <?php echo nl2br(htmlspecialchars($company['address'])); ?></div>
      <?php } ?>
      <?php if (!empty($company['email'])) { ?>
      <div>Email: <?php echo htmlspecialchars($company['email']); ?></div>
      <?php } ?>
      <?php if ($customerCareNo !== '') { ?>
      <div>Customer Care No.: <?php echo htmlspecialchars($customerCareNo); ?></div>
      <?php } ?>
    </div>
  </div>

  <div class="report-heading">Field Service Report</div>

  <div class="box">
    <div class="box-title split-headings">
      <div>Notification / Work Order Info</div>
      <div>Complaint Details</div>
    </div>

    <div class="box-body split">
      <div class="left">
        <div class="row"><b>Notification/Work Order No. :</b> <span><?php echo htmlspecialchars($d['workOrderNo']); ?></span></div>
        <div class="row"><b>Beneficiary/Serial ID :</b> <span><?php echo htmlspecialchars($d['beneficiaryId']); ?></span></div>
        <div class="row"><b>Date of Visit :</b> <span><?php echo htmlspecialchars($d['visitDate']); ?></span></div>
        <div class="row"><b>Customer's Name :</b> <span><?php echo htmlspecialchars($d['customerName']); ?></span></div>
        <div class="row"><b>Contact No :</b> <span><?php echo htmlspecialchars($d['contactNo']); ?></span></div>
        <div class="row"><b>Alternate No :</b> <span><?php echo htmlspecialchars($d['alternateNo']); ?></span></div>
        <div class="row"><b>Address :</b> <span><?php echo htmlspecialchars($d['address']); ?></span></div>
      </div>
      <div class="right">
        <div class="row"><b>Book Date &amp; Time :</b> <span><?php echo htmlspecialchars($d['bookDate']); ?></span></div>
        <div class="row"><b>Call Type :</b> <span><?php echo htmlspecialchars($d['callType']); ?></span></div>
        <div class="row"><b>Engineer :</b> <span><?php echo htmlspecialchars($d['engineer']); ?></span></div>
        <div class="row"><b>Job Status :</b> <span><?php echo htmlspecialchars($d['jobStatus']); ?></span></div>
        <div class="row"><b>Date of Visit :</b> <span><?php echo htmlspecialchars($d['visitDate']); ?></span></div>
        <div class="row"><b>Nature of Complaint :</b> <span><?php echo htmlspecialchars($d['natureOfComplaint']); ?></span></div>
      </div>
    </div>

    <div class="hr"></div>
    <div class="box-title center gray">Product Details</div>
    <div class="box-body cols">
      <div class="col">
        <div class="line"><b>Brand/OEM :</b> <span><?php echo htmlspecialchars($d['brand']); ?></span></div>
        <div class="line"><b>Category :</b> <span><?php echo htmlspecialchars($d['category']); ?></span></div>
        <div class="line"><b>Product :</b> <span><?php echo htmlspecialchars($d['product']); ?></span></div>
        <div class="line"><b>Pump Capacity :</b> <span><?php echo htmlspecialchars($d['pumpCapacity']); ?></span></div>
      </div>
      <div class="col">
        <div class="line"><b>Date of Installation :</b> <span><?php echo htmlspecialchars($d['installationDate']); ?></span></div>
        <div class="line"><b>Array Voltage (VDC) :</b> <span><?php echo htmlspecialchars($d['arrayVoltage']); ?></span></div>
        <div class="line"><b>Array Current :</b> <span><?php echo htmlspecialchars($d['arrayCurrent']); ?></span></div>
        <div class="line"><b>Weather :</b> <span><?php echo htmlspecialchars($d['weather']); ?></span></div>
      </div>
    </div>

    <div class="box-title center gray">General Check Point</div>
    <div class="box-body cols">
      <div class="col">
        <div class="line"><b>RMS No./Network :</b> <span><?php echo htmlspecialchars($d['rms']); ?></span></div>
        <div class="line"><b>Controller Sr. No. :</b> <span><?php echo htmlspecialchars($d['controllerSrNo']); ?></span></div>
        <div class="line"><b>Motor Sr. No. :</b> <span><?php echo htmlspecialchars($d['motorSrNo']); ?></span></div>
        <div class="line"><b>Pump Sr. No. :</b> <span><?php echo htmlspecialchars($d['pumpSrNo']); ?></span></div>
        <div class="line"><b>Pump Location :</b> <span><?php echo htmlspecialchars($d['pumpLocation']); ?></span></div>
        <div class="line"><b>Pump Installed Depth :</b> <span><?php echo htmlspecialchars($d['pumpInstalledDepth']); ?></span></div>
        <div class="line"><b>Water Level Head :</b> <span><?php echo htmlspecialchars($d['waterLevelHead']); ?></span></div>
        <div class="line"><b>Lowering of Pump :</b> <span><?php echo htmlspecialchars($d['loweringOfPump']); ?></span></div>
        <div class="line"><b>Complaint Type :</b> <span><?php echo htmlspecialchars($d['complaintType']); ?></span></div>
      </div>
      <div class="col">
        <div class="line"><b>Source (AC/DC) :</b> <span><?php echo htmlspecialchars($d['sourceAcDc']); ?></span></div>
        <div class="line"><b>Pump Head :</b> <span><?php echo htmlspecialchars($d['pumpHead']); ?></span></div>
        <div class="line"><b>Pipe Length :</b> <span><?php echo htmlspecialchars($d['pipeLength']); ?></span></div>
        <div class="line"><b>Pipe Size Inch :</b> <span><?php echo htmlspecialchars($d['pipeSizeInch']); ?></span></div>
        <div class="line"><b>Water Status :</b> <span><?php echo htmlspecialchars($d['waterStatus']); ?></span></div>
        <div class="line"><b>RPM Status :</b> <span><?php echo htmlspecialchars($d['rpmStatus']); ?></span></div>
        <div class="line"><b>Cable Length of Pole :</b> <span><?php echo htmlspecialchars($d['cableLength']); ?></span></div>
        <div class="line"><b>Fault Type :</b> <span><?php echo htmlspecialchars($d['faultType']); ?></span></div>
      </div>
    </div>

    <div class="full-row"><b>Resistance of Motor in Disconnected Condition :</b> L1-L2 <?php echo htmlspecialchars($d['motorResistance']); ?></div>

    <div class="two-col-row">
      <div><b>Actual Problem Found :</b> <?php echo nl2br(htmlspecialchars($d['actualProblem'])); ?></div>
      <div><b>Provided Solution :</b> <?php echo nl2br(htmlspecialchars($d['providedSolution'])); ?></div>
    </div>

    <div class="two-col-row">
      <div><b>Field Observations and Remarks :</b> <?php echo nl2br(htmlspecialchars($d['fieldObservations'])); ?></div>
      <div><b>Action Taken by Representative :</b> <?php echo nl2br(htmlspecialchars($d['actionTaken'])); ?></div>
    </div>

    <div class="full-row"><b>Customer Remarks :</b> <?php echo nl2br(htmlspecialchars($d['customerRemarks'])); ?></div>

    <div class="box-title photos-header">
      <span>Work Photos</span>
      <span>ATR Photo</span>
    </div>
    <?php if (!empty($photos)) { ?>
    <div class="box-body photos">
      <?php foreach ($photos as $photo) {
          $meta = [];
          if (!empty($photo['date'])) {
              $meta[] = printServiceComplaintFormatDate($photo['date'], true);
          }
          if ($photo['latitude'] !== '' || $photo['longitude'] !== '') {
              $meta[] = trim($photo['latitude'] . ', ' . $photo['longitude'], ', ');
          }
          ?>
      <div class="photo">
        <img src="../uploads/<?php echo htmlspecialchars($photo['file']); ?>" alt="Work photo">
        <?php if (!empty($meta)) { ?>
        <div class="meta"><?php echo htmlspecialchars(implode(' | ', $meta)); ?></div>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
    <?php } ?>
  </div>

  <div class="footer">
    This is a computer generated printout and does not require any signature
  </div>

</div>

</body>
</html>
