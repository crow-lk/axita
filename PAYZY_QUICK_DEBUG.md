# 🚀 Payzy Quick Debug Commands

## ✅ Verify Setup
```bash
php test_payzy_comprehensive.php
```

## 🔍 Monitor Real-Time
```bash
tail -f storage/logs/laravel.log | grep -A 5 "Payzy"
```

## 📊 Check Configuration
```bash
php artisan tinker --execute="
  echo 'Shop ID: ' . core()->getConfigData('sales.payment_methods.payzy.shop_id') . PHP_EOL;
  echo 'Sandbox: ' . (core()->getConfigData('sales.payment_methods.payzy.sandbox') ? 'ON' : 'OFF') . PHP_EOL;
  echo 'Active: ' . (core()->getConfigData('sales.payment_methods.payzy.active') ? 'YES' : 'NO') . PHP_EOL;
"
```

## 🔄 Reconfigure
```bash
php setup_payzy_config.php
php artisan config:clear
php artisan cache:clear
```

## 📝 View Recent Logs
```bash
grep "Payzy" storage/logs/laravel.log | tail -20
```

## 🧪 Test API
```bash
php test_payzy_flow.php
```

## 📈 Count Orders
```bash
php artisan tinker --execute="
  echo DB::table('orders')
    ->join('order_payment', 'orders.id', '=', 'order_payment.order_id')
    ->where('order_payment.method', 'payzy')
    ->count() . ' Payzy orders' . PHP_EOL;
"
```

## 🔐 Test Signature
```bash
php test_payzy_signature_debug.php
```

## 🛣️ Verify Routes
```bash
php artisan route:list | grep payzy
```

## 🧹 Clear Everything
```bash
php artisan config:clear && \
php artisan cache:clear && \
php artisan view:clear && \
php artisan route:clear
```

## 🐛 Debug Single Payment
When testing a payment, watch these in real-time:
```bash
# Terminal 1: Watch logs
tail -f storage/logs/laravel.log | grep -E "(Payzy|payzy)"

# Terminal 2: Watch sessions
ls -lt storage/framework/sessions/ | head -5
```

## 🆘 Emergency Reset
If payment is completely broken:
```bash
# 1. Reconfigure
php setup_payzy_config.php

# 2. Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 3. Verify
php test_payzy_comprehensive.php
```

## 📞 Support Info
- **Payzy Test Shop ID:** 2
- **Payzy API:** https://api.payzypay.xyz/checkout/custom-checkout
- **Test Portal:** test@payzy.lk / Test@!123
- **Sandbox Mode:** Must be ON for testing

## 🎯 Quick Status Check
```bash
php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();
\$status = [
  'Shop ID' => core()->getConfigData('sales.payment_methods.payzy.shop_id') ?: 'NOT SET',
  'Active' => core()->getConfigData('sales.payment_methods.payzy.active') ? 'YES' : 'NO',
  'Sandbox' => core()->getConfigData('sales.payment_methods.payzy.sandbox') ? 'ON' : 'OFF',
];
foreach(\$status as \$k => \$v) echo \"\$k: \$v\n\";
"
```

## 🎨 Pretty Log View
```bash
tail -f storage/logs/laravel.log | \
  grep --color=always -E "Payzy.*|$" | \
  sed 's/production.INFO:/✅/' | \
  sed 's/production.ERROR:/❌/' | \
  sed 's/production.WARNING:/⚠️/'
```

---
**Keep this handy for quick debugging!** 🔧
