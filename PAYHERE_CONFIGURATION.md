# PayHere Payment Gateway Configuration Guide

## Overview

PayHere is a Sri Lankan payment gateway that has been integrated to replace PayPal Standard. The payment method is fully configured and ready for use.

## Configuration Details

### 1. Environment Variables (.env)

```env
PAYHERE_MERCHANT_ID=1232241
PAYHERE_MERCHANT_SECRET=MTM1Mzc1NDUxMzQwMjAwNzU1MjczNDU0MzUyMTIyOTA1NTg1MDQ=
```

**Note:** The merchant secret is base64 encoded in the .env file.

### 2. Admin Panel Configuration

The PayHere payment method can be configured from the Bagisto admin panel:

**Path:** Admin → Configuration → Sales → Payment Methods → PayPal Standard (PayHere)

**Available Settings:**
- **Title:** PayHere
- **Description:** PayHere Standard
- **Business Account:** info@axita.lk (merchant email)
- **Active:** Yes (enabled)
- **Sandbox Mode:** Yes (for testing)
- **Sort Order:** 2
- **Payment Logo:** Custom PayHere logo uploaded

### 3. Technical Implementation

**Files:**
- Payment Class: `packages/Webkul/Paypal/src/Payment/Standard.php`
- Base Class: `packages/Webkul/Paypal/src/Payment/Payhere.php`
- Controller: `packages/Webkul/Paypal/src/Http/Controllers/StandardController.php`
- Blade Template: `packages/Webkul/Paypal/src/Resources/views/standard-redirect.blade.php`
- IPN Route: `routes/api.php` (POST /api/payhere)

**Payment Flow:**
1. Customer selects PayHere at checkout
2. Clicks "Place Order" → Redirects to `/paypal/standard/redirect`
3. Payment form is auto-submitted to PayHere gateway
4. Customer completes payment on PayHere
5. PayHere sends IPN (Instant Payment Notification) to `/api/payhere`
6. Customer redirected back to success/cancel page

### 4. Hash/Signature Generation

PayHere uses MD5 hash for security:

```php
$hash = strtoupper(
    md5(
        $merchantId .
        $orderId .
        number_format($amount, 2, '.', '') .
        $currency .
        strtoupper(md5($merchantSecret))
    )
);
```

**Important:** The merchant secret is double-hashed (MD5 of MD5).

### 5. Order ID Format

Order IDs are prefixed with "axita" followed by the cart ID:
```
axita{cart_id}
```
Example: `axita123`

### 6. IPN Webhook Configuration

**Endpoint:** `https://axita.test/api/payhere`

**What it does:**
1. Receives payment confirmation from PayHere
2. Verifies MD5 signature to prevent fraud
3. Checks status code (2 = success)
4. Creates invoice and marks order as "processing"

**Security:** The webhook verifies the signature using the merchant secret from config.

### 7. Payment Gateway URLs

- **Sandbox (Testing):** https://sandbox.payhere.lk/pay/checkout
- **Production (Live):** https://www.payhere.lk/pay/checkout

The blade template automatically switches based on the sandbox setting in admin panel.

### 8. Currency and Amount Formatting

- **Currency:** LKR (Sri Lankan Rupee)
- **Amount Format:** Always 2 decimal places (e.g., 100.00)
- **Phone Format:** Numbers only, no special characters

## Testing PayHere Integration

### Test Script

Run the test script to verify configuration:

```bash
php test_payhere_config.php
```

This will check:
- ✅ Environment variables
- ✅ Config values
- ✅ Database configuration
- ✅ PayHere class instantiation
- ✅ Hash generation
- ✅ Routes availability
- ✅ Payment gateway URL

### Manual Testing

1. **Enable Sandbox Mode:**
   - Admin → Configuration → Sales → Payment Methods → PayPal Standard
   - Set "Sandbox" to "Yes"

2. **Add Product to Cart:**
   - Browse shop, add product, go to checkout

3. **Select PayHere:**
   - Choose "PayHere" as payment method
   - Fill billing details
   - Click "Place Order"

4. **Complete Payment:**
   - You'll be redirected to PayHere sandbox
   - Use PayHere test cards to complete payment

5. **Verify Order:**
   - Check admin panel → Sales → Orders
   - Order should be created with status "processing"
   - Invoice should be generated

## Admin Panel Configuration Steps

To configure PayHere merchant credentials from admin panel:

1. **Login to Bagisto Admin**
2. **Navigate to Configuration:**
   - Configuration → Sales → Payment Methods
3. **Find "PayPal Standard" (PayHere):**
   - This is the PayHere payment method
4. **Configure Settings:**
   - Set merchant_id: Use the .env value or update
   - Set merchant_secret: Use the .env value or update
   - Enable/Disable: Toggle "Active"
   - Sandbox Mode: Enable for testing, disable for production
5. **Save Configuration**
6. **Clear Cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## Important Notes

1. **Merchant Credentials:**
   - Currently configured in `.env` file
   - Read via `config/services.php`
   - Can be overridden in admin panel via database (core_config table)

2. **Sandbox vs Production:**
   - Sandbox mode uses test gateway: `sandbox.payhere.lk`
   - Production mode uses live gateway: `www.payhere.lk`
   - Always test in sandbox first!

3. **Security:**
   - Merchant secret must be kept confidential
   - IPN webhook verifies all payments using MD5 signature
   - Never expose merchant secret in frontend code

4. **Order Status Flow:**
   - Payment initiated → Order created in "pending" status
   - Payment successful → IPN received → Invoice created → Status "processing"
   - Payment failed → Customer redirected to cancel page

## Troubleshooting

### Issue: Payment not completing

**Check:**
1. IPN webhook is accessible: `curl https://axita.test/api/payhere -X POST`
2. Merchant credentials are correct
3. Hash generation matches PayHere's expected format
4. Logs: `storage/logs/laravel.log` for IPN errors

### Issue: Hash mismatch

**Solution:**
- Ensure amount is formatted as `number_format($amount, 2, '.', '')`
- Merchant secret must be MD5 hashed twice
- All hash components must match exactly (order matters)

### Issue: Order not created after payment

**Check:**
1. IPN logs in `storage/logs/laravel.log`
2. Database `orders` and `invoices` tables
3. Cart ID extraction: `preg_replace('/\D+/', '', $orderId)`

## Production Deployment Checklist

Before going live:

- [ ] Update PAYHERE_MERCHANT_ID to production value
- [ ] Update PAYHERE_MERCHANT_SECRET to production value
- [ ] Set sandbox mode to "No" in admin panel
- [ ] Test payment flow with real card (small amount)
- [ ] Verify IPN webhook is accessible from PayHere servers
- [ ] Check SSL certificate is valid (PayHere requires HTTPS)
- [ ] Monitor logs for any errors
- [ ] Test refund process (if applicable)

## Support

For PayHere integration issues:
- PayHere Documentation: https://support.payhere.lk/
- PayHere API Docs: https://support.payhere.lk/api-&-mobile-sdk/
- PayHere Support: support@payhere.lk

For Bagisto integration issues:
- Check `storage/logs/laravel.log`
- Run test script: `php test_payhere_config.php`
- Verify database configuration: `SELECT * FROM core_config WHERE code LIKE '%paypal_standard%';`
