<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Campaign;

$campaigns = Campaign::whereNotNull('thumbnail')->get();

echo "Campaigns with thumbnails:\n";
foreach ($campaigns as $campaign) {
    echo "ID: {$campaign->id}, Title: {$campaign->title}, Thumbnail: {$campaign->thumbnail}\n";
}

if ($campaigns->isEmpty()) {
    echo "No campaigns with thumbnails found.\n";
}
