<?php
/**
 * Test signature generation matching EXACT Payzy documentation
 */

// According to the docs, this is the correct way
$key = '$2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy';

$data = [
    'x_test_mode' => 'on',
    'x_shopid' => '2',
    'x_amount' => '10',
    'x_order_id' => 'ABC-0001',
    'x_response_url' => 'http://localhost:8080/responce.html',
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
    'x_version' => '1.0',
    'x_platform' => 'custom',
];

echo "=== Method 1: Using pack() (as per docs) ===\n\n";

// Build the list according to docs
$list = implode(',', [
    "x_test_mode={$data['x_test_mode']}",
    "x_shopid={$data['x_shopid']}",
    "x_amount={$data['x_amount']}",
    "x_order_id={$data['x_order_id']}",
    "x_response_url={$data['x_response_url']}",
    "x_first_name={$data['x_first_name']}",
    "x_last_name={$data['x_last_name']}",
    "x_company={$data['x_company']}",
    "x_address={$data['x_address']}",
    "x_country={$data['x_country']}",
    "x_state={$data['x_state']}",
    "x_city={$data['x_city']}",
    "x_zip={$data['x_zip']}",
    "x_phone={$data['x_phone']}",
    "x_email={$data['x_email']}",
    "x_ship_to_first_name={$data['x_ship_to_first_name']}",
    "x_ship_to_last_name={$data['x_ship_to_last_name']}",
    "x_ship_to_company={$data['x_ship_to_company']}",
    "x_ship_to_address={$data['x_ship_to_address']}",
    "x_ship_to_country={$data['x_ship_to_country']}",
    "x_ship_to_state={$data['x_ship_to_state']}",
    "x_ship_to_city={$data['x_ship_to_city']}",
    "x_ship_to_zip={$data['x_ship_to_zip']}",
    "x_freight={$data['x_freight']}",
    "x_platform={$data['x_platform']}",
    "x_version={$data['x_version']}",  // WITH = sign
    "signed_field_names=x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names"
]);

echo "String to sign:\n$list\n\n";

// Method from docs
$hash1 = hash_hmac('sha256', $list, $key, false);
$signature1 = base64_encode(pack('H*', $hash1));

echo "Signature (docs method): $signature1\n\n";

echo "=== Method 2: Direct binary (current implementation) ===\n\n";

// Our current method
$hash2 = hash_hmac('sha256', $list, $key, true);
$signature2 = base64_encode($hash2);

echo "Signature (binary method): $signature2\n\n";

echo "=== Are they the same? ===\n";
echo ($signature1 === $signature2 ? "✅ YES" : "❌ NO") . "\n\n";

echo "=== Testing WITH the typo (missing = in x_version) ===\n\n";

// With the typo from sample code
$listWithTypo = implode(',', [
    "x_test_mode={$data['x_test_mode']}",
    "x_shopid={$data['x_shopid']}",
    "x_amount={$data['x_amount']}",
    "x_order_id={$data['x_order_id']}",
    "x_response_url={$data['x_response_url']}",
    "x_first_name={$data['x_first_name']}",
    "x_last_name={$data['x_last_name']}",
    "x_company={$data['x_company']}",
    "x_address={$data['x_address']}",
    "x_country={$data['x_country']}",
    "x_state={$data['x_state']}",
    "x_city={$data['x_city']}",
    "x_zip={$data['x_zip']}",
    "x_phone={$data['x_phone']}",
    "x_email={$data['x_email']}",
    "x_ship_to_first_name={$data['x_ship_to_first_name']}",
    "x_ship_to_last_name={$data['x_ship_to_last_name']}",
    "x_ship_to_company={$data['x_ship_to_company']}",
    "x_ship_to_address={$data['x_ship_to_address']}",
    "x_ship_to_country={$data['x_ship_to_country']}",
    "x_ship_to_state={$data['x_ship_to_state']}",
    "x_ship_to_city={$data['x_ship_to_city']}",
    "x_ship_to_zip={$data['x_ship_to_zip']}",
    "x_freight={$data['x_freight']}",
    "x_platform={$data['x_platform']}",
    "x_version{$data['x_version']}",  // WITHOUT = sign (THE TYPO!)
    "signed_field_names=x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names"
]);

$hashTypo = hash_hmac('sha256', $listWithTypo, $key, true);
$signatureTypo = base64_encode($hashTypo);

echo "Signature (WITH typo): $signatureTypo\n\n";
