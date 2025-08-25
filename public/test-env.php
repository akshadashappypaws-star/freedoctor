<?php

// Load Laravel configuration
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->bootstrap();

echo "<h1>Environment Configuration Test</h1>";
echo "<h2>Google Maps API Key Test</h2>";

echo "<p><strong>Direct env() call:</strong> " . (env('GOOGLE_MAPS_API_KEY') ?: 'NOT FOUND') . "</p>";
echo "<p><strong>Config call:</strong> " . (config('app.google_maps_api_key') ?: 'NOT FOUND') . "</p>";

// Show all env variables that contain 'GOOGLE'
echo "<h3>All Google-related environment variables:</h3>";
echo "<pre>";
foreach ($_ENV as $key => $value) {
    if (stripos($key, 'GOOGLE') !== false) {
        echo "$key = $value\n";
    }
}
echo "</pre>";

// Test the complete Google Maps URL
$apiKey = env('GOOGLE_MAPS_API_KEY');
$mapsUrl = "https://maps.googleapis.com/maps/api/js?key={$apiKey}&libraries=places&callback=initMap";

echo "<h3>Generated Google Maps URL:</h3>";
echo "<p><code>$mapsUrl</code></p>";

// Test if we can make a simple request to Google Maps API
echo "<h3>API Connectivity Test:</h3>";
$testUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=Pune&key={$apiKey}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<p><strong>HTTP Status:</strong> $httpCode</p>";

if ($response) {
    $data = json_decode($response, true);
    echo "<p><strong>API Response Status:</strong> " . ($data['status'] ?? 'UNKNOWN') . "</p>";
    
    if (isset($data['error_message'])) {
        echo "<p><strong>Error Message:</strong> " . $data['error_message'] . "</p>";
    }
    
    if ($data['status'] === 'OK') {
        echo "<p style='color: green;'>✅ API Key is working correctly!</p>";
    } else {
        echo "<p style='color: red;'>❌ API Key has issues. Status: " . $data['status'] . "</p>";
    }
    
    echo "<details><summary>Full API Response</summary><pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre></details>";
} else {
    echo "<p style='color: red;'>❌ Could not connect to Google Maps API</p>";
}

?>
