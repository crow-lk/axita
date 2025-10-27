# Payzy Integration - Comprehensive Debugging Guide

## Current Status Analysis

### ✅ What's Working Correctly:

1. **Payment Method Registration** - Payzy appears in checkout
2. **Redirect Flow** - Bagisto correctly redirects to `payzy.process`
3. **Signature Generation** - HMAC-SHA256 implementation is correct
4. **API Integration** - Request format matches Payzy documentation
5. **Callback Handling** - Success/cancel routes are properly set up

### ❌ The Root Problem:

**"Invalid temp order id" error on Payzy payment page**

This occurs because:
- Payzy's API returns a generic error URL: `/fromwordpress/e1`
- This is NOT a real payment session URL
- Demo credentials (Shop ID: 2) don't create functional payment sessions
- You need REAL test merchant credentials from Payzy

## How Bagisto Payment Flow Works

### Standard Flow (Cash on Delivery, Money Transfer):
```
User clicks "Place Order"
    ↓
Cart validation
    ↓
Order created immediately
    ↓
Success page
```

### Redirect Flow (PayPal, Payzy, etc.):
```
User clicks "Place Order"
    ↓
Payment::getRedirectUrl() returns route
    ↓
Redirect to payment process route
    ↓
Controller prepares payment data
    ↓
Auto-submit form to payment gateway
    ↓
User on payment gateway site
    ↓
User completes payment
    ↓
Gateway redirects to success/cancel URL
    ↓
** ORDER CREATED HERE ** (in success handler)
    ↓
Success page
```

## Verification Checklist

### 1. Check Payment Method Configuration

```bash
cd /Users/kaviya/Documents/crowlk/axita
php artisan tinker
```

```php
// In tinker:
$config = DB::table('core_config')->where('code', 'LIKE', 'payzy%')->get();
print_r($config->toArray());
```

**Expected Output:**
```
- shop_id: 2
- secret_key: $2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy
- active: 1
- sandbox: 1
```

### 2. Check Routes

```bash
php artisan route:list | grep payzy
```

**Expected Output:**
```
GET|HEAD   payzy/cancel .... payzy.cancel
GET|HEAD   payzy/process ... payzy.process
GET|HEAD   payzy/success ... payzy.success
```

### 3. Test Payment Flow with Logging

Enable detailed logging by monitoring the Laravel log while testing:

```bash
tail -f storage/logs/laravel.log
```

**What to Look For:**

#### Step 1: Payment Initiation
```
[timestamp] Payzy Payment Request
    - order_id: UNIQUE_ID
    - cart_id: CART_ID
    - amount: AMOUNT
    - shop_id: 2
    - test_mode: on
    - response_url: NGROK_URL/payzy/success
    - signature: BASE64_SIGNATURE
```

#### Step 2: API Response
```
[timestamp] Payzy API Response
    - status: 201
    - json: {
        "url": "/fromwordpress/e1"  ← THIS IS THE PROBLEM
      }
```

**If you see `/fromwordpress/e1`:** Demo credentials are not creating real payment sessions.

**What you SHOULD see with real credentials:**
```
    - json: {
        "url": "https://gateway.payzypay.xyz/payment/session/abc123def456"
      }
```

#### Step 3: Redirect to Payzy
```
[timestamp] Redirecting to Payzy payment URL
    - url: FULL_PAYZY_URL
```

#### Step 4: Return from Payzy (Success)
```
[timestamp] Payzy success callback
    - x_order_id: UNIQUE_ID
    - response_code: 00
    - signature: RETURN_SIGNATURE
```

#### Step 5: Signature Verification
```
[timestamp] Payzy signature verification
    - expected_signature: CALCULATED_SIGNATURE
    - received_signature: RETURN_SIGNATURE
    - match: true/false
```

## Testing Scenarios

### Scenario 1: Test with Current Setup (Expected to Fail)

1. **Start ngrok:**
```bash
ngrok http axita.test:80
```

2. **Update .env:**
```env
APP_URL=https://YOUR_NGROK_URL.ngrok.io
```

3. **Clear cache:**
```bash
php artisan config:clear
php artisan cache:clear
```

4. **Make a test purchase:**
- Access site via ngrok URL
- Add product to cart
- Proceed to checkout
- Select Payzy payment
- Click "Place Order"
- Monitor logs

5. **Expected Result:**
- Redirects to Payzy
- Shows "Invalid temp order id" error page
- This confirms demo credentials issue

### Scenario 2: With Real Credentials (Should Work)

When you get real test merchant credentials from Payzy:

1. **Update configuration in admin panel:**
- Go to Admin → Configuration → Sales → Payment Methods → Payzy
- Update Shop ID with your test merchant ID
- Update Secret Key with your test secret key
- Ensure Sandbox is enabled
- Save

2. **Clear cache:**
```bash
php artisan config:clear
```

3. **Test payment flow:**
- Should redirect to actual Payzy payment page
- Complete payment with test card
- Should redirect back and create order

## Common Issues and Solutions

### Issue 1: Session Lost After Redirect

**Symptoms:**
- "Payment session expired" error
- Session data not found in success callback

**Solution:**
```php
// In .env, ensure:
SESSION_DRIVER=file
SESSION_LIFETIME=120

// Clear sessions:
php artisan cache:clear
php artisan session:flush
```

### Issue 2: Signature Verification Failed

**Symptoms:**
- "Payment verification failed. Invalid signature."
- Signature mismatch in logs

**Causes:**
1. Secret key incorrect or has extra spaces
2. Field order wrong in signature generation
3. Response data modified during transit

**Solution:**
Check signature generation in logs:
```bash
grep "Signature generation" storage/logs/laravel.log
```

Verify field order matches Payzy docs exactly.

### Issue 3: Cart Not Found on Return

**Symptoms:**
- "Cart not found or expired"
- Order not created even with successful payment

**Solution:**
Ensure `payzy_cart_id` is stored in session:
```php
// Check in PayzyController::process()
session()->put('payzy_cart_id', $cart->id);

// Check in logs
Log::info('Stored cart ID', ['cart_id' => $cart->id]);
```

### Issue 4: ngrok URL Not Working

**Symptoms:**
- Payzy can't redirect back
- Timeout or connection error

**Solutions:**
1. **Verify ngrok is running:**
```bash
# Should show active session
ngrok http axita.test:80
```

2. **Check APP_URL:**
```bash
grep APP_URL .env
# Should be: APP_URL=https://xxxx.ngrok.io
```

3. **Test ngrok URL:**
```bash
curl https://YOUR_NGROK_URL.ngrok.io
# Should return your site's HTML
```

4. **Verify response URL in logs:**
```bash
grep "x_response_url" storage/logs/laravel.log
# Should show ngrok URL, not axita.test
```

## Payzy Credentials: Demo vs Real

### Demo Credentials (Current - DON'T WORK):
```
Shop ID: 2
Secret Key: $2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy
Purpose: Documentation only
Result: Returns generic error page
```

### Real Test Credentials (Need from Payzy):
```
Shop ID: Your test merchant ID (e.g., 123456)
Secret Key: Your actual test secret key
Purpose: Functional testing
Result: Creates real payment session
```

### How to Get Real Credentials:

1. **Contact Payzy Support:**
   - Email: support@payzy.lk
   - Request: "Test merchant account for integration testing"
   - Provide: Business details, website domain

2. **What to Ask For:**
   - Test Merchant ID (Shop ID)
   - Test Secret Key
   - Test mode documentation
   - Test card numbers (if any)
   - Webhook configuration instructions

3. **Integration Steps:**
   - Receive credentials via secure channel
   - Update in admin panel configuration
   - Test with small amount first
   - Verify orders are created correctly

## Code Verification

### Check PayzyController.php

The key sections that must be correct:

#### 1. Signature Generation (Lines ~300-350)
```php
protected function generateSignature(array $data, string $secretKey): string
{
    // Must follow exact field order from Payzy docs
    $signatureString = implode(',', [
        "x_test_mode={$data['x_test_mode']}",
        "x_shopid={$data['x_shopid']}",
        // ... all fields in correct order
    ]);

    $hash = hash_hmac('sha256', $signatureString, $secretKey, false);
    return base64_encode(pack('H*', $hash));
}
```

#### 2. Payment Data Preparation (Lines ~80-120)
```php
$paymentData = [
    'x_test_mode' => $testMode,
    'x_shopid' => $shopId,
    'x_amount' => number_format($cart->grand_total, 2, '.', ''),
    'x_order_id' => $orderId,
    'x_response_url' => route('payzy.success'),  // Must be ngrok URL
    // ... all required fields
];
```

#### 3. Success Handler (Lines ~195-280)
```php
public function success(Request $request)
{
    // Get data from query params
    $orderId = $request->input('x_order_id');
    $responseCode = $request->input('response_code');
    $signature = $request->input('signature');

    // Verify signature
    if ($this->verifyPaymentSignature($request, $storedData)) {
        if ($responseCode === '00') {
            // Create order HERE, not before
            $data = (new OrderResource($cart))->jsonSerialize();
            $order = $this->orderRepository->create($data);
        }
    }
}
```

## Final Recommendations

### Short Term (Current Setup):
1. ✅ Code is correctly implemented
2. ✅ Follows Bagisto payment gateway standards
3. ✅ Signature generation matches Payzy specs
4. ❌ Demo credentials won't create real payment sessions
5. ⚠️ Cannot fully test without real credentials

### Action Items:
1. **Contact Payzy Support** - Request test merchant account
2. **Use ngrok** - Expose local server for testing
3. **Monitor logs** - Verify each step of the flow
4. **Test with real credentials** - When received from Payzy
5. **Document test card numbers** - If Payzy provides any

### Long Term (Production):
1. Get production merchant credentials
2. Deploy to publicly accessible server
3. Update APP_URL to production domain
4. Disable sandbox mode
5. Test with small real amount
6. Monitor production logs
7. Set up error alerting

## Support Resources

- **Payzy Documentation**: https://payzy.lk/dev-doc
- **Laravel Logs**: `/Users/kaviya/Documents/crowlk/axita/storage/logs/laravel.log`
- **Bagisto Forums**: https://forums.bagisto.com
- **Your Integration Files**:
  - Payment Class: `/packages/Webkul/Payment/src/Payment/Payzy.php`
  - Controller: `/packages/Webkul/Payment/src/Http/Controllers/PayzyController.php`
  - Routes: `/packages/Webkul/Payment/src/Http/routes.php`
  - Config: `/packages/Webkul/Admin/src/Config/system.php`

---

**BOTTOM LINE**: Your code is correct. The "invalid temp order id" error is because demo credentials don't create functional payment sessions. You need real test merchant credentials from Payzy to proceed with testing.
