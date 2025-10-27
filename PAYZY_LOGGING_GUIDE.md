# PayZY Integration - Complete Logging Guide

## 🔍 Comprehensive Logging Added

I've added detailed logging to trace the entire PayZY payment flow from frontend to backend.

---

## Frontend Logging (Browser Console)

Open your browser's **Developer Tools** (F12) and watch the **Console** tab during checkout.

### What You'll See:

```
=== PAYZY CHECKOUT: Place Order Started ===
Current Step: payment
Selected Payment Method: payzy
Cart Data: {id: 125, grand_total: 13000, ...}
API Endpoint: https://e213c772a853.ngrok-free.app/checkout/onepage/orders

=== PAYZY CHECKOUT: Order Response Received ===
Response Status: 200
Response Data: {redirect: true, redirect_url: "https://..."}

=== PAYZY CHECKOUT: Redirect Required ===
Redirect URL: https://api.payzypay.xyz/...
Payment Method: payzy
Redirecting to PayZY payment gateway...
```

### Frontend Log Points:

1. **Place Order Started** - When user clicks "Place Order"
2. **Order Response** - Response from Laravel backend
3. **Redirect Decision** - Whether redirect is needed
4. **Error Handling** - Any errors during checkout

---

## Backend Logging (Laravel Logs)

### Monitor Logs in Real-Time

Open a terminal and run:

```bash
cd /Users/kaviya/Documents/crowlk/axita
tail -f storage/logs/laravel.log | grep -i "PAYZY:"
```

Or see ALL details:
```bash
tail -f storage/logs/laravel.log
```

---

## Backend Log Flow

### 1. Process Method Called
```
=== PAYZY: Process Method Called ===
PAYZY: Cart Retrieved
  - cart_id: 125
  - grand_total: 13000.00
  - customer_email: customer@example.com

PAYZY: Configuration Retrieved
  - shop_id: 2
  - test_mode: on
  - secret_key_length: 60
```

### 2. Address & Order ID
```
PAYZY: Address Information
  - billing_address: {...}
  - shipping_address: {...}

PAYZY: Order ID Generated
  - order_id: ORDER-125-1761415234
  - cart_id: 125
```

### 3. Signature Generation
```
PAYZY: Signature Generation Started
PAYZY: Signed Fields (count: 28)
PAYZY: Adding Field to Signature String (for each field)
PAYZY: Data String for Signature
  - data_string: x_test_mode=on,x_shopid=2,...
  - data_string_length: 1234

=== PAYZY: Signature Generated Successfully ===
  - signature: abc123xyz...
  - signature_length: 44
```

### 4. API Request
```
=== PAYZY: Complete Payment Request ===
  - order_id: ORDER-125-1761415234
  - amount: 13000.00
  - response_url: https://e213c772a853.ngrok-free.app/payzy/success
  - signature: abc123...

PAYZY: Sending API Request
  - url: https://api.payzypay.xyz/checkout/custom-checkout
  - method: POST

=== PAYZY: API Response Received ===
  - status: 201
  - successful: true
  - body: {"url":"https://..."}

=== PAYZY: Redirecting to Payment URL ===
  - url: https://app.payzypay.xyz/payment/xyz123
  - url_domain: app.payzypay.xyz
```

### 5. Success Callback (When customer returns)
```
=== PAYZY: Success Callback Received ===
  - timestamp: 2025-10-25 23:45:12
  - url: https://e213c772a853.ngrok-free.app/payzy/success?...
  - x_order_id: ORDER-125-1761415234
  - response_code: 00

PAYZY: Session Data Retrieved
  - has_stored_data: true
  - cart_id_from_session: 125

=== PAYZY: Signature Verification Process ===
  - received_signature: xyz789...
  - response_code: 00

PAYZY: Signature Verification PASSED ✓
PAYZY: Payment Successful (response_code = 00)

=== PAYZY: Order Created Successfully ===
  - order_id: 17
  - increment_id: 000017
  - payzy_order_id: ORDER-125-1761415234
  - grand_total: 13000.00

=== PAYZY: Redirecting to Success Page ===
```

---

## Log Levels Explained

### `INFO` - Normal flow
Regular operation steps and confirmations

### `WARNING` - Potential issues
Non-critical issues like payment declined (response_code != 00)

### `ERROR` - Critical issues
- Configuration missing
- Cart not found
- Signature verification failed
- API errors

### `DEBUG` - Detailed data
Field-by-field signature generation (may be verbose)

---

## Common Scenarios & Expected Logs

### ✅ Successful Payment Flow

**Frontend Console:**
```
Place Order Started → Order Response → Redirect Required → Redirecting...
```

**Backend Logs:**
```
Process Called → Cart Retrieved → Config OK → Signature Generated 
→ API Request Sent → API Response OK → Redirecting
→ (Customer pays on PayZY site)
→ Success Callback → Signature Verified ✓ → Order Created → Success
```

### ❌ Configuration Error

**Backend Logs:**
```
PAYZY: Process Method Called
PAYZY: Configuration Retrieved
  - shop_id: NOT SET (or incorrect)
  - secret_key: NOT SET

PAYZY: Configuration missing
  ERROR: Payzy payment gateway is not properly configured
```

### ❌ Cart Not Found

**Backend Logs:**
```
PAYZY: Process Method Called
ERROR: Cart not found in session
```

### ❌ Signature Verification Failed

**Backend Logs:**
```
PAYZY: Success Callback Received
PAYZY: Signature Verification Process
=== PAYZY: Signature Comparison ===
  - received_signature: abc...
  - calculated_signature: xyz...
  - match: NO ✗

ERROR: Signature Verification FAILED
```

### ⚠️ Payment Declined

**Backend Logs:**
```
PAYZY: Success Callback Received
PAYZY: Signature Verification PASSED ✓
WARNING: Payment Failed or Declined
  - response_code: 01 (or other non-00 code)
```

---

## Testing Workflow

### Step 1: Open Logs Monitor
```bash
cd /Users/kaviya/Documents/crowlk/axita
tail -f storage/logs/laravel.log | grep -i "PAYZY:"
```

### Step 2: Open Browser Console
1. Go to your store: https://e213c772a853.ngrok-free.app
2. Press F12 (Developer Tools)
3. Go to Console tab
4. Add item to cart and proceed to checkout

### Step 3: Watch Both Logs

**In Browser Console:** See frontend flow
**In Terminal:** See backend processing

### Step 4: Complete Payment

Watch the logs as you:
1. Click "Place Order"
2. Get redirected to PayZY
3. Complete payment
4. Get redirected back
5. See order success page

---

## Log File Location

```
/Users/kaviya/Documents/crowlk/axita/storage/logs/laravel.log
```

### View Recent Logs
```bash
# Last 100 lines
tail -100 storage/logs/laravel.log

# Last 100 PayZY-related lines
tail -1000 storage/logs/laravel.log | grep -i "PAYZY:"

# Search for specific order ID
grep "ORDER-125-" storage/logs/laravel.log

# View logs from specific time
grep "2025-10-25 23:" storage/logs/laravel.log | grep PAYZY
```

---

## Debugging Tips

### Issue: No logs appearing

**Check:**
```bash
# Ensure log file exists and is writable
ls -la storage/logs/laravel.log

# Check PHP error log too
tail -f storage/logs/laravel.log storage/logs/php_error.log
```

### Issue: Too many logs

**Filter by severity:**
```bash
# Only errors
tail -f storage/logs/laravel.log | grep "ERROR"

# Only PayZY errors
tail -f storage/logs/laravel.log | grep -i "PAYZY:" | grep "ERROR"
```

### Issue: Want to trace specific transaction

**Use order ID:**
```bash
# Watch for specific order
tail -f storage/logs/laravel.log | grep "ORDER-125-1761415234"

# Or cart ID
tail -f storage/logs/laravel.log | grep "cart_id\":125"
```

---

## Log Analysis

### Check if Request Reached PayZY
Look for:
```
PAYZY: Sending API Request
PAYZY: API Response Received
```

If missing → Request never sent (check earlier errors)

### Check Response URL
Look for:
```
response_url: https://e213c772a853.ngrok-free.app/payzy/success
```

Should be **ngrok URL**, not `axita.test`

### Check Signature Generation
Look for:
```
PAYZY: Data String for Signature
  data_string: x_test_mode=on,x_shopid=2,x_amount=...
```

Should contain all fields in correct order

### Check PayZY Response
Look for:
```
PAYZY: API Response Received
  status: 201
  body: {"url":"https://..."}
```

If status is 4xx or 5xx → PayZY rejected the request

---

## Quick Diagnostic Commands

```bash
# 1. Check latest PayZY transaction
tail -200 storage/logs/laravel.log | grep -A 5 "PAYZY: Process Method Called" | tail -20

# 2. Check for errors in last hour
grep "$(date '+%Y-%m-%d %H:')" storage/logs/laravel.log | grep -i "PAYZY:" | grep "ERROR"

# 3. Check signature generation
grep "Signature Generated Successfully" storage/logs/laravel.log | tail -5

# 4. Check API responses
grep "PAYZY: API Response Received" storage/logs/laravel.log | tail -5

# 5. Count successful vs failed transactions today
echo "Successful: $(grep "$(date '+%Y-%m-%d')" storage/logs/laravel.log | grep -c 'Order Created Successfully')"
echo "Failed: $(grep "$(date '+%Y-%m-%d')" storage/logs/laravel.log | grep -c 'PAYZY:.*ERROR')"
```

---

## Production Notes

### Reduce Logging in Production

For production, you may want to reduce log verbosity:

1. Remove `DEBUG` level logs
2. Keep only `INFO`, `WARNING`, `ERROR`
3. Consider using a separate log file for PayZY

### Log Rotation

Ensure Laravel's log rotation is configured:

```env
# .env
LOG_CHANNEL=daily
LOG_LEVEL=info
```

This will create daily log files and keep only last 7 days.

---

## Summary

✅ **Frontend logging** - Browser console shows checkout flow
✅ **Backend logging** - Laravel logs show complete transaction
✅ **Real-time monitoring** - Use `tail -f` to watch live
✅ **Detailed debugging** - Every step is logged with context
✅ **Error tracking** - All errors clearly marked and detailed

**Now you can trace every step of the PayZY integration!**

---

## Test Now

1. **Open terminal:** `tail -f storage/logs/laravel.log | grep -i "PAYZY:"`
2. **Open browser console:** F12 → Console tab
3. **Test checkout:** Add item → Checkout → Place Order
4. **Watch both logs:** See the flow in real-time

**Good luck! The logs will tell you exactly what's happening.** 🚀
