<?php

require_once 'vendor/autoload.php';

use App\Http\Controllers\JadwalScheduleController;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing JadwalScheduleController...\n";

try {
    $controller = new JadwalScheduleController();
    echo "✓ Controller created successfully\n";
    
    // Test getSchedule method
    $request = new Request();
    $response = $controller->getSchedule($request);
    echo "✓ getSchedule method executed successfully\n";
    echo "Response status: " . $response->getStatusCode() . "\n";
    
    // Test getConflicts method
    $response = $controller->getConflicts($request);
    echo "✓ getConflicts method executed successfully\n";
    echo "Response status: " . $response->getStatusCode() . "\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
