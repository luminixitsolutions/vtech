<?php
include_once 'config.php';
function sendWhatsAppTemplate($phoneNumber, $templateName, $params = [], $languageCode) {
    $apiKey = "db2dd8b1-589b-11f0-98fc-02c8a5e042bd";
    $url = "https://partnersv1.pinbot.ai/v3/372974299233641/messages";

    // Build template parameters
    $parameters = [];
    foreach ($params as $text) {
        $parameters[] = [
            "type" => "text",
            "text" => $text
        ];
    }

    $data = [
        "messaging_product" => "whatsapp",
        "recipient_type" => "individual",
        "to" => $phoneNumber,
        "type" => "template",
        "template" => [
            "name" => $templateName,
            "language" => [
                "code" => $languageCode
            ],
            "components" => [
                [
                    "type" => "body",
                    "parameters" => $parameters
                ]
            ]
        ]
    ];

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "apikey: $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute and return result
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return "Request Error: " . curl_error($ch);
    }
    curl_close($ch);
    return $response;
}

$InstallerId = 11;
$sql = "SELECT Fname,Phone FROM tbl_users WHERE id='$InstallerId'";
$row = getRecord($sql);
$InstallerPhone = $row['Phone'];
$InstallerName = $row['Fname'];

$sql2 = "SELECT tu.id,tu.Fname,tu.Phone AS CustPhone,tc.Name AS PumpCapacity,tu2.Fname AS DriverName,tu2.VehicalNo,tu.Phone AS DriverPhone FROM `tbl_users` tu INNER JOIN tbl_common_master tc ON tc.id=tu.PumpCapacity INNER JOIN tbl_sell ts ON ts.CustId=tu.id INNER JOIN tbl_users tu2 ON ts.DriverId=tu2.id WHERE tu.id=2961";
$row2 = getRecord($sql2);
$CustName = $row2['Fname'];
$CustPhone = $row2['CustPhone'];
$PumpCapacity = $row2['PumpCapacity'];
$DriverName = $row2['DriverName'];
$VehicalNo = $row2['VehicalNo'];
$DriverPhone = $row2['DriverPhone'];
$DispatchDate = date('d/m/Y');
// customer 
$phone = "919595454907";
$template = "dispatchedmaterial";
$params = [$CustName, $PumpCapacity, $DispatchDate, $DriverName, $DriverPhone, $InstallerName, $VehicalNo];  // Name, Date, Location

$languageCode = 'hi';
$response = sendWhatsAppTemplate($phone, $template, $params,$languageCode);

//uinstaller 
$phone = "919595454907";
$template = "installertemplate";
$params = [$InstallerName,$CustName, $PumpCapacity, $DispatchDate, $DriverName, $DriverPhone,$VehicalNo];  // Name, Date, Location

$languageCode = 'hi';
$response = sendWhatsAppTemplate($phone, $template, $params,$languageCode);
echo $response;