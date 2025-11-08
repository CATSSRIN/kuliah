<?php
// Fetch and display the Vindax API response for debugging
$url = "https://api.vindax.com/api/v1/ticker/24hr";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    die("cURL error: " . curl_error($curl));
}

curl_close($curl);

// Print the raw JSON response
header('Content-Type: application/json');
echo $response;

// Additionally, decode JSON and print the structure
$data = json_decode($response, true);
echo "\n\nDecoded Structure:\n";
print_r($data);