# PayZY Integration Testing Guide

## ✅ Pre-Test Verification Completed

Based on the credential verification, your PayZY integration is properly configured:

### Configuration Status:
- ✅ **Shop ID:** 2
- ✅ **Secret Key:** Configured (60 characters)
- ✅ **Test Mode:** ENABLED (Sandbox ON)
- ✅ **Payment Method:** ACTIVE
- ✅ **API Endpoint:** https://api.payzypay.xyz/checkout/custom-checkout
- ✅ **ngrok Tunnel:** Running (https://e213c772a853.ngrok-free.app)
- ✅ **Laravel URL Generation:** Using ngrok URL
- ✅ **Response URL:** https://e213c772a853.ngrok-free.app/payzy/success

## 📋 Testing Checklist

### 1. Start Monitoring (In Terminal 1)
```bash
cd /Users/kaviya/Documents/crowlk/axita
./watch_payzy_requests.sh
```

### 2. Access Site via ngrok URL
**IMPORTANT:** You MUST use the ngrok URL, not axita.test

✅ **Correct:** https://e213c772a853.ngrok-free.app
❌ **Wrong:** https://axita.test

### 3. Test Flow
1. Add product(s) to cart
2. Go to checkout
3. Fill in billing/shipping details
4. Select **"Payzy Payment Gateway"**
5. Click **"Place Order"**

### 4. What to Check in Logs

The monitoring script will show:

#### ✅ Expected Log Sequence:
```
=== PAYZY: Process Method Called ===
PAYZY: Cart Retrieved
🔐 PAYZY: Credentials Loaded from Database
   - shop_id: 2
   - secret_key_length: 60
   - secret_key_preview: $2b$12$82C...klTw.EGvWy
   - test_mode: on
PAYZY: Address Information
PAYZY: Order ID Generated
PAYZY: Response URL Generated
PAYZY: Data String for Signature
PAYZY: Signature Generated
PAYZY: Complete Payment Request
PAYZY: Full Request Payload (JSON with all fields)
PAYZY: Sending API Request
=== PAYZY: API Response Received ===
PAYZY: Response Data Parsed
=== PAYZY: Redirecting to Payment URL ===
```

### 5. What Data is Being Sent

The logs will show the EXACT data being sent to PayZY:

```json
{
  "x_test_mode": "on",
  "x_shopid": "2",
  "x_amount": "CART_TOTAL",
  "x_order_id": "ORDER-XXX-TIMESTAMP",
  "x_response_url": "https://e213c772a853.ngrok-free.app/payzy/success",
  "x_first_name": "CUSTOMER_NAME",
  "x_last_name": "CUSTOMER_LASTNAME",
  "x_email": "CUSTOMER_EMAIL",
  "x_phone": "CUSTOMER_PHONE",
  "x_address": "BILLING_ADDRESS",
  "x_country": "LK",
  "x_city": "CITY",
  "x_zip": "ZIP",
  "x_ship_to_first_name": "SHIPPING_NAME",
  "x_ship_to_last_name": "SHIPPING_LASTNAME",
  "x_ship_to_address": "SHIPPING_ADDRESS",
  "x_ship_to_country": "LK",
  "x_ship_to_city": "SHIPPING_CITY",
  "x_ship_to_zip": "SHIPPING_ZIP",
  "x_freight": "SHIPPING_COST",
  "x_platform": "custom",
  "x_version": "1.0",
  "signed_field_names": "ALL_FIELD_NAMES",
  "signature": "BASE64_SIGNATURE"
}
```

### 6. Expected PayZY Responses

#### ✅ Success Response:
```json
{
  "url": "https://app.payzypay.xyz/payment/UNIQUE_PAYMENT_ID"
}
```
You'll be redirected to PayZY's payment page with order details.

#### ❌ Error Response (Current Issue):
```json
{
  "url": "https://app.payzypay.xyz/fromwordpress/e1"
}
```
This shows empty payment data - indicates credential or configuration issue on PayZY's side.

## 🐛 Troubleshooting

### Issue: Still getting "/fromwordpress/e1" error

**Possible Causes:**

1. **Invalid Shop ID**
   - Shop ID "2" might not be your actual shop ID
   - Check PayZY admin dashboard for correct Shop ID

2. **Wrong Secret Key**
   - The secret key might be incorrect or expired
   - Verify in PayZY dashboard: Settings → API Credentials

3. **Test Mode Not Enabled on PayZY Side**
   - Your PayZY account might not have test mode enabled
   - Contact PayZY support to enable sandbox/test mode

4. **Account Not Fully Activated**
   - PayZY account might need additional verification
   - Check account status in PayZY dashboard

5. **Domain/IP Restrictions**
   - PayZY might require domain whitelisting
   - Add your ngrok URL to allowed domains in PayZY dashboard

### What to Send to PayZY Support

If the error persists, send them:

1. **Shop ID:** 2
2. **Test Mode:** ON
3. **Timestamp of test:** (from logs)
4. **Request signature:** (from logs)
5. **Response received:** "/fromwordpress/e1"
6. **Screenshot of the error page**

Use the prepared support request:
```bash
cat /Users/kaviya/Documents/crowlk/axita/PAYZY_SUPPORT_REQUEST.md
```

## 📊 Quick Commands

### Verify Setup Before Testing:
```bash
./verify_payzy_credentials.sh
./verify_ngrok_setup.sh
```

### Start Monitoring:
```bash
./watch_payzy_requests.sh
```

### Check Recent Logs:
```bash
tail -100 storage/logs/laravel.log | grep PAYZY
```

### Clear Caches (if needed):
```bash
php artisan config:clear && php artisan cache:clear && php artisan view:clear
```

## 🎯 Next Steps

1. **Start monitoring:** `./watch_payzy_requests.sh`
2. **Open browser:** https://e213c772a853.ngrok-free.app
3. **Test payment flow**
4. **Share the logs** with me so I can see exactly what's happening

The integration code is correct. If PayZY still returns the error page, it's an issue with:
- PayZY account configuration
- Invalid credentials
- Account restrictions

You may need to contact PayZY support for verification.
