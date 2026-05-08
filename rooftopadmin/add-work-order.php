<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Work-Order";
$Page = "Add-Work-Order";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Work Order
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
    <script src="ckeditor/ckeditor.js"></script>
</head>

<body>
    <style type="text/css">
    .password-tog-info {
        display: inline-block;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        position: absolute;
        right: 50px;
        top: 30px;
        text-transform: uppercase;
        z-index: 2;
    }
    </style>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_rooftop_work_order WHERE id='$id'";
$row7 = getRecord($sql7);

if($_REQUEST["action"]=="deletelink")
{
  $id = $_REQUEST["id"];
  $pid = $_REQUEST["pid"];
  $sql11 = "DELETE FROM tbl_wo_references WHERE id = '$id'";
  $conn->query($sql11);
?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
       window.location.href="add-work-order.php?id=<?php echo $pid;?>";
    </script>
<?php }

if($_GET['id'] == ''){
    $TermsCondition = '<table>
<tbody>
<tr>
<td width="38">
<p><strong>Sr.</strong></p>
</td>
<td width="343">
<p><strong>Item Particulars</strong></p>
</td>
<td width="44">
<p><strong>Qty</strong></p>
</td>
<td width="47">
<p><strong>Unit</strong></p>
</td>
<td width="57">
<p><strong>Rate</strong></p>
</td>
<td width="89">
<p><strong>Amount</strong></p>
</td>
</tr>
<tr>
<td width="38">
<p>&nbsp;</p>
</td>
<td width="343">
<p>Work order for Beneficiary Selection, Survey , Material unloading at farmer site, Civil material purchasing, RCC Foundation ,Installation, Commissioning report signed</p>
<p>and JCR signed from</p>
</td>
<td colspan="4" width="238">
<p><strong>&nbsp;</strong></p>
<p>Annexure sheet Enclosed</p>
</td>
</tr>
<tr>
<td width="41">
<p>&nbsp;</p>
</td>
<td width="326">
<p>respective Authority, Commissioning report uploading, Bill proceeding from PO and Bill Submitting to MEDA office and bill Forwarded to District office <strong>of 3HP/5HP/7.5HPSOLAR WATER AC/DC SURFACE/</strong></p>
<p><strong>Submersible Pump Set for Solar Water Pumping Set and Controller with Remote monitoring system as per EESL-2 &amp; MNRE 2020-19 Guidelines </strong>for various locations as per</p>
<p>beneficiary list issued from department</p>
<p><strong>Location- BHANDARA AND GONDIA DISTRICT</strong></p>
</td>
<td width="252">
<p>&nbsp;</p>
</td>
</tr>
</tbody>
</table>';

$TermsCondition2 = '<ol>
<li><strong><u>Approval</u></strong>: The installation and commissioning and JCR Signing should be strictly of EESL-2 (tender as mentioned above) &amp; MNRE Specifications as per latest guidelines</li>
</ol>
<p>&nbsp;</p>
<ol start="2">
<li><strong><u>Penalty</u></strong>: Liquidate damages @ 25% per week shall be levied subject to a maximum of 5% of total order value only would be levied in case of delay in installation contracted period as per ESSL &amp;MNRE agreement arrangement done with our clients.</li>
</ol>
<p>&nbsp;</p>
<ol start="3">
<li><strong><u>Delivery</u></strong>: Total Quantity of installation shall be delivered as per schedule mentioned in annexure sheet of priority basis. Site Inspection call shall be given 3days in advance and you having to available at the time of</li>
</ol>
<p>&nbsp;</p>
<ol start="4">
<li><strong><u>Inspection</u>: </strong>You shall be available at the time of inspection as per the requirement of EESL &amp;MNRE. Please note that inspection call shall be raised 3days in advance so that timely installation can be</li>
</ol>
<p>&nbsp;</p>
<p><strong>5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong><u>Arbitration: -</u> </strong>If at any time any question, and dispute and /or differences whatsoever shallarise between the parties due to any conditions and failing amicable settlement the same shall be referred to an arbitrator under the Indian Arbitration &amp; Conciliation Act 1996 or any statutory modifications of the same prevalent at the time. The venue of such arbitration will be Nagpur.</p>
<p>&nbsp;</p>
<ol start="6">
<li><strong><u>Jurisdiction: -</u></strong> Any dispute arising out of the order against this offer letter shall be subject to the jurisdiction of the court in the city of</li>
</ol>
<p>&nbsp;</p>
<p><strong>7.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><strong><u>Cancellation: -</u> </strong>Work Order once placed will not be cancelled except with our written consent and after compensating the loss, if any to us.</p>
<p>&nbsp;</p>
<h2>8.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Consignee Address:</h2>
<p><strong>Consignee :</strong></p>
<p><strong>VTECH ENGINEERS</strong></p>
<p>Nagpur</p>
<p>&nbsp;</p>
<h2>9.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Billing Address:</h2>
<p><strong>Buyer: VTECH ENGINEERS,</strong></p>
<p>Plot No.25,26 Rishabh Industrial Estate</p>
<p>Ph.20 Bhandara Road Kamptee Kapsi (Bujurg)</p>
<p>Nagpur-441104</p>
<p>&nbsp;</p>
<ol start="10">
<li><strong>Invoice copy Dispatch Address: </strong>- VTECH ENGINEERS</li>
</ol>
<p>Shubh Vinayak Building Ground floor</p>
<p>Near small Ayachit Mandir Badkas</p>
<p>Chowk Mahal Nagpur-440032.</p>
<p>Contact-8484937592</p>
<p>&nbsp;</p>';

$TermsCondition3 = '<ol>
<li>Installer should convince farmers to choose VTECH and get them registered on the government portal. Installer has to get farmers to submit monthly consent of farmers in their favor to MEDA/EESL, which will give Notice to Proceed (NTP) for this. Currently, MEDA /EESL has provided the farmer list to VTECH ENGINEERS In future, if there is any requirement to generate new farmers, then the same would be intimated to the installer.</li>
</ol>
<p>&nbsp;</p>
<ol start="2">
<li>Installer should work exclusively with VTECH ENGINEERS for the duration of the specific project for which agreement is</li>
</ol>
<p>&nbsp;</p>
<ol start="3">
<li>Installer should conduct site survey of the registered farmers with Lineman on Third-party inspector. He should follow the rules, regulations &amp; format of survey as required by the government</li>
</ol>
<p>&nbsp;</p>
<ol start="4">
<li>Installation and Commissioning of solar photovoltaic water pumping system shall be done by the Installer as per the details provided by MEDA/EESL.</li>
</ol>
<p>&nbsp;</p>
<ol start="5">
<li>Installer will give receiving of the material on the Transport Bilty and it will be cross signed by the Truck In case installer doesn&rsquo;t give receiving, it will be assumed that it is correctly received by him. Any discrepancy in stock will be on account of installer and will be adjusted from his account.</li>
</ol>
<p>&nbsp;</p>
<ol start="6">
<li><strong><u>Installer has to mention on Bilty, the number of Pieces received by him and Driver will also sign on the</u> <u>same Bilty. Shortages / Damages will only be given free of cost if the same is signed on Bilty.</u> </strong>Otherwise , all Shortages / Damages reported thereafter will be adjusted from the Installers</li>
</ol>
<p>&nbsp;</p>
<ol start="7">
<li>Installer will do Foundation, Installation as per MEDA/ MSEDCL &amp; MNRE Kusum standards and foundation / Civil work as given by VTECH &amp; approved by MEDA/MSEDCL Installer should follow all quality compliances as per MNRE Kusum Tender, MEDA/MSEDCL standards and VTECH quality standards mentioned in Annexure-1.</li>
</ol>
<p>&nbsp;</p>
<ol start="8">
<li>The installer will verify that there is no electrical connection on the customer site, where solar pump is getting installed and ensure that the same site is mentioned in the Farmers Application in site survey. If installer installs Pump at the site/Qila, where electrical connection is there, no payment shall be given for any installation and system will have to be uninstalled and returned back to the</li>
</ol>
<p>&nbsp;</p>
<ol start="9">
<li>Installer will submit video of each installation for release of Installer to take proper care of points written in the Annexure 1 for Installation, Material &amp; App.</li>
</ol>
<p>&nbsp;</p>
<ol start="10">
<li>VTECH will do quality checks of installations. VTECH has to re-install the pump by itself to correct the Installer&rsquo;s issues, then cost towards re-installation the pump by itself to correct the Installer&rsquo;s issues, then cost towards re-installation will be adjusted from the installer&rsquo;s account.</li>
</ol>
<p>&nbsp;</p>
<ol start="11">
<li>Installer will be responsible for all processes related to Payments-Making files, Getting it signed from Customer/Government officials, getting Inspection &amp; signs done from district Government</li>
</ol>
<p>&nbsp;</p>
<ol start="12">
<li>Installer has to install the system within 2 days of receipt of material and should get all documents signed from beneficiary, geo-tag photos updated. Installer has to get the installed solar pump inspected and approved from PO/Govt. Officials within 3-4 days after installation. In case of delay in material Installation and approvals, penalty will be charged on the If there are any issues found in Foundation/installation, then installer is supposed to rectify it again, at no extra cost to the company.</li>
</ol>
<p>&nbsp;</p>
<ol start="13">
<li>If during transportation &amp; installation to farmer site, there is damage or breakage to the solar pump material, then the amount of damaged material will be adjusted from the Installer&rsquo;s</li>
</ol>
<p>&nbsp;</p>
<ol start="14">
<li>All serial of Panels / Controllers/Pumps/ RMU IMEL No, foundation &amp; installation photos will be put on VTECH ENGINEERS has to do it on his own then the system will not be considered installed and no payment will be done to the Installer.</li>
</ol>
<p>&nbsp;</p>
<ol start="15">
<li>All RMU&rsquo;s should be configured, activated on MEDA/EESL portal, Kusum MNRE Portal and working</li>
</ol>
<p>&nbsp;</p>
<ol start="16">
<li>The installer has to maintain the system free of cost for 3 months from the date of installation, and should attend calls within 2 days of the complaint. Replacement of Faculty component shall be supplied by VTECH on receipt of defective VTECH shall enter into a separate Service contract with the Installer, if it is satisfied with Installers services.</li>
</ol>
<p>&nbsp;</p>
<ol start="17">
<li>In case Installer does not repair the pump and send its manpower for rectification within 2 days, 500 per pump / day penalty shall be imposed till the solar pump comes back to satisfactory condition.</li>
</ol>
<p>&nbsp;</p>
<ol start="18">
<li>In case, the Installer is not providing its services as required by the company, the company reserves the right to suspend its The Installer is not an employee of VTECH and is a separate legal entity.</li>
</ol>
<p>&nbsp;</p>
<ol start="19">
<li>Installer shall comply with all applicable regulatory and statutory norms. Installer will have to obtain approval / NOC 9 wherever required) from appropriate government authority from implementing project in each selected village.</li>
</ol>
<p>&nbsp;</p>
<ol start="20">
<li>Submission of following reports by Installer &amp; any other additional requirement of MEDA/EESL or MNRE that arises in the duration of the project:</li>
</ol>
<p>&nbsp;</p>
<ul>
<li>Handling over certificate solar photovoltaic water pumping system signed by Farmer, Vender and duly certified by MEDA/EESL or MEDA/EESL appointed third party inspecting</li>
</ul>
<p>&nbsp;</p>
<ul>
<li>Performance report for 1 day after commissioning based on data received from remote monitoring system or data logger incase, where internet services are not available.</li>
</ul>
<p>&nbsp;</p>
<ul>
<li>An undertaking by the Installer certifying that the civil work will with stand the windspeed of 150km/ hour in all weather</li>
</ul>
<p>&nbsp;</p>
<ul>
<li>Submission of installation report as per prescribed format of MEDA/EESL, from ABC with RMS data and Geo- tagged</li>
</ul>
<p>&nbsp;</p>
<ul>
<li>After 1 month of Installation, RMU Data for 1 month (Online or Offline) will be provided by the Installer.</li>
</ul>
<p>&nbsp;</p>
<ul>
<li>Installer shall submit the detailed report and short 5-minute-high definition video per district including local training, awareness and sensitization campaigns, methodology for sustainable maintenance for further 5 years to beneficiaries with relevant photographs.</li>
</ul>
<p>&nbsp;</p>
<ul>
<li>Submission of completion report of each district to MEDA/EESL within 1 week of 100% completion of work as per allocation in district.</li>
</ul>
<p>&nbsp;</p>
<ul>
<li>Installer should submit weekly, monthly and quarterly installation &amp; commissioning reports to MEDA/EESL Divisional office and Head</li>
</ul>
<p>&nbsp;</p>
<ul>
<li>Installer should submit certificate (as per Prescribed format given by MEDA/EESL) and photographs of each pump system installer which must show complete installation setup with beneficiary and also upload details on web portal provided.</li>
</ul>
<p>&nbsp;</p>
<ul>
<li>Installation data should be punched in web platform of MEDA/EESL as per terms and conditions provided by MEDA/EESL/MNRE.</li>
</ul>
<p>&nbsp;</p>
<ol start="21">
<li>Beneficiary Selection, Survey, Total Transportation, Foundation, Installation, Inspection and Bill Submission to respected MEDA Office charge per system as per installed Solar Pump is as TDS will be adjusted as per the norms from the Installer billing.</li>
</ol>
<p>&nbsp;</p>
<ol start="22">
<li>90% Payment shall be given when Files are signed by MEDA office, Serial Nos &amp; Photos on App are uploaded and RMU integration on the MEDA/EESL portal is done. Last 10% Payment will be done after 3 month of Installation and full payment received from the head office of MEDA/EESL.</li>
</ol>
<p>&nbsp;</p>
<ol start="23">
<li>This agreement comes under jurisdiction of Courts of</li>
</ol>
<p>&nbsp;</p>
<ol start="24">
<li>The Installer Accepts all conditions unconditionally a laid down in this contract including Annexure 1.</li>
</ol>';

$TermsCondition4 = '<p style="text-align:justify">इंस्टालेशन का वीडियोहर फार्मरका भेजें, डजसर्ेंयेसब डिख रहा हो। -</p>

<p style="text-align:justify">१) पैनल to पैनल की वायररंग लटकती रहती हैं। उसेके बल टाई सेटाइट टाई करे । इसके ढ़ीलेहोनेसेकनेक्शन loose हो जातेहै। पैनल to पैनल वायररंग MC ४ कनेक्टर सेज्वाइन करे ।</p>

<p style="text-align:justify">२ ) पैनल इंस्टालेशन की जगह का चुनाव सही करे । न तो येपेड़ के नीचेलगा हुआ हो, न येिुसरेफार्मर की जर्ीन पर जा रही हो, और न येिुसरेपैनल स्टरक्चर पर शैिो आये। डकसी भी पैनल पर छाया नही ंपड़नी चाडहए।</p>

<p style="text-align:justify">३ ) िर ाइंग के डहसाब सेस्टरक्चर लगाएं. पैनल स्टरक्चर की िू री िू सरेस्टरक्चर से२२ फ़ीट होनी चाडहए। के बल की वायररंग ऐसी डहसाब सेिी गयी है। िर ाइंग केडहसाब सेयेफॉलो करे ।</p>

<p style="text-align:justify">४ ) िेखा गया हैंकी पैनल के नट बोल्ड ढीलेरहतेहैऔर पूरी तरह कसेनही ंजा रहेहैं। येशायि हाथ सेडकया जा रहेहैं-डजसकी वजह सेयेसर्स्या आ रही हैं। येशायि हाथ सेडकया जा रहेहैं- डजसकी वजह सेयेसर्स्या आ रही है। इसको चाबी सेटाइट करवाए।</p>

<p style="text-align:justify">५ ) फाउंिेशन िर ाइंग केडहसाब सेहो।</p>

<p style="text-align:justify">६ ) EARTHING CONNECTION अडिकतर जगह नही ंडकया जा रहा है, डजसकी वजह सेकरं ट की काफी कम्प्लेंट्स भी आ रही ंहैं। अडथिंग सारेस्टरक्चर की जरूर सेहो। अडथिंग केडलए पहलेगि्ढ़ा बनाएं ,उसर्ेंअडथिंग के डर्कल िाल कर , कॉपर रोि को उसर्ेगाड़ िे। १ साइि पर कॉपर रोि के छे ि र्ेंGI वायर टाइट सेकसेंऔर GI वायर को तीनो स्टरक्चर केडपलर के फाउंिेशन बोल्ड पर अच्छेसेटाइट करें ।</p>

<p style="text-align:justify">७ ) LIGHTNING ARRESTOR जरूर लगवाए।</p>

<p style="text-align:justify">८ ) पैनल हवा र्ेंनही ंउड़े,इसके सर्ािान के डलए इस बार पैनल क्लैप िीए गए है। हर पैनल को इन्स्टॉल करतेसर्य पैनल क्लैप का लगाना बहुत जरूरी होगा। अगर येनही ंलगतेहैं,तो पूरा इंस्टालेशन आपको िुबारा करना होगा क्ुुँकी येपैनल स्टरक्चर पर लगातेसर्य बीच र्ेंलगाए जातेहैं। इसका खास ध्यान रखें।</p>

<p style="text-align:justify">९ ) १ स्टरक्चर सेिू सरेस्टरक्चर की वायररंग को अंिरग्राउंि कर िे।</p>

<p style="text-align:justify">११ ) मोटर - कं टर ोलर की के बल, और मोटर की के बल जॉइंट बहुत खराब तरीके सेहो रहा हैं। तार छील कर टेप जो लगाया जा रहा है - वो सही तरीके सेनही ंहै। जॉइंट पर मोटर वायररंग केसाथ शाटटहो जा रही हैऔर ३ फे ज की र्ोटर डसफम १ या २ फे ज पर चल रही हैडजससेर्ोटर खराब हो जा रही है। इस का इंस्टालेशन के सर्य खास ध्यान िे।</p>

<p style="text-align:justify">१३ ) र्ोटर िालतेटाइर् पर वायर पर डबलकु ल लोि न िें, उसेढीला रखे। र्ोटर िालतेहुए पाडहलेरस्सेको टाइर् सेबांिे। र्ोटर के साथ डनचेजाती हुयी वायर को HDPE पाइप सेबीच बीच र्ेंरस्सेया टेप सेबांि िे। अभी की इंस्टालेशन र्ेंसभी लोि वायर पर आता हैऔर पूरी खी ंच जाती हैऔर काफी कं लेंट आती है।</p>

<p style="text-align:justify">१४ ) र्ोटर की वायररंग को HDPE क्लैप पर न लपेटे।</p>

<p style="text-align:justify">१५ ) पडहलेस्विच ऑफ करेऔर डफर MCB ऑफ करें । सीिेMCB ऑफ़ करनेपर, MCB खराब हो जाती है।</p>

<p style="text-align:justify">१६ ) कं टर ोलर केडिब्बेको लॉक्ि रखे। अगर पैरार्ीटर चैज भी करे, तो उसके बाि लॉक कर िे।</p>

<p style="text-align:justify">१५ ) अगर डकसान की VTECH र्ोटर खराब हो जाए , तो वह अपनी खुि की र्ोटर हर्ारेडसस्टर् र्ेंनही ंलगाए। कं टर ोलर खराब हो जायेगा।</p>

<p style="text-align:justify"><strong>VTECH App सीररयल नंबर और RMU</strong></p>

<p style="text-align:justify">१.App पर सीररयल No. स्कै न कर के अपलोि करे- पैनल , र्ोटर , कं टर ोलर, RMU , सीररयल No. हाथ सेडलख कर कं पनी को नही ंिे।</p>

<p style="text-align:justify">२. इस बार पैनल के पीछेभो डपलेस्टीकर पर सीररयल No डचपकायेगए है। काफी िुलीके ट नंबसमआतेजो नही ंआएं गेअगर आप स्कै न कर के अपलोि करेंगे।</p>

<p style="text-align:justify">३. फार्मर फोटो और सीररयल नंबर App पर अपलोि करें ।</p>

<p style="text-align:justify">४. इंस्टालेशन के टाइर् , RMU का हरी बत्ती आना जरुरी है।</p>

<p style="text-align:justify">५. 6 फोटो हर फॉर्मकी App पर अपलोि करे- फाउंिेशन , स्टरक्चर , कं टर ोलर , पानी आतेहुए , फॉर्मके साथ System ,अडथिंग</p>

<p style="text-align:justify">६. District पर फाइल बनानेका कार् इंस्टालर केस्कोप र्ेंहे।</p>

';


$Details = '<p>Dear Sir,</p>
<p>&nbsp;</p>
<p>We are pleased to place this work order Beneficiary selection, Survey ,Material unloading at farmer site, Civil material purchasing, RCC Foundation ,Installation, Commissioning report signed and JCR signed from respective Authority, Commissioning report uploading, Bill proceeding from PO and Bill Submitting to respective MEDA office bill Forwarded to District office <strong>of 3HP/5HP/7.5 HP SOLAR WATER AC/DC SURFACE/ Submersible PumpSet for Solar Water Pumping Set and Controller with Remote monitoring system as per EESL-2&amp; MNRE 2020-19 Guidelines </strong>for various locations as per beneficiary list issued from department on our behalf subject to the following annexure:</p>
<p>&nbsp;</p>
<p>Annexure &ldquo;A&rdquo;: Rate &amp; Qty&rsquo;s Details.;</p>
<p>Annexure &ldquo;B&rdquo;: Standard Terms &amp; Conditions</p>
<p>Annexure &ldquo;C&rdquo;: Commercial Terms &amp; Conditions</p>
<p>&nbsp;</p>';

$QtnSubject = "Quotation for Supply , Installation and commissioning of 3HP/5HP/7.5 HP/10 HP SOLAR WATER AC/DC SURFACE/ Submersible Pump Set for Solar Water Pumping Set and Controller with Remote monitoring system nd without Solar PV Module as per EESL-2& MNRE 2020-19 Guidelines upto JCC at PO office.";

$InvoiceDate = date('Y-m-d');
    $sql8 = "SELECT MAX(id) AS MaxId FROM tbl_rooftop_work_order";
$row8 = getRecord($sql8);
$MaxId = $row8['MaxId'] + 1;
$Invoice_No = "00".$MaxId;

}
else{
   $TermsCondition = $row7['TermsCondition'];
   $TermsCondition2 = $row7['TermsCondition2'];
   $TermsCondition3 = $row7['TermsCondition3'];
   $TermsCondition4 = $row7['TermsCondition4'];
   $Details = $row7['Details'];
   $QtnSubject = $row7['QtnSubject'];
   $InvoiceDate = $row7['InvoiceDate'];
$Invoice_No = $row7['InvoiceNo'];
}


   


if(isset($_POST['submit'])){
    $CustId = addslashes(trim($_POST["CustId"]));
     $CellNo = addslashes(trim($_POST["CellNo"]));
    $CustName = addslashes(trim($_POST["CustName"]));
$Status = 1;
$Address = addslashes(trim($_POST["Address"]));
$InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
$InvoiceDate = addslashes(trim($_POST["InvoiceDate"]));
$QtnSubject = addslashes(trim($_POST["QtnSubject"]));
$RefEnqNo = addslashes(trim($_POST["RefEnqNo"]));
$Details = addslashes(trim($_POST["Details"]));
$TermsCondition = addslashes(trim($_POST["TermsCondition"]));
$TermsCondition2 = addslashes(trim($_POST["TermsCondition2"]));
$TermsCondition3 = addslashes(trim($_POST["TermsCondition3"]));
$TermsCondition4 = addslashes(trim($_POST["TermsCondition4"]));
$Details = addslashes(trim($_POST["Details"]));
$CompId = addslashes(trim($_POST["CompId"]));
$KindAttn = addslashes(trim($_POST["KindAttn"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');


if($_GET['id']==''){
     $qx = "INSERT INTO tbl_rooftop_work_order SET CompId='$CompId',CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='$Status',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate = '$InvoiceDate',QtnSubject='$QtnSubject',Details='$Details',TermsCondition='$TermsCondition',TermsCondition2='$TermsCondition2',TermsCondition3='$TermsCondition3',TermsCondition4='$TermsCondition4',CreatedDate='$CreatedDate',CreatedBy='$user_id',RefEnqNo='$RefEnqNo',KindAttn='$KindAttn'";
  $conn->query($qx);
$WoId = mysqli_insert_id($conn);
 $number = count($_POST['Ref']);
  if($number > 0)  
            {  
                for($i=0; $i<$number; $i++)  
                {  
                     if(trim($_POST["Ref"][$i] != ''))  
                     {
                        $Links = addslashes($_POST['Ref'][$i]);
                        $sql = "INSERT INTO tbl_wo_references SET WoId='$WoId',Ref='$Links'";
                        $conn->query($sql);

                     }
                }
             }

  echo "<script>alert('Work Order Created Successfully!');window.location.href='view-work-order.php';</script>";
}
else{
 
    $query2 = "UPDATE tbl_rooftop_work_order SET CompId='$CompId',CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='$Status',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate = '$InvoiceDate',QtnSubject='$QtnSubject',Details='$Details',TermsCondition='$TermsCondition',TermsCondition2='$TermsCondition2',TermsCondition3='$TermsCondition3',TermsCondition4='$TermsCondition4',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',RefEnqNo='$RefEnqNo',KindAttn='$KindAttn' WHERE id = '$id'";
  $conn->query($query2);

    $sql = "DELETE FROM tbl_wo_references WHERE WoId='$id'";
$conn->query($sql);
 $number = count($_POST['Ref']);
  if($number > 0)  
            {  
                for($i=0; $i<$number; $i++)  
                {  
                     if(trim($_POST["Ref"][$i] != ''))  
                     {
                        $Links = addslashes($_POST['Ref'][$i]);
                        $sql = "INSERT INTO tbl_wo_references SET WoId='$id',Ref='$Links'";
                        $conn->query($sql);

                     }
                }
             }

  echo "<script>alert('Work Order Updated Successfully!');window.location.href='view-work-order.php';</script>";

}
    //header('Location:courses.php'); 

  }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Work Order</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                     <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Company<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CompId" id="CompId" required>
<option selected="" value="">Select Company</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=10";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CompId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

                                    <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Customer</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=5";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<!-- <div class="form-group col-md-2" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" onclick="addVendor()">+</button>
</div> -->

<div class="form-group col-md-12">
                                            <label class="form-label">Contact No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["CellNo"]; ?>"
                                                autocomplete="off" oninput="getUserDetails()">
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-12">
   <label class="form-label">Customer Name </label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row7["CustName"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-12">
   <label class="form-label">Address</label>
     <textarea name="Address" id="Address" class="form-control"  
                                                ><?php echo $row7['Address']; ?></textarea>
    <div class="clearfix"></div>
 </div>   


<!-- <div class="form-group col-lg-4">
<label class="form-label">Ref NO <span class="text-danger">*</span></label>
<input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $Invoice_No; ?>" >
<div class="clearfix"></div>
</div> -->

<div class="form-group col-lg-6">
<label class="form-label">Kind Attn <span class="text-danger">*</span></label>
<input type="text" name="KindAttn" class="form-control" id="KindAttn" placeholder="" value="<?php echo $row7['KindAttn']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
    <label class="form-label">QTN Date </label>
    <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control"
                                                placeholder="" value="<?php echo $InvoiceDate; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
</div> 

<div class="form-group col-lg-3">
<label class="form-label">Ref Enquiry No <span class="text-danger">*</span></label>
<input type="text" name="RefEnqNo" class="form-control" id="RefEnqNo" placeholder="" value="<?php echo $row7['RefEnqNo']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
   <label class="form-label">Subject</label>
     <textarea  type="text" name="QtnSubject" id="QtnSubject" class="form-control"><?php echo $QtnSubject; ?></textarea>
    <div class="clearfix"></div>
 </div>   
 </div>  

<?php 
$sql_1 = "SELECT * FROM tbl_wo_references WHERE WoId='$id'";
$row_1 = getList($sql_1);
foreach($row_1 as $result){
 ?>
<div class="form-row">
  
<div class="form-group col-md-12">
<label class="form-label">Ref <span class="text-danger">*</span></label>
<div class="input-group">
     <label class="custom-file">
<input type="text" class="form-control" placeholder="" value="<?php echo $result["Ref"]; ?>" autocomplete="off" name="Ref[]">
</label>
<div class="clearfix"></div>
<span class="input-group-append">
 <a onClick="return confirm('Are you sure you want delete this Record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $result['id']; ?>&action=deletelink&pid=<?php echo $_GET['id']; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
  </span>
</div>


</div>
</div>

<?php } ?>

<div id="dynamic_field2">
  <div class="form-row">

<div class="form-group col-md-12">
<label class="form-label">Ref <span class="text-danger">*</span></label>
<div class="input-group">
    <label class="custom-file">
<input type="text" name="Ref[]" class="form-control" placeholder="" value="" autocomplete="off" >
</label>
<div class="clearfix"></div>
<span class="input-group-append">
    <button class="btn btn-secondary" type="button" id="add_more2"><i class="fa fa-plus"></i></button>
  </span>
</div>
</div>
</div>
</div> 

<div class="form-row">
<div class="form-group col-md-12">
   <label class="form-label">Details</label>
     <textarea  type="text" name="Details" id="editor1" class="form-control"><?php echo $Details; ?></textarea>
    <div class="clearfix"></div>
 </div> 
 
<div class="form-group col-md-12">
   <label class="form-label">Annexure “A”</label>
     <textarea  type="text" name="TermsCondition" id="editor2" class="form-control"><?php echo $TermsCondition; ?></textarea>
    <div class="clearfix"></div>
 </div> 


 <div class="form-group col-md-12">
   <label class="form-label">Annexure “B”</label>
     <textarea  type="text" name="TermsCondition2" id="editor3" class="form-control"><?php echo $TermsCondition2; ?></textarea>
    <div class="clearfix"></div>
 </div> 

  <div class="form-group col-md-12">
   <label class="form-label">Annexure “C”</label>
     <textarea  type="text" name="TermsCondition3" id="editor4" class="form-control"><?php echo $TermsCondition3; ?></textarea>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-12">
   <label class="form-label">Annexure Hindi</label>
     <textarea  type="text" name="TermsCondition4" id="editor5" class="form-control"><?php echo $TermsCondition4; ?></textarea>
    <div class="clearfix"></div>
 </div> 

</div>
<br>

                                   <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                
                                    </div>
                               </div>


 <div class="col-lg-5" id="emidetails" style="display:none;">
    

 </div>

  
                                

 </div>
 </form>





                            </div>
                        </div>



</div>


                   


                    <?php include_once 'footer.php'; ?>
                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>

 <script type="text/javascript">
 CKEDITOR.replace( 'editor1');
    CKEDITOR.replace( 'editor2');
    CKEDITOR.replace( 'editor3');
    CKEDITOR.replace( 'editor4');
    CKEDITOR.replace( 'editor5');
    function getUserDetails(){
        var CellNo = $('#CellNo').val();
        var action = "getUserDetails2";
            $.ajax({
                url: "ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    CellNo: CellNo
                },
                dataType:"json",  
                success: function(data) {
                    $('#Address').val(data.Address);
                    $('#CustName').val(data.Fname+" "+data.Lname);
                    $('#Gname').val(data.Gname);
                    $('#Gphone').val(data.Gphone);
                    $('#Gname2').val(data.Gname2);
                    $('#Gphone2').val(data.Gphone2);
                    $('#AgentName').val(data.AgentName);
                    
                }
            });

    }
     $(document).ready(function() {

var i2=1;  
          $('#add_more2').click(function(){  
           i2++;  
           var html = '';
           html+='<div class="form-row" id="row'+i2+'">'; 
          
 html+='<div class="form-group col-md-12">'; 
 html+='<label class="form-label">Ref <span class="text-danger">*</span></label>'; 
 html+='<div class="input-group">    <label class="custom-file">'; 
   html+='<input type="text" name="Ref[]" class="form-control" placeholder="" value="" autocomplete="off" ></label>'; 
 html+='<div class="clearfix"></div>'; 
 html+='<span class="input-group-append">'; 
     html+='<button class="btn btn-danger btn_remove" type="button" id="'+i2+'"><i class="fa fa-times"></i></button>'; 
   html+='</span>'; 
 html+='</div>'; 
 html+='</div>';
 html+='</div>'; 
           $('#dynamic_field2').append(html);
        });  

      $(document).on('click', '.btn_remove2', function(){  
           var button_id2 = $(this).attr("id");  
           if(confirm("Are you sure you want to delete?"))  
           { 
           $('#row2'+button_id2+'').remove();  
           }
      }); 

     $(document).on("change", "#CustId", function(event) {
            var val = this.value;
            var action = "getUserDetails";
            $.ajax({
                url: "ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType:"json",  
                success: function(data) {
                    
                    $('#Address').val(data.Taluka+", "+data.Village+", "+data.District);
                    $('#CustName').val(data.Fname);
                    $('#CellNo').val(data.Phone);
                     $('#Gname').val(data.Gname);
                    $('#Gphone').val(data.Gphone);
                    $('#Gname2').val(data.Gname2);
                    $('#Gphone2').val(data.Gphone2);
                    $('#AgentName').val(data.AgentName);
                }
            });

        });


    });

     
 </script>
</body>

</html>