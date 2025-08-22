<?php
// Simple test without external dependencies
echo "Testing template structure analysis...\n";

// This is the template from your campaign
$template = [
    "id" => 1,
    "whatsapp_id" => "946021564345776",
    "name" => "doctor_flow_lead",
    "status" => "APPROVED",
    "category" => "MARKETING",
    "components" => [
        [
            "type" => "BODY",
            "text" => "*👋 Welcome Doctor!*\nNow, conducting *Medical Camps in Pune* has become *completely hassle-free* 🚀\n\nFor the *first time in India, you can simply* *post your camp on our platform*, and patients will start discovering and registering for it automatically.\n\n✨ *What's in it for you?*\n\n• ✅ Hassle-free *camp setup & patient registration*\n• ✅ *Promote your expertise* & build trust in the community\n• ✅ Get *new patient leads* for your practice or future healthcare activities\n• ✅ Easy dashboard to manage your camps & categories\n\n🪺 Whether it's *General Health, Eye, Dental, Diabetes, Child Care, Women's Health*, or any other category – just select your camp type, and we'll ensure patients are notified instantly.\n\n👉 To get started, please select your interested camp\n\n📩 Once you select, our team will reach out to you with *next steps* and help you go live with your *first medical camp in Pune* within m"
        ],
        [
            "type" => "BUTTONS",
            "buttons" => [
                [
                    "type" => "FLOW",
                    "text" => "Select Interested Camp",
                    "flow_id" => 748582754439475,
                    "flow_action" => "NAVIGATE",
                    "navigate_screen" => "CAMP_SELECTION"
                ],
                [
                    "type" => "URL",
                    "text" => "Visit website",
                    "url" => "https://freedoctor.world/our_business_proposal"
                ],
                [
                    "type" => "PHONE_NUMBER",
                    "text" => "Contact Us",
                    "phone_number" => "+917741044366"
                ]
            ]
        ]
    ]
];

echo "Template Analysis:\n";
echo "Template Name: " . $template['name'] . "\n";
echo "Status: " . $template['status'] . "\n";
echo "Category: " . $template['category'] . "\n";
echo "Components: " . count($template['components']) . "\n\n";

// Check for potential issues
$issues = [];

foreach ($template['components'] as $component) {
    if ($component['type'] === 'BODY') {
        $text = $component['text'];
        if (strlen($text) > 1024) {
            $issues[] = "Body text too long: " . strlen($text) . " characters (max 1024)";
        }
        if (substr($text, -1) === 'm') {
            $issues[] = "Body text appears to be cut off (ends with 'm')";
        }
    }
    
    if ($component['type'] === 'BUTTONS') {
        foreach ($component['buttons'] as $button) {
            if ($button['type'] === 'FLOW') {
                $issues[] = "Uses FLOW button which requires WhatsApp Business API v2.0+";
            }
        }
    }
}

if (empty($issues)) {
    echo "✅ No obvious template issues found\n";
} else {
    echo "❌ Potential issues found:\n";
    foreach ($issues as $issue) {
        echo "  - $issue\n";
    }
}

echo "\nPossible solutions:\n";
echo "1. Check if Flow buttons are supported in your WhatsApp Business account\n";
echo "2. Verify the Flow ID (748582754439475) exists and is published\n";
echo "3. Test with a simpler template first (like hello_world)\n";
echo "4. Check if the template needs re-approval after API version change\n";

echo "\nRecommendation: Test with hello_world template first to isolate the issue.\n";
?>
