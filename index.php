// This script is part of the WHMCS Azuracast module - https://marketplace.whmcs.com/product/7150-azuracast-provisioning-module

<?php
error_reporting(0);

$baseUrl = "https://yourazuracastdomain.com/api"; // Base API URL Azuracast
$apiKey = "your_azuracast_api_key"; // API Key Azuracast

function getApiData($endpoint, $apiKey) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Authorization: Bearer $apiKey"
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        return null;
    }

    curl_close($ch);
    return json_decode($response, true);
}

$cpuData = getApiData("$baseUrl/admin/server/stats", $apiKey);
$cpuLoadAvg = isset($cpuData['cpu']['load'][0]) ? number_format($cpuData['cpu']['load'][0], 2) : "N/A"; // Load médio de 1 min arredondado
$uptime = "N/A";

header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<server>\n";
echo "    <load>$cpuLoadAvg</load>\n";
echo "    <uptime>$uptime</uptime>\n";
echo "</server>\n";
?>
