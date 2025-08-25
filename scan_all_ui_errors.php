<?php
/**
 * Complete UI Error Scanner
 * Scans all Blade template files for JavaScript errors and UI issues
 */

echo "=== FreeDoctor Web UI Scanner ===\n\n";

// Directories to scan
$directories = [
    'resources/views',
    'public/js',
    'public/css'
];

$errors = [];
$warnings = [];
$suggestions = [];

function scanBladeFiles($directory) {
    global $errors, $warnings, $suggestions;
    
    $files = glob($directory . '/*.blade.php');
    foreach (glob($directory . '/*', GLOB_ONLYDIR) as $dir) {
        $files = array_merge($files, scanBladeFiles($dir));
    }
    
    foreach ($files as $file) {
        echo "Scanning: " . basename($file) . "\n";
        $content = file_get_contents($file);
        
        // Check for common JavaScript errors
        if (preg_match('/onclick\s*=\s*["\'][^"\']*["\']/', $content, $matches)) {
            $functions = [];
            preg_match_all('/onclick\s*=\s*["\']([^"\']*)\([^"\']*["\']/', $content, $functionMatches);
            
            foreach ($functionMatches[1] as $func) {
                if (!preg_match('/function\s+' . preg_quote($func) . '\s*\(/', $content) && 
                    !preg_match('/window\.' . preg_quote($func) . '\s*=/', $content) &&
                    !preg_match('/\$\(document\)\.ready.*' . preg_quote($func) . '/', $content)) {
                    $errors[] = "Missing function definition: $func in " . basename($file);
                }
            }
        }
        
        // Check for Bootstrap modal issues
        if (strpos($content, 'data-bs-toggle="modal"') !== false) {
            if (strpos($content, 'bootstrap.bundle.min.js') === false && 
                strpos($content, 'bootstrap.min.js') === false) {
                $warnings[] = "Bootstrap modal used without Bootstrap JS in " . basename($file);
            }
        }
        
        // Check for jQuery usage without inclusion
        if (preg_match('/\$\(/', $content) && strpos($content, 'jquery') === false) {
            $warnings[] = "jQuery used without inclusion in " . basename($file);
        }
        
        // Check for unclosed script tags
        $scriptOpen = substr_count($content, '<script');
        $scriptClose = substr_count($content, '</script>');
        if ($scriptOpen !== $scriptClose) {
            $errors[] = "Unclosed script tags in " . basename($file) . " (Open: $scriptOpen, Close: $scriptClose)";
        }
        
        // Check for SweetAlert usage
        if (strpos($content, 'Swal.fire') !== false && 
            strpos($content, 'sweetalert2') === false) {
            $warnings[] = "SweetAlert2 used without inclusion in " . basename($file);
        }
        
        // Check for common CSS issues
        if (preg_match('/style\s*=\s*["\'][^"\']*position\s*:\s*fixed[^"\']*["\']/', $content)) {
            $suggestions[] = "Fixed positioning found in " . basename($file) . " - consider responsive design";
        }
    }
    
    return $files;
}

function scanJSFiles($directory) {
    global $errors, $warnings;
    
    if (!is_dir($directory)) return [];
    
    $files = glob($directory . '/*.js');
    foreach ($files as $file) {
        echo "Scanning JS: " . basename($file) . "\n";
        $content = file_get_contents($file);
        
        // Check for syntax errors (basic)
        if (substr_count($content, '{') !== substr_count($content, '}')) {
            $errors[] = "Unmatched braces in " . basename($file);
        }
        
        if (substr_count($content, '(') !== substr_count($content, ')')) {
            $errors[] = "Unmatched parentheses in " . basename($file);
        }
        
        // Check for undefined variables
        if (preg_match('/console\.log\(([^)]+)\)/', $content, $matches)) {
            $suggestions[] = "Console.log found in " . basename($file) . " - remove for production";
        }
    }
    
    return $files;
}

function scanCSSFiles($directory) {
    global $warnings, $suggestions;
    
    if (!is_dir($directory)) return [];
    
    $files = glob($directory . '/*.css');
    foreach ($files as $file) {
        echo "Scanning CSS: " . basename($file) . "\n";
        $content = file_get_contents($file);
        
        // Check for CSS issues
        if (substr_count($content, '{') !== substr_count($content, '}')) {
            $warnings[] = "Unmatched CSS braces in " . basename($file);
        }
        
        // Check for vendor prefixes
        if (strpos($content, '-webkit-') !== false || strpos($content, '-moz-') !== false) {
            $suggestions[] = "Vendor prefixes found in " . basename($file) . " - consider autoprefixer";
        }
    }
    
    return $files;
}

echo "Starting comprehensive scan...\n\n";

// Scan all directories
$allFiles = [];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (strpos($dir, 'views') !== false) {
            $allFiles = array_merge($allFiles, scanBladeFiles($dir));
        } elseif (strpos($dir, 'js') !== false) {
            $allFiles = array_merge($allFiles, scanJSFiles($dir));
        } elseif (strpos($dir, 'css') !== false) {
            $allFiles = array_merge($allFiles, scanCSSFiles($dir));
        }
    }
}

echo "\n=== SCAN RESULTS ===\n\n";

if (!empty($errors)) {
    echo "🔴 ERRORS (" . count($errors) . "):\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "🟡 WARNINGS (" . count($warnings) . "):\n";
    foreach ($warnings as $warning) {
        echo "  - $warning\n";
    }
    echo "\n";
}

if (!empty($suggestions)) {
    echo "💡 SUGGESTIONS (" . count($suggestions) . "):\n";
    foreach ($suggestions as $suggestion) {
        echo "  - $suggestion\n";
    }
    echo "\n";
}

if (empty($errors) && empty($warnings)) {
    echo "✅ No critical issues found!\n";
} else {
    echo "📊 SUMMARY:\n";
    echo "  Total files scanned: " . count($allFiles) . "\n";
    echo "  Errors: " . count($errors) . "\n";
    echo "  Warnings: " . count($warnings) . "\n";
    echo "  Suggestions: " . count($suggestions) . "\n";
}

echo "\n=== SCAN COMPLETE ===\n";
?>
