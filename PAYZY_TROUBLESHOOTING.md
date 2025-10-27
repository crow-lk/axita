# Payzy Payment Integration - Troubleshooting Guide

## Issues Found and Fixed

### 1. **Missing Configuration** ❌ → ✅ FIXED
**Problem:** Shop ID and Secret Key were not configured in the database.

**Solution:** Run `setup_payzy_config.php` to insert the test credentials:
```bash
php setup_payzy_config.php
php artisan config:clear
php artisan cache:clear
```

**Test Credentials:**
- Shop ID: `2`
- Secret Key: `$2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy`
- Sandbox Mode: Enabled

### 2. **Enhanced Logging** ✅ ADDED
Added comprehensive logging to `PayzyController.php` to help debug issues:
- Payment request data logging
- API response logging
- Signature generation logging
- Signature verification logging

**View Logs:**
```bash
tail -f storage/logs/laravel.log | grep Payzy
```

### 3. **Image Loading Error** ⚠️ NON-CRITICAL
The Payzy payment logo was causing errors because the image file doesn't exist.

**Solution:** Either:
1. Upload a logo in Admin Panel → Configuration → Sales → Payment Methods → Payzy
2. Ignore the error (it's non-critical and doesn't affect payment processing)

## How to Test Payzy Payment

### 1. From Browser
1. Add items to cart
2. Go to checkout
3. Select "Payzy Payment Gateway" as payment method
4. Complete the order
5. You'll be redirected to Payzy payment page
6. After payment, you'll be redirected back to success or cancel URL

### 2. Using Test Script
```bash
php test_payzy_flow.php
```

This will:
- Check configuration
- Generate a test payment request
- Make API call to Payzy
- Display the payment URL

### 3. Check Signature Generation
```bash
php test_payzy_signature_debug.php
```

This verifies that signature generation matches Payzy's expected format.

## Common Issues and Solutions

### Issue: "Cart not found" error
**Cause:** Cart session expired or was cleared before payment completion.

**Solution:**
- Ensure user completes payment quickly
- Don't clear cart until payment is verified
- Check session configuration in `.env`

### Issue: "Payment gateway is not properly configured"
**Cause:** Shop ID or Secret Key not set.

**Solution:**
1. Run: `php setup_payzy_config.php`
2. Or manually configure in Admin Panel:
   - Go to Configuration → Sales → Payment Methods → Payzy
   - Enter Shop ID and Secret Key
   - Enable Sandbox Mode for testing
   - Set Active to "Yes"

### Issue: "Signature verification failed"
**Cause:** Mismatch between sent and received signature.

**Check:**
1. Verify Secret Key is correct
2. Check logs for signature comparison:
   ```bash
   grep "Signature Verification" storage/logs/laravel.log
   ```
3. Ensure no data modification during payment

### Issue: Payment succeeds but order not created
**Cause:** Response handling or cart issues.

**Debug:**
1. Check logs for success callback:
   ```bash
   grep "success callback" storage/logs/laravel.log
   ```
2. Verify `response_code` is `00`
3. Check if cart still exists when callback is received

## Understanding Payzy Flow

### Request Flow:
```
1. Customer → Checkout with Payzy
2. Bagisto → Generate signature with order data
3. Bagisto → POST to Payzy API
4. Payzy API → Returns payment URL
5. Bagisto → Redirects customer to payment URL
6. Customer → Completes payment on Payzy
7. Payzy → Redirects back to success/cancel URL
8. Bagisto → Verifies signature
9. Bagisto → Creates order if payment successful
```

### Signature Generation:
```php
// Fields to sign (in exact order)
$fields = [
    'x_test_mode', 'x_shopid', 'x_amount', 'x_order_id',
    'x_response_url', 'x_first_name', 'x_last_name', 'x_company',
    'x_address', 'x_country', 'x_state', 'x_city', 'x_zip',
    'x_phone', 'x_email', 'x_ship_to_first_name', 'x_ship_to_last_name',
    'x_ship_to_company', 'x_ship_to_address', 'x_ship_to_country',
    'x_ship_to_state', 'x_ship_to_city', 'x_ship_to_zip',
    'x_freight', 'x_platform', 'x_version', 'signed_field_names'
];

// Build string: field1=value1,field2=value2,...
// Generate HMAC-SHA256 with secret key
// Base64 encode the result
```

### Response Verification:
```php
// Fields to verify (with response_code first)
$fields = [
    'response_code', 'x_test_mode', 'x_shopid', 'x_amount',
    // ... all other fields same as request
];

// Same signature generation process
// Compare with received signature
```

## Monitoring Payments

### Real-time Logs:
```bash
# All Payzy activity
tail -f storage/logs/laravel.log | grep -A 5 "Payzy"

# Just payment requests
tail -f storage/logs/laravel.log | grep "Payment Request"

# Just API responses
tail -f storage/logs/laravel.log | grep "API Response"

# Signature issues
tail -f storage/logs/laravel.log | grep "Signature"
```

### Database Checks:
```bash
# Check orders created via Payzy
php artisan tinker --execute="
  \$orders = DB::table('orders')
    ->join('order_payment', 'orders.id', '=', 'order_payment.order_id')
    ->where('order_payment.method', 'payzy')
    ->select('orders.*', 'order_payment.additional')
    ->get();
  foreach(\$orders as \$order) {
    echo 'Order #' . \$order->id . ' - ' . \$order->status . PHP_EOL;
  }
"
```

## Payzy API Documentation

### Endpoints:
- **Payment API:** `https://api.payzypay.xyz/checkout/custom-checkout`
- **Payment Page:** `https://app.payzypay.xyz/fromwordpress/*`

### Test Mode:
- Set `x_test_mode` to `on` for testing
- No real money transactions in test mode
- Use test cards provided by Payzy

### Response Codes:
- `00` - Payment successful
- Other codes - Payment failed/cancelled

## Files Modified

### Core Implementation:
1. `/packages/Webkul/Payment/src/Payment/Payzy.php` - Payment method class
2. `/packages/Webkul/Payment/src/Http/Controllers/PayzyController.php` - Payment controller with enhanced logging
3. `/packages/Webkul/Payment/src/Http/routes.php` - Payment routes
4. `/packages/Webkul/Payment/src/Config/paymentmethods.php` - Payment method config
5. `/packages/Webkul/Admin/src/Config/system.php` - Admin configuration fields

### Helper Scripts:
1. `setup_payzy_config.php` - Insert configuration into database
2. `test_payzy_flow.php` - Test payment API integration
3. `test_payzy_signature_debug.php` - Verify signature generation

## Next Steps

### For Production:
1. Get production credentials from Payzy
2. Update configuration in Admin Panel
3. Disable sandbox mode
4. Test with small amount first
5. Monitor logs for any issues

### For Development:
1. Keep sandbox mode enabled
2. Use test credentials
3. Monitor logs during testing
4. Test all scenarios:
   - Successful payment
   - Cancelled payment
   - Failed payment
   - Expired cart
   - Network issues

## Support

### Payzy Support:
- Website: https://payzy.lk
- Developer Documentation: https://payzy.lk/dev-doc
- Support Email: [Check Payzy website]

### Bagisto Community:
- Documentation: https://bagisto.com/en/documentation/
- Forums: https://forums.bagisto.com/
- GitHub: https://github.com/bagisto/bagisto

## Change Log

### 2025-10-25
- ✅ Added comprehensive logging to PayzyController
- ✅ Fixed missing configuration issue
- ✅ Created setup script for test credentials
- ✅ Created test scripts for debugging
- ✅ Documented troubleshooting steps
