# PayZY Integration Fix - October 25, 2025

## Issue: "Invalid temp orderId" Error

### Root Cause Analysis

The error "invalid temp orderId" from PayZY typically occurs due to:

1. **Incorrect signature generation** - Mismatch between your signature and PayZY's expected format
2. **Invalid order ID format** - PayZY may have specific requirements for order IDs
3. **Missing or incorrect fields** - All required fields must be present and properly formatted
4. **URL encoding issues** - The response callback URL might not be properly accessible

### Changes Made

#### 1. Fixed Order ID Format
**Changed from:** `ORD-{cart_id}-{timestamp}`
**Changed to:** `ORDER-{cart_id}-{timestamp}`

Location: `packages/Webkul/Payment/src/Http/Controllers/PayzyController.php` line ~67

#### 2. Fixed x_freight Field
**Changed from:** `'x_freight' => 'x_freight'` (literal string)
**Changed to:** `'x_freight' => number_format($cart->selected_shipping_rate->price ?? 0, 2, '.', '')`

This now sends the actual freight/shipping cost or '0.00' if not available.

Location: `packages/Webkul/Payment/src/Http/Controllers/PayzyController.php` line ~98

#### 3. Verified Signature Generation
The signature generation matches the PayZY sample code exactly. Test script confirms it's working correctly.

### Testing Checklist

- [x] Signature generation matches PayZY sample
- [x] Configuration is set (Shop ID: 2, Test Mode: on, Secret Key: configured)
- [x] Order ID format updated
- [x] x_freight field fixed
- [ ] Test actual payment flow
- [ ] Verify ngrok URL is correct in .env

### Current Configuration

```
APP_URL=https://e213c772a853.ngrok-free.app
PayZY Shop ID: 2
PayZY Test Mode: on
PayZY Secret Key: 60 characters (configured)
```

### Next Steps for Testing

1. **Verify ngrok is running:**
   ```bash
   # In your ngrok terminal, ensure it's active and showing your domain
   # It should match APP_URL in .env
   ```

2. **Check PayZY Admin Portal:**
   - Login to PayZY admin portal
   - Verify Shop ID = 2 is correct
   - Verify the secret key matches what's in your database
   - Check if there are any domain/URL restrictions

3. **Test the payment flow:**
   - Add items to cart
   - Go to checkout
   - Fill in billing/shipping information
   - Select PayZY as payment method
   - Click "Place Order"
   - Monitor Laravel logs for any errors

4. **Monitor Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

   Look for these log entries:
   - "Payzy Payment Request" - Shows what data is being sent
   - "Payzy API Response" - Shows PayZY's response
   - "Payzy Signature Generation" - Shows the signature details

### Debugging Commands

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Test signature generation
php test_payzy_signature.php

# Check configuration
php artisan tinker --execute="echo 'Shop ID: ' . core()->getConfigData('sales.payment_methods.payzy.shop_id') . PHP_EOL;"
```

### Common Issues and Solutions

#### Issue: "Invalid temp orderId"
**Possible causes:**
1. Order ID contains invalid characters (should be alphanumeric with dashes/underscores)
2. Order ID is too long (try shorter format)
3. Signature doesn't match (verify secret key)

**Solution:**
- Verify the order ID format: `ORDER-{number}-{timestamp}`
- Check Laravel logs to see what order ID was sent
- Confirm signature is being generated correctly

#### Issue: PayZY redirects back immediately with error
**Possible causes:**
1. Signature mismatch
2. Missing required fields
3. Invalid field values (e.g., amount must be numeric)

**Solution:**
- Check Laravel logs for the full request data
- Verify all fields are populated (not empty strings)
- Ensure amount is formatted as "10.00" not "10"

#### Issue: Can't access response URL
**Possible causes:**
1. ngrok not running
2. APP_URL in .env doesn't match ngrok URL
3. PayZY can't reach your server

**Solution:**
- Ensure ngrok is running: `ngrok http 80` or appropriate port
- Update APP_URL in .env to match ngrok URL
- Clear config cache after changing .env
- Test the response URL manually: visit `{APP_URL}/payzy/success` in browser

### API Request Format

Based on PayZY documentation, the request should look like this:

```json
{
  "x_test_mode": "on",
  "x_shopid": "2",
  "x_amount": "10.00",
  "x_order_id": "ORDER-123-1729854321",
  "x_response_url": "https://yourdomain.com/payzy/success",
  "x_first_name": "John",
  "x_last_name": "Doe",
  "x_company": "Company Name",
  "x_address": "123 Main St",
  "x_country": "Sri Lanka",
  "x_state": "Western",
  "x_city": "Colombo",
  "x_zip": "12345",
  "x_phone": "1234567890",
  "x_email": "customer@example.com",
  "x_ship_to_first_name": "John",
  "x_ship_to_last_name": "Doe",
  "x_ship_to_company": "Company Name",
  "x_ship_to_address": "123 Main St",
  "x_ship_to_country": "Sri Lanka",
  "x_ship_to_state": "Western",
  "x_ship_to_city": "Colombo",
  "x_ship_to_zip": "12345",
  "x_freight": "0.00",
  "x_platform": "custom",
  "x_version": "1.0",
  "signed_field_names": "x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names",
  "signature": "base64_encoded_hmac_sha256_signature"
}
```

### Contact PayZY Support

If the issue persists, contact PayZY support with:
1. Your Shop ID: 2
2. The exact order ID that failed
3. The signature you generated
4. The error message received
5. Screenshots of the error

### Files Modified

1. `packages/Webkul/Payment/src/Http/Controllers/PayzyController.php`
   - Line ~67: Order ID format
   - Line ~98: x_freight field
   - Signature generation (already correct)

### Rollback Instructions

If you need to rollback changes:
```bash
git diff packages/Webkul/Payment/src/Http/Controllers/PayzyController.php
git checkout packages/Webkul/Payment/src/Http/Controllers/PayzyController.php
```
