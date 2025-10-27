<?php
/**
 * Payzy Credential Validator
 * Test Payzy credentials with a simple API call
 * 
 * Usage:
 *   php validate_payzy.php
 *   php validate_payzy.php YOUR_SHOP_ID YOUR_SECRET_KEY
 */

// Get credentials from command line or use defaults
$shopId = $argv[1] ?? '2';
$secretKey = $argv[2] ?? '$2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy';

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║           PAYZY CREDENTIAL VALIDATOR                       ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "Testing Credentials:\n";
echo "  Shop ID: $shopId\n";
echo "  Secret Key: " . substr($secretKey, 0, 20) . "..." . substr($secretKey, -4) . "\n";
echo "\n";

// Prepare test data
$orderId = 'TEST-' . time();
$testData = [
    'x_test_mode' => 'on',
    'x_shopid' => $shopId,
    'x_amount' => '100.00',
    'x_order_id' => $orderId,
    'x_response_url' => 'http://localhost/payzy/success',
    'x_first_name' => 'John',
    'x_last_name' => 'Doe',
    'x_company' => 'Test Company',
    'x_address' => '123 Test St',
    'x_country' => 'Sri Lanka',
    'x_state' => 'Western',
    'x_city' => 'Colombo',
    'x_zip' => '10100',
    'x_phone' => '1234567890',
    'x_email' => 'test@example.com',
    'x_ship_to_first_name' => 'John',
    'x_ship_to_last_name' => 'Doe',
    'x_ship_to_company' => 'Test Company',
    'x_ship_to_address' => '123 Test St',
    'x_ship_to_country' => 'Sri Lanka',
    'x_ship_to_state' => 'Western',
    'x_ship_to_city' => 'Colombo',
    'x_ship_to_zip' => '10100',
    'x_freight' => 'x_freight',
    'x_platform' => 'custom',
    'x_version' => '1.0',
    'signed_field_names' => 'x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names',
];

echo "Test Order:\n";
echo "  Order ID: $orderId\n";
echo "  Amount: {$testData['x_amount']}\n";
echo "\n";

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

echo "Signature: " . substr($signature, 0, 30) . "...\n";
echo "\n";
echo "Making API request to Payzy...\n";
echo "─────────────────────────────────────────────────────────────\n";

// Make API call using cURL
$ch = curl_init('https://api.payzypay.xyz/checkout/custom-checkout');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                     RESULTS                                ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

if ($error) {
    echo "❌ cURL Error: $error\n\n";
    exit(1);
}

echo "HTTP Status: $httpCode\n";
echo "\n";

$responseData = json_decode($response, true);

if ($responseData) {
    echo "Response:\n";
    echo json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
    echo "\n";
} else {
    echo "Raw Response:\n";
    echo $response . "\n";
    echo "\n";
}

// Analyze response
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                     ANALYSIS                               ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

if ($httpCode == 200 || $httpCode == 201) {
    if (isset($responseData['url'])) {
        $paymentUrl = $responseData['url'];
        echo "✅ API Request: SUCCESS\n";
        echo "✅ Payment URL: $paymentUrl\n";
        echo "\n";
        
        // Check if it's a generic error URL
        if (strpos($paymentUrl, '/fromwordpress/e1') !== false) {
            echo "⚠️  WARNING: Generic Error Page Detected!\n";
            echo "─────────────────────────────────────────────────────────────\n";
            echo "The URL is: $paymentUrl\n";
            echo "\n";
            echo "This is a GENERIC ERROR PAGE, not a real payment session.\n";
            echo "\n";
            echo "What this means:\n";
            echo "  ❌ Shop ID is not properly configured in Payzy's system\n";
            echo "  ❌ These might be demo/test credentials that don't work\n";
            echo "  ❌ No real payment session is being created\n";
            echo "  ❌ You'll see 'invalid temp order id' error on that page\n";
            echo "\n";
            echo "What to do:\n";
            echo "  1. Register for a Payzy merchant account\n";
            echo "  2. Or contact Payzy support for working test credentials\n";
            echo "  3. Use your real Shop ID and Secret Key\n";
            echo "\n";
            echo "Contact:\n";
            echo "  • Payzy Website: https://payzy.lk\n";
            echo "  • Merchant Portal: https://merchant.payzy.lk/\n";
            echo "  • Support: Check https://payzy.lk/FAQ\n";
            echo "\n";
        } else {
            echo "✅ SUCCESS: Credentials are working!\n";
            echo "─────────────────────────────────────────────────────────────\n";
            echo "A unique payment URL was generated.\n";
            echo "This means your Shop ID and Secret Key are valid.\n";
            echo "\n";
            echo "You can test the payment flow by visiting:\n";
            echo "  $paymentUrl\n";
            echo "\n";
            echo "✅ Your integration is ready to use!\n";
            echo "\n";
        }
    } else {
        echo "⚠️  Unexpected Response Structure\n";
        echo "─────────────────────────────────────────────────────────────\n";
        echo "Expected a 'url' field in the response.\n";
        echo "Got: " . json_encode($responseData) . "\n";
        echo "\n";
    }
} else if ($httpCode == 400) {
    echo "❌ Bad Request (400)\n";
    echo "─────────────────────────────────────────────────────────────\n";
    echo "Possible issues:\n";
    echo "  • Invalid signature\n";
    echo "  • Missing required fields\n";
    echo "  • Incorrect data format\n";
    echo "\n";
} else if ($httpCode == 401 || $httpCode == 403) {
    echo "❌ Authentication Failed ($httpCode)\n";
    echo "─────────────────────────────────────────────────────────────\n";
    echo "Possible issues:\n";
    echo "  • Invalid Shop ID\n";
    echo "  • Invalid Secret Key\n";
    echo "  • Credentials not authorized\n";
    echo "\n";
} else {
    echo "❌ Request Failed (HTTP $httpCode)\n";
    echo "─────────────────────────────────────────────────────────────\n";
    echo "Something went wrong with the API request.\n";
    echo "\n";
}

echo "════════════════════════════════════════════════════════════\n";
echo "\n";
echo "💡 Usage:\n";
echo "  php validate_payzy.php\n";
echo "  php validate_payzy.php SHOP_ID SECRET_KEY\n";
echo "\n";
echo "📝 Example:\n";
echo "  php validate_payzy.php 2 '\$2b\$12\$82C876...\$GvWy'\n";
echo "\n";
echo "🔍 To test your own credentials:\n";
echo "  php validate_payzy.php YOUR_SHOP_ID 'YOUR_SECRET_KEY'\n";
echo "\n";
