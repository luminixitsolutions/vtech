<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Field Service Report</title>

<style>
*{ font-family: Arial, Helvetica, sans-serif; box-sizing:border-box; }

.page{
  width: 900px;
  margin: auto;
  padding: 15px;
  border: 1px solid #000;
}

/* HEADER */
.header{
  display:flex;
  justify-content:space-between;
  align-items:flex-start;
}

.logo{
  font-size:26px;
  font-weight:bold;
  border:1px solid #000;
  padding:10px 20px;
}

.header-right{
  text-align:right;
  font-size:12px;
}

.title{
  text-align:center;
  font-size:20px;
  font-weight:bold;
  margin:10px 0;
}

/* SECTION MAIN BOX */
.box{
  border:1px solid #000;
  margin-top:10px;
}

/* SECTION HEADING */
.box-title{
  font-weight:bold;
  font-size:13px;
  border-bottom:1px solid #000;
  padding:6px 10px;
}

/* CONTENT AREA */
.box-body{
  padding:8px 10px;
}

/* SPLIT 2 COLUMN INSIDE BOX */
.split{
  display:flex;
}

/* LEFT + RIGHT halves */
.left{ width:50%; padding-right:10px; }
.right{ width:40%; padding-left:10px; }

/* FIELD ROW STYLE */
.row{
  font-size:13px;
  padding:2px 0;
  display:flex;
}

.row b{
  width:190px;
  display:inline-block;
}

/* 50/50 columns inside body */
.cols{
  display:flex;
}
.col{
  width:50%;
  padding:3px 0;
}

/* label width */
.line b{
  width:210px;
  display:inline-block;
}

.line{
  font-size:13px;
  margin:2px 0;
}

/* PHOTOS */
.photos{
  display:grid;
  grid-template-columns:repeat(6,1fr);
  gap:6px;
  margin-top:8px;
}

.photo{
  border:1px solid #000;
  height:90px;
  font-size:11px;
  display:flex;
  align-items:center;
  justify-content:center;
}

/* FOOTER */
.footer{
  text-align:center;
  font-size:12px;
  margin-top:10px;
}

/* PRINT */
@media print{
  #printBtn{ display:none; }
  .page{ width:100%; border:none; }
}
</style>
</head>

<body>

<button id="printBtn" onclick="window.print()">Print</button>

<div class="page">

  <!-- HEADER -->
  <div class="header">
    <div class="logo">VTECH</div>

    <div class="header-right">
      GSTIN: 27AAICV1402D1ZU<br>
      Registered Office: Shub Vinayak Kumbharpura, Badkas Chowk, Small Aychit<br>
      Mandir, Mahal, Nagpur-440032 (MH)<br>
      Email : vtech.enquiry@gmail.com
    </div>
  </div>

  <div class="title">Field Service Report</div>

  <!-- TOP BIG BOX -->
  <div class="box">

   <div class="box-title" style="display:flex;">
    <div style="width:50%;">Notification / Work Order Info</div>
    <div style="width:40%; text-align:left; padding-left:10px;">
      Complaint Details
    </div>
  </div>

    <div class="box-body split">

      <!-- LEFT -->
      <div class="left">

        <div class="row"><b>Work Order No. :</b> OPL250908298</div>
        <div class="row"><b>Beneficiary / Serial ID :</b> KM0501508</div>
        <div class="row"><b>Date of Visit :</b> 09-09-2025 04:20 PM</div>
        <div class="row"><b>Customer Name :</b> Hiraman Sukhdeo Narule</div>
        <div class="row"><b>Contact No :</b> 7030649450</div>
        <div class="row"><b>Alternate No :</b> 7030649450</div>
        <div class="row"><b>Address :</b> Mangli, Nagpur, Maharashtra</div>

      </div>

      <!-- RIGHT -->
      <div class="right">

        <div class="row"><b>Book Date & Time :</b> 08-Sep-2025 05:33 PM</div>
        <div class="row"><b>Call Type :</b> Break Down</div>
        <div class="row"><b>Engineer :</b> Rohit-MH (Solar)</div>
        <div class="row"><b>Job Status :</b> Closed</div>
        <div class="row"><b>Date of Visit :</b> 09-Sep-2025 04:20 PM</div>
        <div class="row"><b>Nature of Complaint :</b> Controller Error / Display Error</div>

      </div>

    </div>
    
    <hr>
      <div class="box-title" style="text-align:center;">Product Details</div>

    <div class="box-body cols">

      <div class="col">
        <div class="line"><b>Brand / OEM :</b> V-TECH</div>
        <div class="line"><b>Category :</b> Solar</div>
        <div class="line"><b>Pump Capacity :</b> 3 HP</div>
        <div class="line"><b>Pump Model :</b> Submersible Pump</div>
      </div>

      <div class="col">
        <div class="line"><b>Date of Installation :</b> —</div>
        <div class="line"><b>Array Voltage (VDC) :</b> —</div>
        <div class="line"><b>Array Current :</b> —</div>
        <div class="line"><b>Weather :</b> Cleaned</div>
      </div>

    </div>
    
    <hr>
    

    <div class="box-title" style="text-align:center;">General Check Point</div>

    <div class="box-body cols">

      <div class="col">

        <div class="line"><b>RMS No. / Networking :</b> YES</div>
        <div class="line"><b>Controller Sr. No :</b> —</div>
        <div class="line"><b>Motor Sr. No :</b> —</div>
        <div class="line"><b>Pump Sr. No :</b> —</div>
        <div class="line"><b>Pump Installed Depth :</b> —</div>
        <div class="line"><b>Water Level :</b> —</div>
        <div class="line"><b>Complaint Type :</b> After Warranty</div>
        <div class="line"><b>Resistance of Motor in Disconnected Condition :</b> L1-L2</div>

      </div>

      <div class="col">

        <div class="line"><b>Source (AC / DC) :</b> DC</div>
        <div class="line"><b>Pump Head :</b> —</div>
        <div class="line"><b>Pipe Length :</b> —</div>
        <div class="line"><b>Water Status :</b> Cleaned Water</div>
        <div class="line"><b>RPM Status :</b> —</div>
        <div class="line"><b>Type Failure :</b> —</div>
        <div class="line"><b>Failure Type :</b> Manufacturing Fault</div>

      </div>

    </div>
    <hr>
    
    <div class="box-title">Field Observations & Remarks</div>
    <div class="box-body">Controller box not working</div>
  <hr>
  <div class="box-title">Action Taken By Representative</div>
    <div class="box-body">3HP site ok controller kbl joint parameter update site ok</div>
    <hr>
    <div class="box-title">Customer Remarks</div>
    <div class="box-body">Happy</div>
    <hr>
    <div class="box-title">Work Photos</div>

    <div class="box-body photos">
      <div class="photo">PHOTO 1</div>
      <div class="photo">PHOTO 2</div>
      <div class="photo">PHOTO 3</div>
      <div class="photo">PHOTO 4</div>
      <div class="photo">PHOTO 5</div>
      <div class="photo">ATR PHOTO</div>
    </div>
  </div>


  <div class="footer">
    Page 1 of 2<br>
    This is computer generated printout and does not require any signature
  </div>

</div>

</body>
</html>
