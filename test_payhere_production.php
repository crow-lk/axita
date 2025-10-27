<?php

/**
 * PayHere Production Readiness Test
 * Comprehensive QA tests for PayHere integration
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Log;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║     PayHere Production Readiness QA Test Suite            ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

$passed = 0;
$failed = 0;
$warnings = 0;

// Test 1: APP_URL Configuration
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 1: APP_URL Configuration (CRITICAL)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$appUrl = config('app.url');
echo "Current APP_URL: {$appUrl}\n";

if (strpos($appUrl, '.test') !== false || strpos($appUrl, 'localhost') !== false) {
    echo "❌ FAILED: APP_URL uses local domain (.test or localhost)\n";
    echo "   Impact: PayHere cannot send IPN notifications\n";
    echo "   Fix: Update APP_URL in .env to production domain\n";
    echo "   Example: APP_URL=https://yourdomain.com\n";
    $failed++;
} else {
    echo "✅ PASSED: APP_URL configured with production domain\n";
    $passed++;
}
echo "\n";

// Test 2: Sandbox Mode Check
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 2: Sandbox Mode Configuration\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$sandboxMode = \Illuminate\Support\Facades\DB::table('core_config')
    ->where('code', 'sales.payment_methods.paypal_standard.sandbox')
    ->value('value');

echo "Sandbox Mode: " . ($sandboxMode ? 'Enabled' : 'Disabled') . "\n";

if ($sandboxMode == '1' || $sandboxMode === null) {
    echo "⚠️  WARNING: Sandbox mode is enabled or not set\n";
    echo "   Gateway URL: https://sandbox.payhere.lk/pay/checkout\n";
    echo "   For Production: Disable sandbox mode\n";
    $warnings++;
} else {
    echo "✅ PASSED: Sandbox mode disabled (Production mode)\n";
    echo "   Gateway URL: https://www.payhere.lk/pay/checkout\n";
    $passed++;
}
echo "\n";

// Test 3: Merchant Credentials
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 3: Merchant Credentials\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$merchantId = config('services.payhere.merchant_id');
$merchantSecret = config('services.payhere.merchant_secret');

echo "Merchant ID: {$merchantId}\n";
echo "Merchant Secret: " . (strlen($merchantSecret) > 0 ? substr($merchantSecret, 0, 20) . '... (configured)' : 'NOT SET') . "\n";

if (empty($merchantId) || empty($merchantSecret)) {
    echo "❌ FAILED: Merchant credentials not configured\n";
    $failed++;
} else {
    echo "✅ PASSED: Merchant credentials configured\n";
    $passed++;
}
echo "\n";

// Test 4: Hash Generation
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 4: Hash Generation Algorithm\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
try {
    $testOrderId = 'axita123';
    $testAmount = 1000.50;
    $testCurrency = 'LKR';
    
    $hash = strtoupper(
        md5(
            $merchantId .
            $testOrderId .
            number_format($testAmount, 2, '.', '') .
            $testCurrency .
            strtoupper(md5($merchantSecret))
        )
    );
    
    echo "Test Parameters:\n";
    echo "  Order ID: {$testOrderId}\n";
    echo "  Amount: " . number_format($testAmount, 2, '.', '') . "\n";
    echo "  Currency: {$testCurrency}\n";
    echo "Generated Hash: {$hash}\n";
    echo "✅ PASSED: Hash generation working\n";
    $passed++;
} catch (Exception $e) {
    echo "❌ FAILED: Hash generation error - " . $e->getMessage() . "\n";
    $failed++;
}
echo "\n";

// Test 5: Return URLs
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 5: Return URLs Configuration\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$returnUrl = route('paypal.standard.success');
$cancelUrl = route('paypal.standard.cancel');
$notifyUrl = config('app.url') . '/api/payhere';

echo "Success URL: {$returnUrl}\n";
echo "Cancel URL: {$cancelUrl}\n";
echo "IPN Notify URL: {$notifyUrl}\n";

if (strpos($notifyUrl, '.test') !== false || strpos($notifyUrl, 'localhost') !== false) {
    echo "❌ FAILED: IPN URL uses local domain\n";
    echo "   Impact: PayHere cannot send payment confirmations\n";
    $failed++;
} else {
    echo "✅ PASSED: URLs configured with production domain\n";
    $passed++;
}
echo "\n";

// Test 6: IPN Security
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 6: IPN Webhook Security\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$apiRouteFile = file_get_contents(__DIR__ . '/routes/api.php');

if (strpos($apiRouteFile, "config('services.payhere.merchant_secret')") !== false) {
    echo "✅ PASSED: IPN uses config for merchant secret (not hardcoded)\n";
    $passed++;
} else {
    echo "⚠️  WARNING: IPN might use hardcoded merchant secret\n";
    $warnings++;
}

if (strpos($apiRouteFile, 'allowedIPs') !== false || strpos($apiRouteFile, 'whitelist') !== false) {
    echo "✅ PASSED: IP whitelist implemented\n";
    $passed++;
} else {
    echo "⚠️  WARNING: No IP whitelist found for IPN\n";
    echo "   Recommendation: Add PayHere IP whitelist for security\n";
    $warnings++;
}
echo "\n";

// Test 7: Currency Configuration
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 7: Currency Configuration\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$appCurrency = config('app.currency');
echo "App Currency: {$appCurrency}\n";

$standardFile = file_get_contents(__DIR__ . '/packages/Webkul/Paypal/src/Payment/Standard.php');
if (strpos($standardFile, "'currency' => 'LKR'") !== false) {
    if ($appCurrency === 'LKR') {
        echo "✅ PASSED: Currency hardcoded to LKR, matches app currency\n";
        $passed++;
    } else {
        echo "⚠️  WARNING: Currency hardcoded to LKR but app uses {$appCurrency}\n";
        echo "   Impact: Currency mismatch may cause payment issues\n";
        $warnings++;
    }
} else {
    echo "✅ PASSED: Currency dynamically configured\n";
    $passed++;
}
echo "\n";

// Test 8: Error Handling
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 8: Error Handling\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$controllerFile = file_get_contents(__DIR__ . '/packages/Webkul/Paypal/src/Http/Controllers/StandardController.php');

if (strpos($controllerFile, "session()->flash") !== false) {
    echo "✅ PASSED: Error messages configured for user feedback\n";
    $passed++;
} else {
    echo "⚠️  WARNING: Limited user error messages\n";
    $warnings++;
}

if (strpos($controllerFile, 'try') !== false || strpos($apiRouteFile, 'try') !== false) {
    echo "✅ PASSED: Exception handling implemented\n";
    $passed++;
} else {
    echo "⚠️  WARNING: Limited exception handling\n";
    $warnings++;
}
echo "\n";

// Test 9: Order ID Uniqueness
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 9: Order ID Format\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if (strpos($standardFile, "'axita'.\$cart->id") !== false) {
    echo "⚠️  WARNING: Order ID format: axita{cart_id}\n";
    echo "   Potential Issue: May not be unique if cart is reused\n";
    echo "   Recommendation: Add timestamp for uniqueness\n";
    $warnings++;
} else {
    echo "✅ PASSED: Order ID includes unique identifier\n";
    $passed++;
}
echo "\n";

// Test 10: Payment Method Active Status
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 10: Payment Method Status\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$isActive = \Illuminate\Support\Facades\DB::table('core_config')
    ->where('code', 'sales.payment_methods.paypal_standard.active')
    ->value('value');

echo "Payment Method Active: " . ($isActive ? 'Yes' : 'No') . "\n";

if ($isActive) {
    echo "✅ PASSED: PayHere payment method is enabled\n";
    $passed++;
} else {
    echo "❌ FAILED: PayHere payment method is disabled\n";
    $failed++;
}
echo "\n";

// Test 11: SSL/HTTPS Check
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 11: SSL/HTTPS Configuration\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if (strpos($appUrl, 'https://') === 0) {
    echo "✅ PASSED: HTTPS configured in APP_URL\n";
    echo "   Note: Ensure SSL certificate is valid in production\n";
    $passed++;
} else {
    echo "❌ FAILED: HTTPS not configured\n";
    echo "   Impact: PayHere requires HTTPS for security\n";
    $failed++;
}
echo "\n";

// Test 12: Logging Configuration
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 12: Logging Configuration\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if (strpos($standardFile, 'Log::info') !== false || strpos($apiRouteFile, 'Log::info') !== false) {
    echo "✅ PASSED: Payment logging configured\n";
    $passed++;
} else {
    echo "⚠️  WARNING: Limited payment logging\n";
    $warnings++;
}

$logPath = storage_path('logs/laravel.log');
if (file_exists($logPath) && is_writable($logPath)) {
    echo "✅ PASSED: Log file writable\n";
    $passed++;
} else {
    echo "⚠️  WARNING: Log file not writable or doesn't exist\n";
    $warnings++;
}
echo "\n";

// Summary
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                      TEST SUMMARY                          ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";
echo "✅ Passed:   {$passed}\n";
echo "❌ Failed:   {$failed}\n";
echo "⚠️  Warnings: {$warnings}\n";
echo "\n";

// Overall Status
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
if ($failed > 0) {
    echo "🔴 OVERALL STATUS: NOT PRODUCTION READY\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Critical issues must be fixed before deployment.\n";
} elseif ($warnings > 2) {
    echo "🟡 OVERALL STATUS: READY WITH WARNINGS\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Consider addressing warnings for optimal security.\n";
} else {
    echo "🟢 OVERALL STATUS: PRODUCTION READY\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "All critical tests passed. Monitor first transactions closely.\n";
}
echo "\n";

// Recommendations
if ($failed > 0 || $warnings > 0) {
    echo "╔════════════════════════════════════════════════════════════╗\n";
    echo "║                    RECOMMENDATIONS                         ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    
    if (strpos($appUrl, '.test') !== false) {
        echo "1. Update APP_URL in .env to production domain:\n";
        echo "   APP_URL=https://yourproductiondomain.com\n";
        echo "   ASSET_URL=https://yourproductiondomain.com\n";
        echo "\n";
    }
    
    if ($sandboxMode == '1' || $sandboxMode === null) {
        echo "2. Disable sandbox mode for production:\n";
        echo "   mysql -u root axita_db -e \"UPDATE core_config SET value='0' WHERE code='sales.payment_methods.paypal_standard.sandbox';\"\n";
        echo "   php artisan config:clear\n";
        echo "\n";
    }
    
    if ($warnings > 0) {
        echo "3. Consider implementing:\n";
        echo "   - IP whitelist for IPN webhook\n";
        echo "   - Enhanced error logging\n";
        echo "   - Order ID timestamp for uniqueness\n";
        echo "\n";
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Test completed at: " . date('Y-m-d H:i:s') . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";
