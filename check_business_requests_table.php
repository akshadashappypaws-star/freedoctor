<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\BusinessOrganizationRequest;
use Illuminate\Support\Facades\DB;

echo "=== Checking Business Organization Requests Table ===\n\n";

// Check existing records to see what camp_request_type values are used
$existingRequests = BusinessOrganizationRequest::select('camp_request_type')->distinct()->get();

echo "Existing camp_request_type values:\n";
foreach ($existingRequests as $request) {
    echo "- '{$request->camp_request_type}' (length: " . strlen($request->camp_request_type) . ")\n";
}

// Check table structure
echo "\n=== Database Schema Info ===\n";
try {
    $pdo = DB::connection()->getPdo();
    $stmt = $pdo->prepare("DESCRIBE business_organization_requests");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'camp_request_type') {
            echo "camp_request_type column: " . json_encode($column) . "\n";
        }
    }
} catch (\Exception $e) {
    echo "Error getting schema: " . $e->getMessage() . "\n";
}
?>
