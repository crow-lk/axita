# Payzy Integration - Quick Reference

## Credentials (Testing)
```
Shop ID: 2
Secret Key: $2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy
Test Mode: ON
```

## Admin Configuration Path
```
Admin Panel → Configuration → Sales → Payment Methods → Payzy
```

## Implementation Summary

### ✅ What Was Implemented

1. **Payment Class** (`Payzy.php`)
   - Extends base Payment class
   - Handles payment method availability
   - Returns redirect URL to payment controller

2. **Payment Controller** (`PayzyController.php`)
   - **process()**: Sends payment request to Payzy API
   - **success()**: Handles return callback and verifies signature
   - **cancel()**: Handles payment cancellation
   - Uses HMAC-SHA256 signature generation (matches Payzy sample)

3. **Routes**
   - GET `/payzy/process` - Initiates payment
   - GET `/payzy/success` - Success callback
   - GET `/payzy/cancel` - Cancellation handler

4. **Configuration**
   - Added to `paymentmethods.php`
   - Admin panel fields in `system.php`
   - Language translations in `app.php`

### 🔐 Signature Generation (Based on Payzy Sample)

The implementation follows the exact signature method from `/Users/kaviya/Downloads/payzy/Front-End/script.js`:

```javascript
// JavaScript version (Payzy sample)
var list = 'x_test_mode=' + data.x_test_mode + 
           ',x_shopid=' + data.x_shopid + 
           ',x_amount=' + data.x_amount + 
           // ... all fields
           ',signed_field_names=' + "field1,field2,..."

var hash = CryptoJS.HmacSHA256(list, key);
var signature = CryptoJS.enc.Base64.stringify(hash);
```

```php
// PHP version (Our implementation)
protected function generateSignature(array $data, string $secretKey): string
{
    $signedFields = explode(',', $data['signed_field_names']);
    $dataString = '';
    
    foreach ($signedFields as $field) {
        $dataString .= $field . '=' . ($data[$field] ?? '') . ',';
    }
    
    $dataString = rtrim($dataString, ',');
    $hash = hash_hmac('sha256', $dataString, $secretKey, true);
    
    return base64_encode($hash);
}
```

### 📊 Payment Flow

```
Customer Checkout
    ↓
Select Payzy Payment
    ↓
POST to Payzy API (https://api.payzypay.xyz/checkout/custom-checkout)
    ↓
Redirect to Payzy Payment Page
    ↓
Customer Enters Payment Info
    ↓
Payzy Processes Payment
    ↓
Redirect to /payzy/success?x_order_id=...&response_code=00&signature=...
    ↓
Verify Signature
    ↓
Check response_code = '00'
    ↓
Create Order
    ↓
Show Success Page
```

### 🧪 Testing Steps

1. **Configure in Admin**
   ```
   Shop ID: 2
   Secret Key: $2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy
   Sandbox: ON
   Status: Active
   ```

2. **Clear Cache**
   ```bash
   php artisan config:clear
   php artisan route:clear
   ```

3. **Test Purchase**
   - Add products to cart
   - Proceed to checkout
   - Select "Payzy" payment method
   - Complete payment on Payzy page
   - Verify order creation

### 📝 Response Codes

| Code | Meaning |
|------|---------|
| 00 | Success - Payment approved |
| Other | Failed - Payment declined/error |

### 🔍 Debugging

**Enable Logs:**
```php
// Already implemented in PayzyController
Log::info('Payzy success callback', $request->all());
Log::error('Payzy payment error: ' . $e->getMessage());
```

**View Logs:**
```bash
tail -f storage/logs/laravel.log
```

**Check Routes:**
```bash
php artisan route:list --name=payzy
```

### 📂 Key Files

```
packages/Webkul/Payment/src/
├── Payment/
│   └── Payzy.php                    # Payment method class
├── Http/
│   ├── Controllers/
│   │   └── PayzyController.php      # Payment processing logic
│   └── routes.php                   # Route definitions
└── Config/
    └── paymentmethods.php           # Payment method config

packages/Webkul/Admin/src/
├── Config/
│   └── system.php                   # Admin configuration
└── Resources/
    └── lang/
        └── en/
            └── app.php              # English translations
```

### 🎯 API Reference

**Endpoint:**
```
POST https://api.payzypay.xyz/checkout/custom-checkout
```

**Required Fields:**
- x_test_mode (on/off)
- x_shopid (your shop ID)
- x_amount (payment amount)
- x_order_id (unique order identifier)
- x_response_url (callback URL)
- Customer billing information
- Customer shipping information
- x_platform (custom)
- x_version (1.0)
- signed_field_names (list of signed fields)
- signature (HMAC-SHA256 hash)

**Response:**
```json
{
  "url": "https://payzy.payment.page/..."
}
```

### ✨ Features Implemented

- ✅ Secure HMAC-SHA256 signature generation
- ✅ Sandbox/Production mode toggle
- ✅ Automatic invoice generation
- ✅ Configurable order status
- ✅ Complete billing/shipping address support
- ✅ Session-based payment verification
- ✅ Comprehensive error handling
- ✅ Detailed logging
- ✅ Admin configuration panel

### 🚀 Go Live Checklist

- [ ] Update Shop ID (if different for production)
- [ ] Update Secret Key (production key)
- [ ] Disable Sandbox Mode
- [ ] Test with small real transaction
- [ ] Monitor logs for first few transactions
- [ ] Verify order creation workflow
- [ ] Test refund process (if applicable)

---

**Reference Documentation**: `/Users/kaviya/Downloads/payzy/Front-End/`  
**Based on Sample**: `script.js` and `responce.js`
