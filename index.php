<?php

// The URL to forward to
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : null;

// Fetch the raw payload data directly from the request body
$payload = file_get_contents('php://input');

if (!$endpoint || !$payload) {
    header("HTTP/1.0 400 Bad Request");
    echo "Endpoint or Payload missing!";
    exit;
}

$response = forwardPayloadToEndpoint($endpoint, $payload);

echo $response;

function forwardPayloadToEndpoint($endpoint, $payload) {
    // Initialize cURL session
    $ch = curl_init($endpoint);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload)
    ]);

    // Execute cURL session and get the response
    $response = curl_exec($ch);

    // If cURL error occurs
    if (curl_errno($ch)) {
        return 'Curl error: ' . curl_error($ch);
    }

    // Close cURL session
    curl_close($ch);

    return $response;
}

?>
