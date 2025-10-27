<?php

/**
 * Insert Payzy configuration into database
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Inserting Payzy Configuration ===\n\n";

// Test credentials from documentation
$shopId = '2';
$secretKey = '$2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy';

// Get the default channel
$channel = DB::table('channels')->first();
$channelCode = $channel->code ?? 'default';

echo "Channel: $channelCode\n\n";

// Configuration to insert
$configs = [
    [
        'code' => 'sales.payment_methods.payzy.shop_id',
        'value' => $shopId,
        'channel_code' => $channelCode,
        'locale_code' => null,
    ],
    [
        'code' => 'sales.payment_methods.payzy.secret_key',
        'value' => $secretKey,
        'channel_code' => $channelCode,
        'locale_code' => null,
    ],
    [
        'code' => 'sales.payment_methods.payzy.sandbox',
        'value' => '1',  // Enable sandbox mode
        'channel_code' => $channelCode,
        'locale_code' => null,
    ],
    [
        'code' => 'sales.payment_methods.payzy.active',
        'value' => '1',  // Enable payment method
        'channel_code' => $channelCode,
        'locale_code' => null,
    ],
    [
        'code' => 'sales.payment_methods.payzy.title',
        'value' => 'Payzy Payment Gateway',
        'channel_code' => $channelCode,
        'locale_code' => 'en',
    ],
    [
        'code' => 'sales.payment_methods.payzy.description',
        'value' => 'Pay securely using Payzy',
        'channel_code' => $channelCode,
        'locale_code' => 'en',
    ],
    [
        'code' => 'sales.payment_methods.payzy.sort',
        'value' => '3',
        'channel_code' => $channelCode,
        'locale_code' => null,
    ],
    [
        'code' => 'sales.payment_methods.payzy.generate_invoice',
        'value' => '1',
        'channel_code' => $channelCode,
        'locale_code' => null,
    ],
    [
        'code' => 'sales.payment_methods.payzy.invoice_status',
        'value' => 'paid',
        'channel_code' => $channelCode,
        'locale_code' => null,
    ],
    [
        'code' => 'sales.payment_methods.payzy.order_status',
        'value' => 'processing',
        'channel_code' => $channelCode,
        'locale_code' => null,
    ],
];

foreach ($configs as $config) {
    // Check if config already exists
    $existing = DB::table('core_config')
        ->where('code', $config['code'])
        ->where('channel_code', $config['channel_code'])
        ->where('locale_code', $config['locale_code'])
        ->first();
    
    if ($existing) {
        // Update
        DB::table('core_config')
            ->where('id', $existing->id)
            ->update([
                'value' => $config['value'],
                'updated_at' => now(),
            ]);
        echo "Updated: {$config['code']}\n";
    } else {
        // Insert
        DB::table('core_config')->insert([
            'code' => $config['code'],
            'value' => $config['value'],
            'channel_code' => $config['channel_code'],
            'locale_code' => $config['locale_code'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "Inserted: {$config['code']}\n";
    }
}

echo "\n=== Configuration Complete ===\n";
echo "Shop ID: $shopId\n";
echo "Sandbox Mode: Enabled\n";
echo "Payment Method: Active\n\n";

echo "Please clear the cache:\n";
echo "php artisan config:clear\n";
echo "php artisan cache:clear\n";
