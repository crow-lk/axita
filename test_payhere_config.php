<?php

/**
 * PayHere Configuration Test Script
 * 
 * This script verifies PayHere payment gateway configuration
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "==========================================\n";
echo "PayHere Configuration Test\n";
echo "==========================================\n\n";

// 1. Check environment variables
echo "1. Environment Variables:\n";
echo "   PAYHERE_MERCHANT_ID: " . env('PAYHERE_MERCHANT_ID', 'NOT SET') . "\n";
echo "   PAYHERE_MERCHANT_SECRET: " . (env('PAYHERE_MERCHANT_SECRET') ? substr(env('PAYHERE_MERCHANT_SECRET'), 0, 20) . '...' : 'NOT SET') . "\n";
echo "\n";

// 2. Check config values
echo "2. Config Values:\n";
echo "   Merchant ID: " . config('services.payhere.merchant_id', 'NOT SET') . "\n";
echo "   Merchant Secret: " . (config('services.payhere.merchant_secret') ? substr(config('services.payhere.merchant_secret'), 0, 20) . '...' : 'NOT SET') . "\n";
echo "\n";

// 3. Check payment method configuration in database
echo "3. Database Configuration:\n";
$configs = \Illuminate\Support\Facades\DB::table('core_config')
    ->where('code', 'LIKE', 'sales.payment_methods.paypal_standard%')
    ->get();

foreach ($configs as $config) {
    $key = str_replace('sales.payment_methods.paypal_standard.', '', $config->code);
    $value = $config->value ?: 'NULL';
    if (strlen($value) > 50) {
        $value = substr($value, 0, 47) . '...';
    }
    echo "   {$key}: {$value}\n";
}
echo "\n";

// 4. Test PayHere class instantiation
echo "4. PayHere Class Test:\n";
try {
    $payhere = app('Webkul\Paypal\Payment\Standard');
    echo "   ✅ PayHere class instantiated successfully\n";
    echo "   Title: " . $payhere->getTitle() . "\n";
    echo "   Description: " . $payhere->getDescription() . "\n";
    echo "   Code: " . $payhere->getCode() . "\n";
    echo "   Active: " . ($payhere->isActive() ? 'Yes' : 'No') . "\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 5. Test hash generation
echo "5. Hash Generation Test:\n";
try {
    $merchantId = config('services.payhere.merchant_id');
    $merchantSecret = config('services.payhere.merchant_secret');
    $orderId = 'axita123';
    $amount = '100.00';
    $currency = 'LKR';
    
    // Decode base64 secret if needed
    $decodedSecret = base64_decode($merchantSecret);
    
    // Generate hash same way as Standard.php
    $hash = strtoupper(
        md5(
            $merchantId .
            $orderId .
            $amount .
            $currency .
            strtoupper(md5($merchantSecret))
        )
    );
    
    echo "   Test Parameters:\n";
    echo "     Merchant ID: {$merchantId}\n";
    echo "     Order ID: {$orderId}\n";
    echo "     Amount: {$amount}\n";
    echo "     Currency: {$currency}\n";
    echo "   Generated Hash: {$hash}\n";
    echo "   ✅ Hash generation successful\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 6. Check routes
echo "6. PayHere Routes:\n";
try {
    echo "   Redirect URL: " . route('paypal.standard.redirect') . "\n";
    echo "   Success URL: " . route('paypal.standard.success') . "\n";
    echo "   Cancel URL: " . route('paypal.standard.cancel') . "\n";
    echo "   IPN URL: " . url('/api/payhere') . "\n";
    echo "   ✅ All routes available\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 7. Check PayHere gateway URL in blade template
echo "7. Payment Gateway:\n";
echo "   Sandbox URL: https://sandbox.payhere.lk/pay/checkout\n";
echo "   Production URL: https://www.payhere.lk/pay/checkout\n";
echo "   Current Mode: " . (config('services.payhere.sandbox', true) ? 'Sandbox' : 'Production') . "\n";
echo "\n";

echo "==========================================\n";
echo "✅ PayHere Configuration Test Complete\n";
echo "==========================================\n";
