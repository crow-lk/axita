<?php

/**
 * Test script to verify Payzy signature generation matches the sample
 * Run with: php test_payzy_signature_debug.php
 */

// Test data matching the sample project
$secretKey = '$2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy';

$data = [
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

// Build the signature string
$signedFields = explode(',', $data['signed_field_names']);
$dataString = '';

foreach ($signedFields as $field) {
    $dataString .= $field . '=' . ($data[$field] ?? '') . ',';
}

// Remove trailing comma
$dataString = rtrim($dataString, ',');

echo "=== Payzy Signature Test (PHP) ===\n\n";
echo "Data String:\n";
echo $dataString . "\n\n";

// Generate HMAC SHA256 hash
$hash = hash_hmac('sha256', $dataString, $secretKey, true);

// Convert to Base64
$signature = base64_encode($hash);

echo "Generated Signature:\n";
echo $signature . "\n\n";

// Now test verification for response
echo "=== Response Verification Test ===\n\n";

$responseData = [
    'response_code'         => '00',
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
    'signed_field_names'    => 'response_code,x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names',
];

// Build the response signature string
$responseSignedFields = explode(',', $responseData['signed_field_names']);
$responseDataString = '';

foreach ($responseSignedFields as $field) {
    $responseDataString .= $field . '=' . ($responseData[$field] ?? '') . ',';
}

// Remove trailing comma
$responseDataString = rtrim($responseDataString, ',');

echo "Response Data String:\n";
echo $responseDataString . "\n\n";

// Generate HMAC SHA256 hash for response
$responseHash = hash_hmac('sha256', $responseDataString, $secretKey, true);

// Convert to Base64
$responseSignature = base64_encode($responseHash);

echo "Response Signature:\n";
echo $responseSignature . "\n\n";

echo "Done!\n";
