<?php
// Check template languages

$apiKey = "EAAWWJbQjORMBPOjIZBiH7svICJNRikZCwMLYvrOpZCork2Hm70OGY2fO18pMQ3xH3ZCwiYuugcHT9gOTF52fzIoNFRVd7o9DYB9P3oWiUfbEdE1eZBtDXcZB915Ru9jdprXYiis3Mep6xcRA9xDAQymp1v4DoIweDnRPTfHYtIaKUPahk2pH38nkgdCA4kdtvv";
$businessAccountId = "1588252012149592";
$baseUrl = "https://graph.facebook.com/v18.0";

echo "=== Checking Template Languages ===\n\n";

// Fetch templates with detailed information
$response = file_get_contents("{$baseUrl}/{$businessAccountId}/message_templates?limit=10", false, stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer {$apiKey}\r\n"
    ]
]));

$data = json_decode($response, true);

if (isset($data['data'])) {
    foreach ($data['data'] as $template) {
        echo "Template: {$template['name']}\n";
        echo "Status: {$template['status']}\n";
        echo "Category: {$template['category']}\n";
        echo "Language: {$template['language']}\n";
        
        if (isset($template['components'])) {
            echo "Components:\n";
            foreach ($template['components'] as $i => $component) {
                echo "  [{$i}] Type: {$component['type']}\n";
                if (isset($component['text'])) {
                    echo "      Text: " . substr($component['text'], 0, 100) . "...\n";
                }
                if (isset($component['buttons'])) {
                    echo "      Buttons: " . count($component['buttons']) . " buttons\n";
                    foreach ($component['buttons'] as $btn) {
                        echo "        - {$btn['type']}: {$btn['text']}\n";
                    }
                }
            }
        }
        echo "\n" . str_repeat("-", 50) . "\n\n";
    }
} else {
    echo "Failed to fetch templates:\n";
    echo $response . "\n";
}
?>
