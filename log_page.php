<?php
$logFile = 'visitor_log.txt';



// Get IP address

$ipAddress = $_SERVER['REMOTE_ADDR'];

//echo "IPADDRESS".$ipAddress."\n";


function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // X-Forwarded-For can contain multiple IPs, take the first one
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ipList[0]);
    } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        return $_SERVER['HTTP_X_REAL_IP'];
    }
    return $_SERVER['REMOTE_ADDR']; // Fallback to REMOTE_ADDR
}

$realipAddress = getRealIpAddr();
//echo "realipAddress".$realipAddress."\n";


// Get User-Agent string

$userAgent = $_SERVER['HTTP_USER_AGENT'];
//echo "userAgent".$userAgent."\n";

// Determine device type (mobile or laptop)

function getDeviceType($userAgent) {

    $mobileAgents = ['Android', 'webOS', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone'];

    foreach ($mobileAgents as $device) {

        if (stripos($userAgent, $device) !== false) {

            return 'Mobile';

        }

    }

    return 'Laptop/Desktop';

}



// Get geolocation data from ipinfo.io API

function getGeolocation($ip) {

    $apiKey = '95bc179ec48d6e'; // Replace with your ipinfo.io API key

    $apiUrl = "https://ipinfo.io/{$ip}?token={$apiKey}";



    // Fetch data using file_get_contents

    $response = file_get_contents($apiUrl);

    //echo "response".$response."\n";


    if ($response === FALSE) {

        return null;

    }


    // Decode JSON response

    $data = json_decode($response, true);

    return $data;

}



$deviceType = getDeviceType($userAgent);

$geoData = getGeolocation($ipAddress);




$location = $geoData ? "{$geoData['city']}, {$geoData['region']}, {$geoData['country']}" : 'Unknown Location';

//echo "$location"."\n";



// Prepare the log entry

$logEntry = date('Y-m-d H:i:s') . " | IP: $ipAddress | Device: $deviceType | Location: $location | User-Agent: $userAgent" . PHP_EOL;



// Append the log entry to the file

file_put_contents($logFile, $logEntry, FILE_APPEND);



// Display a confirmation message to the user

echo "Your visit has been logged with location details. Thank you!";

?>


