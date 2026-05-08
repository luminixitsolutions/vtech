<?php 
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
?>