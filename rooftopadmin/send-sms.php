<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once dirname(__DIR__) . '/sms-function.php';

$MainPage = 'Whatsapp-SMS';
$Page = 'Send-SMS';
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - Send WhatsApp SMS</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once 'header_script.php'; ?>
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/select2/select2.css">
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'header.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container flex-grow-1 container-p-y">

<h5 class="font-weight-bold py-3 mb-0">Send Whatsapp SMS</h5>

<?php
if (isset($_POST['submit'])) {

    $template     = $_POST['Template'] ?? '';
    $languageCode = $_POST['Language'] ?? 'en';
    $sendType     = $_POST['SendType'] ?? 'single';

    if ($template == '') {
        echo "<script>alert('Template is required');</script>";
    } else {

        if ($sendType === 'single') {

            $CustPhone = $_POST['CellNo'] ?? '';

            if ($CustPhone == '') {
                echo "<script>alert('Customer mobile required');</script>";
            } else {

                $CustPhone = preg_replace('/[^0-9]/', '', $CustPhone);
                $phone = '91' . $CustPhone;

                $response = sendWhatsAppTemplate($phone, $template, [], $languageCode);

                if (is_string($response)) {
                    $response = json_decode($response, true);
                }

                if (isset($response['messages'][0]['id'])) {
                    echo "<script>alert('WhatsApp sent successfully');</script>";
                } else {
                    echo "<script>alert('WhatsApp sending failed');</script>";
                }
            }
        }

        if ($sendType === 'all') {

            $sql = "SELECT CellNo FROM tbl_leads WHERE CellNo!=''";
            $customers = getList($sql);

            $success = 0;
            $failed  = 0;

            foreach ($customers as $c) {

                $CustPhone = preg_replace('/[^0-9]/', '', $c['CellNo']);
                if (strlen($CustPhone) < 10) {
                    continue;
                }

                $phone = '91' . $CustPhone;

                $response = sendWhatsAppTemplate($phone, $template, [], $languageCode);

                if (is_string($response)) {
                    $response = json_decode($response, true);
                }

                if (isset($response['messages'][0]['id'])) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            echo "<script>alert('WhatsApp Sent: $success | Failed: $failed');</script>";
        }
    }
}
?>

<div class="card">
<div class="card-body">

<form method="post">

<div class="form-group">
    <label class="form-label">Send To</label><br>
    <label class="custom-control custom-radio mr-3">
        <input type="radio" name="SendType" value="single" class="custom-control-input" checked>
        <span class="custom-control-label">Single Customer</span>
    </label>
    <label class="custom-control custom-radio">
        <input type="radio" name="SendType" value="all" class="custom-control-input">
        <span class="custom-control-label">All Customers</span>
    </label>
</div>

<div class="form-row">
<div class="form-group col-md-8">
    <label class="form-label">Template *</label>
    <select class="select2-demo form-control" name="Template" required onchange="setLanguage(this)">
        <option value="">Select Template</option>
        <?php
        $tpl = getList("SELECT name, language FROM tbl_templates_name");
        foreach ($tpl as $t) {
            echo "<option value='" . htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8') . "' data-lang='" . htmlspecialchars($t['language'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8') . "</option>";
        }
        ?>
    </select>
</div>

<div class="form-group col-md-4">
    <label class="form-label">Language</label>
    <input type="text" name="Language" id="Language" class="form-control" readonly>
</div>
</div>

<div class="form-row customer-block">
<div class="form-group col-md-8">
    <label class="form-label">Customer</label>
    <select class="select2-demo form-control" id="CustName" onchange="setCellNo(this)">
        <option value="">Select Customer</option>
        <?php
        $cust = getList("SELECT CustName, CellNo FROM tbl_leads");
        foreach ($cust as $c) {
            $cell = htmlspecialchars($c['CellNo'], ENT_QUOTES, 'UTF-8');
            $name = htmlspecialchars($c['CustName'], ENT_QUOTES, 'UTF-8');
            echo "<option data-cell='$cell'>$name</option>";
        }
        ?>
    </select>
</div>

<div class="form-group col-md-4">
    <label class="form-label">Cell No</label>
    <input type="text" name="CellNo" id="CellNo" class="form-control">
</div>
</div>

<button type="submit" name="submit" class="btn btn-primary">SEND WHATSAPP</button>

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
<script>
$('.select2-demo').select2();

function setCellNo(el){
    $('#CellNo').val($(el).find(':selected').data('cell') || '');
}

function setLanguage(el){
    $('#Language').val($(el).find(':selected').data('lang') || '');
}

$('input[name="SendType"]').change(function(){
    if($(this).val()==='all'){
        $('.customer-block').hide();
    } else {
        $('.customer-block').show();
    }
});
</script>
</body>
</html>
