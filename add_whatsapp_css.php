<?php
/*
 * Script to add WhatsApp CSS to all remaining WhatsApp Blade files
 * This adds the CSS link to all files that extend 'admin.master' but don't have it yet
 */

$whatsappFiles = [
    'c:\xampp\htdocs\freedoctor-web\resources\views\admin\whatsapp\workflows.blade.php',
    'c:\xampp\htdocs\freedoctor-web\resources\views\admin\whatsapp\workflows-create.blade.php',
    'c:\xampp\htdocs\freedoctor-web\resources\views\admin\whatsapp\workflows-show.blade.php',
    'c:\xampp\htdocs\freedoctor-web\resources\views\admin\whatsapp\machines.blade.php',
    'c:\xampp\htdocs\freedoctor-web\resources\views\admin\whatsapp\settings.blade.php',
    'c:\xampp\htdocs\freedoctor-web\resources\views\admin\whatsapp\automation\workflow.blade.php',
    'c:\xampp\htdocs\freedoctor-web\resources\views\admin\whatsapp\automation\rules.blade.php',
    'c:\xampp\htdocs\freedoctor-web\resources\views\admin\whatsapp\automation\analytics.blade.php',
    'c:\xampp\htdocs\freedoctor-web\resources\views\admin\whatsapp\automation\machines.blade.php',
    'c:\xampp\htdocs\freedoctor-web\resources\views\admin\whatsapp\automation\workflow_new.blade.php',
];

$cssToAdd = "\n@push('styles')\n    <link rel=\"stylesheet\" href=\"{{ asset('css/whatsapp-layout.css') }}\">\n@endpush\n";

foreach ($whatsappFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Check if it extends admin.master and doesn't already have the CSS
        if (strpos($content, "@extends('admin.master')") !== false && 
            strpos($content, 'whatsapp-layout.css') === false) {
            
            // Replace the first line after @extends('admin.master')
            $pattern = "/(@extends\('admin\.master'\))\n/";
            $replacement = "$1" . $cssToAdd . "\n";
            
            $newContent = preg_replace($pattern, $replacement, $content);
            
            if ($newContent !== $content) {
                file_put_contents($file, $newContent);
                echo "Updated: " . basename($file) . "\n";
            }
        }
    }
}

echo "Script completed!\n";
?>
