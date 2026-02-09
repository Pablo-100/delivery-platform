<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST SMS ===\n\n";

// Check config
$provider = config('services.sms.provider');
$apiKey = config('services.textflow.api_key');

echo "Provider: {$provider}\n";
echo "API Key: " . substr($apiKey, 0, 15) . "...\n\n";

// Test direct HTTP call to TextFlow
echo "--- Test direct TextFlow API ---\n";

$response = \Illuminate\Support\Facades\Http::withHeaders([
    'Authorization' => 'Bearer ' . $apiKey,
    'Content-Type' => 'application/json',
])->post('https://textflow.me/api/send-sms', [
    'phone_number' => '+21620123456',
    'text' => 'Test SMS - Delivery Platform',
]);

echo "HTTP Status: " . $response->status() . "\n";
echo "Response: " . $response->body() . "\n\n";

// Test via SmsService
echo "--- Test via SmsService ---\n";
$sms = new \App\Services\SmsService();
$result = $sms->send('+21620123456', 'Test SMS from Delivery Platform');
echo "Result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";

// Check logs
echo "\n--- Derniers logs ---\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -15);
    foreach ($lastLines as $line) {
        if (str_contains($line, 'SMS') || str_contains($line, 'TextFlow') || str_contains($line, 'textflow')) {
            echo $line;
        }
    }
}
