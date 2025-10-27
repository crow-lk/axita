<?php

/**
 * Test Payzy payment flow
 * This simulates what happens during a payment
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Payzy Payment Flow ===\n\n";

// Test configuration
$shopId = core()->getConfigData('sales.payment_methods.payzy.shop_id');
$secretKey = core()->getConfigData('sales.payment_methods.payzy.secret_key');
$sandbox = core()->getConfigData('sales.payment_methods.payzy.sandbox');
$active = core()->getConfigData('sales.payment_methods.payzy.active');

echo "Configuration:\n";
echo "Shop ID: " . ($shopId ?: 'NOT SET') . "\n";
echo "Secret Key: " . ($secretKey ? str_repeat('*', strlen($secretKey) - 4) . substr($secretKey, -4) : 'NOT SET') . "\n";
echo "Sandbox: " . ($sandbox ? 'Yes' : 'No') . "\n";
echo "Active: " . ($active ? 'Yes' : 'No') . "\n\n";

// Test signature generation with sample data
$testData = [
    'x_test_mode'           => 'on',
    'x_shopid'              => '2',
    'x_amount'              => '100.00',
    'x_order_id'            => 'TEST-' . time(),
    'x_response_url'        => url('/payzy/success'),
    'x_first_name'          => 'Test',
    'x_last_name'           => 'User',
    'x_company'             => '',
    'x_address'             => 'Test Address',
    'x_country'             => 'LK',
    'x_state'               => 'Western',
    'x_city'                => 'Colombo',
    'x_zip'                 => '10100',
    'x_phone'               => '0771234567',
    'x_email'               => 'test@example.com',
    'x_ship_to_first_name'  => 'Test',
    'x_ship_to_last_name'   => 'User',
    'x_ship_to_company'     => '',
    'x_ship_to_address'     => 'Test Address',
    'x_ship_to_country'     => 'LK',
    'x_ship_to_state'       => 'Western',
    'x_ship_to_city'        => 'Colombo',
    'x_ship_to_zip'         => '10100',
    'x_freight'             => 'x_freight',
    'x_platform'            => 'custom',
    'x_version'             => '1.0',
    'signed_field_names'    => 'x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names',
];

// Generate signature
$signedFields = explode(',', $testData['signed_field_names']);
$dataString = '';

foreach ($signedFields as $field) {
    $dataString .= $field . '=' . ($testData[$field] ?? '') . ',';
}

$dataString = rtrim($dataString, ',');

$hash = hash_hmac('sha256', $dataString, $secretKey, true);
$signature = base64_encode($hash);

$testData['signature'] = $signature;

echo "Test Payment Data:\n";
echo "Order ID: " . $testData['x_order_id'] . "\n";
echo "Amount: " . $testData['x_amount'] . "\n";
echo "Signature: " . $signature . "\n\n";

echo "Making API request to Payzy...\n";

try {
    $response = \Illuminate\Support\Facades\Http::post('https://api.payzypay.xyz/checkout/custom-checkout', $testData);
    
    echo "Response Status: " . $response->status() . "\n";
    echo "Response Body: " . $response->body() . "\n\n";
    
    if ($response->successful()) {
        $data = $response->json();
        echo "Success! Payment URL: " . ($data['url'] ?? 'N/A') . "\n";
    } else {
        echo "Error: " . $response->status() . "\n";
        echo "Body: " . $response->body() . "\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";
