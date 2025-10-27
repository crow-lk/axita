<?php
/**
 * Test Payzy payment exactly like the sample code
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

echo "\n=== Testing Payzy Payment (Exact Sample Match) ===\n\n";

$secretKey = '$2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy';
$shopId = '2';

// Create test data matching sample EXACTLY
$orderId = 'TEST-' . time();

$testData = [
    'x_test_mode' => 'on',
    'x_shopid' => $shopId,
    'x_amount' => '100.00',
    'x_order_id' => $orderId,
    'x_response_url' => 'http://axita.test/payzy/success',
    'x_first_name' => 'John',
    'x_last_name' => 'Doe',
    'x_company' => 'ABC Company',
    'x_address' => '123 Main St',
    'x_country' => 'Sri Lanka',
    'x_state' => 'Western',
    'x_city' => 'Colombo',
    'x_zip' => '12345',
    'x_phone' => '1234567890',
    'x_email' => 'exampl@email.com',
    'x_ship_to_first_name' => 'John',
    'x_ship_to_last_name' => 'Doe',
    'x_ship_to_company' => 'ABC Company',
    'x_ship_to_address' => '123 Main St',
    'x_ship_to_country' => 'Sri Lanka',
    'x_ship_to_state' => 'Western',
    'x_ship_to_city' => 'Colombo',
    'x_ship_to_zip' => '12345',
    'x_freight' => 'x_freight',
    'x_platform' => 'custom',
    'x_version' => '1.0',
    'signed_field_names' => 'x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names',
];

// Build signature string
$signedFields = explode(',', $testData['signed_field_names']);
$dataString = '';

foreach ($signedFields as $field) {
    $dataString .= $field . '=' . ($testData[$field] ?? '') . ',';
}

$dataString = rtrim($dataString, ',');

echo "Order ID: $orderId\n";
echo "Amount: {$testData['x_amount']}\n\n";

echo "Data String:\n";
echo substr($dataString, 0, 150) . "...\n\n";

// Generate signature
$hash = hash_hmac('sha256', $dataString, $secretKey, true);
$signature = base64_encode($hash);

$testData['signature'] = $signature;

echo "Signature: $signature\n\n";

// Make API call
echo "Making API request to Payzy...\n";

try {
    $response = Http::timeout(10)
        ->asJson()
        ->post('https://api.payzypay.xyz/checkout/custom-checkout', $testData);
    
    $statusCode = $response->status();
    $responseData = $response->json();
    
    echo "Status: $statusCode\n";
    echo "Response:\n";
    print_r($responseData);
    echo "\n";
    
    if ($response->successful() && isset($responseData['url'])) {
        echo "✅ Payment URL received: {$responseData['url']}\n\n";
        
        // Parse the URL
        $urlParts = parse_url($responseData['url']);
        echo "URL Parts:\n";
        print_r($urlParts);
        echo "\n";
        
        // Check if URL contains any query parameters or identifiers
        if (isset($urlParts['path'])) {
            echo "Path: {$urlParts['path']}\n";
            $pathParts = explode('/', trim($urlParts['path'], '/'));
            echo "Path segments:\n";
            print_r($pathParts);
        }
    } else {
        echo "❌ Unexpected response structure\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Comparing with Previous Successful Request ===\n";
echo "From logs, successful request had:\n";
echo "- Order ID: ORD-121-1761407703\n";
echo "- Response: https://app.payzypay.xyz/fromwordpress/e1\n";
echo "- Same response URL for ALL requests\n\n";

echo "💡 Analysis:\n";
echo "The API is returning the SAME URL (e1) for all requests.\n";
echo "This suggests either:\n";
echo "1. This is an error page URL\n";
echo "2. Payzy needs additional setup/configuration\n";
echo "3. The Shop ID needs to be properly registered with Payzy\n";
echo "4. There's a problem with how the API is being called\n\n";

echo "🔍 Recommendation:\n";
echo "Contact Payzy support to:\n";
echo "1. Verify Shop ID '2' is properly configured\n";
echo "2. Check if there are additional setup steps needed\n";
echo "3. Ask about the 'invalid temp order id' error\n";
echo "4. Confirm the API endpoint and request format\n\n";
