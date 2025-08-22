<?php
// Simple script to check for JavaScript syntax issues
$file = 'resources/views/user/pages/campaigns.blade.php';
$content = file_get_contents($file);
$lines = explode("\n", $content);

echo "Checking JavaScript syntax issues...\n\n";

// Check for return statements outside functions
$inFunction = false;
$braceCount = 0;
$inScript = false;

for ($i = 0; $i < count($lines); $i++) {
    $line = trim($lines[$i]);
    $lineNum = $i + 1;
    
    // Track script blocks
    if (strpos($line, '<script') !== false) {
        $inScript = true;
        continue;
    }
    if (strpos($line, '</script>') !== false) {
        $inScript = false;
        $inFunction = false;
        $braceCount = 0;
        continue;
    }
    
    if (!$inScript) continue;
    
    // Track function declarations
    if (preg_match('/function\s+\w+\s*\(/', $line) || preg_match('/\w+\s*=\s*function\s*\(/', $line)) {
        $inFunction = true;
    }
    
    // Track braces
    $braceCount += substr_count($line, '{') - substr_count($line, '}');
    
    if ($braceCount <= 0) {
        $inFunction = false;
    }
    
    // Check for return statements outside functions
    if (preg_match('/^\s*return\s+/', $line) && !$inFunction) {
        echo "POTENTIAL ISSUE: Return statement outside function at line $lineNum: $line\n";
    }
    
    // Check for specific problematic patterns
    if (strpos($line, '{{') !== false && strpos($line, '}}') !== false && $inScript) {
        echo "POTENTIAL ISSUE: Blade syntax in JavaScript at line $lineNum: $line\n";
    }
}

echo "\nDone checking.\n";
?>
