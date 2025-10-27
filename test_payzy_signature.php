<?php

/**
 * Test script to verify Payzy signature generation
 * Run with: php test_payzy_signature.php
 */

// Test data from Payzy sample
$secretKey = '$2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy';

$testData = [
    'x_test_mode'           => 'on',
    'x_shopid'              => '2',
    'x_amount'              => '10',
    'x_order_id'            => 'ABC-0001',
    'x_response_url'        => 'http://localhost:8080/responce.html',
    'x_first_name'          => 'John',
    'x_last_name'           => 'Doe',
    'x_company'             => 'ABC Company',
    'x_address'             => '123 Main St',
    'x_country'             => 'Sri Lanka',
    'x_state'               => 'Western',
    'x_city'                => 'Colombo',
    'x_zip'                 => '12345',
    'x_phone'               => '1234567890',
    'x_email'               => 'exampl@email.com',
    'x_ship_to_first_name'  => 'John',
    'x_ship_to_last_name'   => 'Doe',
    'x_ship_to_company'     => 'ABC Company',
    'x_ship_to_address'     => '123 Main St',
    'x_ship_to_country'     => 'Sri Lanka',
    'x_ship_to_state'       => 'Western',
    'x_ship_to_city'        => 'Colombo',
    'x_ship_to_zip'         => '12345',
    'x_freight'             => 'x_freight',
    'x_platform'            => 'custom',
    'x_version'             => '1.0',
    'signed_field_names'    => 'x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names',
];

echo "=== Payzy Signature Test ===\n\n";

// Method 1: Our implementation (correct)
function generateSignature1($data, $secretKey) {
    $signedFields = explode(',', $data['signed_field_names']);
    $dataString = '';
    
    foreach ($signedFields as $field) {
        $dataString .= $field . '=' . ($data[$field] ?? '') . ',';
    }
    
    $dataString = rtrim($dataString, ',');
    $hash = hash_hmac('sha256', $dataString, $secretKey, true);
    
    return base64_encode($hash);
}

// Method 2: Matching JavaScript sample exactly (has the typo)
function generateSignature2($data, $secretKey) {
    $list = 'x_test_mode=' . $data['x_test_mode'] .
            ',x_shopid=' . $data['x_shopid'] .
            ',x_amount=' . $data['x_amount'] .
            ',x_order_id=' . $data['x_order_id'] .
            ',x_response_url=' . $data['x_response_url'] .
            ',x_first_name=' . $data['x_first_name'] .
            ',x_last_name=' . $data['x_last_name'] .
            ',x_company=' . $data['x_company'] .
            ',x_address=' . $data['x_address'] .
            ',x_country=' . $data['x_country'] .
            ',x_state=' . $data['x_state'] .
            ',x_city=' . $data['x_city'] .
            ',x_zip=' . $data['x_zip'] .
            ',x_phone=' . $data['x_phone'] .
            ',x_email=' . $data['x_email'] .
            ',x_ship_to_first_name=' . $data['x_ship_to_first_name'] .
            ',x_ship_to_last_name=' . $data['x_ship_to_last_name'] .
            ',x_ship_to_company=' . $data['x_ship_to_company'] .
            ',x_ship_to_address=' . $data['x_ship_to_address'] .
            ',x_ship_to_country=' . $data['x_ship_to_country'] .
            ',x_ship_to_state=' . $data['x_ship_to_state'] .
            ',x_ship_to_city=' . $data['x_ship_to_city'] .
            ',x_ship_to_zip=' . $data['x_ship_to_zip'] .
            ',x_freight=' . $data['x_freight'] .
            ',x_platform=' . $data['x_platform'] .
            ',x_version=' . $data['x_version'] . // Note: JS sample has typo here (missing =)
            ',signed_field_names=' . $data['signed_field_names'];
    
    $hash = hash_hmac('sha256', $list, $secretKey, true);
    return base64_encode($hash);
}

$signature1 = generateSignature1($testData, $secretKey);
$signature2 = generateSignature2($testData, $secretKey);

echo "Method 1 (Our implementation - correct):\n";
echo "Signature: $signature1\n\n";

echo "Method 2 (Matching JS sample exactly):\n";
echo "Signature: $signature2\n\n";

echo "Signatures match: " . ($signature1 === $signature2 ? "YES ✓" : "NO ✗") . "\n\n";

// Display the data string being hashed
$signedFields = explode(',', $testData['signed_field_names']);
$dataString = '';
foreach ($signedFields as $field) {
    $dataString .= $field . '=' . ($testData[$field] ?? '') . ',';
}
$dataString = rtrim($dataString, ',');

echo "Data string for hashing:\n";
echo $dataString . "\n\n";

// Test with actual request data
echo "=== Full Request Data ===\n";
$testData['signature'] = $signature1;
echo json_encode($testData, JSON_PRETTY_PRINT) . "\n\n";

// Verify all required fields are present
echo "=== Field Verification ===\n";
$requiredFields = explode(',', $testData['signed_field_names']);
$missingFields = [];
foreach ($requiredFields as $field) {
    if (!isset($testData[$field]) || $testData[$field] === '') {
        $missingFields[] = $field;
    }
}

if (empty($missingFields)) {
    echo "✓ All required fields are present\n";
} else {
    echo "✗ Missing fields: " . implode(', ', $missingFields) . "\n";
}

echo "\n=== Test Complete ===\n";
