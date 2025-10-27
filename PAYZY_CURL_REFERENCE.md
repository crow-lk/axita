# Payzy Credential Validation - Quick Reference

## 🚀 Quick Commands

### 1. PHP Validator (Recommended - Most Detailed)
```bash
php validate_payzy.php
```

**With your own credentials:**
```bash
php validate_payzy.php YOUR_SHOP_ID 'YOUR_SECRET_KEY'
```

### 2. Shell Script (Visual & User-Friendly)
```bash
./validate_payzy_credentials.sh
```

**With your own credentials:**
```bash
./validate_payzy_credentials.sh YOUR_SHOP_ID 'YOUR_SECRET_KEY'
```

### 3. Quick Test (Simple & Fast)
```bash
./quick_test_payzy.sh
```

## 📋 Manual cURL Command

If you want to run curl manually, here's the command:

### Step 1: Generate Signature
```bash
php -r '
$data_string = "x_test_mode=on,x_shopid=2,x_amount=100.00,x_order_id=TEST-123,x_response_url=http://localhost/payzy/success,x_first_name=John,x_last_name=Doe,x_company=Test Company,x_address=123 Test St,x_country=Sri Lanka,x_state=Western,x_city=Colombo,x_zip=10100,x_phone=1234567890,x_email=test@example.com,x_ship_to_first_name=John,x_ship_to_last_name=Doe,x_ship_to_company=Test Company,x_ship_to_address=123 Test St,x_ship_to_country=Sri Lanka,x_ship_to_state=Western,x_ship_to_city=Colombo,x_ship_to_zip=10100,x_freight=x_freight,x_platform=custom,x_version=1.0,signed_field_names=x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names";
$secret_key = "$2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy";
$hash = hash_hmac("sha256", $data_string, $secret_key, true);
echo base64_encode($hash);
'
```

### Step 2: Make API Request
```bash
curl -X POST https://api.payzypay.xyz/checkout/custom-checkout \
  -H "Content-Type: application/json" \
  -d '{
    "x_test_mode": "on",
    "x_shopid": "2",
    "x_amount": "100.00",
    "x_order_id": "TEST-123",
    "x_response_url": "http://localhost/payzy/success",
    "x_first_name": "John",
    "x_last_name": "Doe",
    "x_company": "Test Company",
    "x_address": "123 Test St",
    "x_country": "Sri Lanka",
    "x_state": "Western",
    "x_city": "Colombo",
    "x_zip": "10100",
    "x_phone": "1234567890",
    "x_email": "test@example.com",
    "x_ship_to_first_name": "John",
    "x_ship_to_last_name": "Doe",
    "x_ship_to_company": "Test Company",
    "x_ship_to_address": "123 Test St",
    "x_ship_to_country": "Sri Lanka",
    "x_ship_to_state": "Western",
    "x_ship_to_city": "Colombo",
    "x_ship_to_zip": "10100",
    "x_freight": "x_freight",
    "x_platform": "custom",
    "x_version": "1.0",
    "signed_field_names": "x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names",
    "signature": "PASTE_SIGNATURE_HERE"
  }' | jq .
```

## 🔍 Understanding Results

### ✅ Good Response (Credentials Working):
```json
{
  "url": "https://app.payzypay.xyz/payment/abc123def456"
}
```
- URL contains unique session identifier
- Different for each request
- **Credentials are valid and working!**

### ⚠️ Generic Error Response (Credentials Not Working):
```json
{
  "url": "https://app.payzypay.xyz/fromwordpress/e1"
}
```
- Same URL for all requests
- Generic error page
- Shows "invalid temp order id" when visited
- **Credentials are demo/test only - need real merchant account**

### ❌ Error Response:
```json
{
  "error": "Invalid signature"
}
```
Or HTTP 400/401/403
- **Credentials are incorrect**
- Check Shop ID and Secret Key

## 📝 Testing Your Own Credentials

### Replace in commands:
```bash
# Your Shop ID (number)
SHOP_ID="YOUR_SHOP_ID"

# Your Secret Key (long string)
SECRET_KEY='YOUR_SECRET_KEY'
```

### Example with custom credentials:
```bash
php validate_payzy.php 12345 '$2y$10$abcdefghijklmnopqrstuvwxyz123456789'
```

## 🎯 Quick Validation Workflow

1. **Get credentials from Payzy:**
   - Shop ID
   - Secret Key

2. **Run validator:**
   ```bash
   php validate_payzy.php YOUR_SHOP_ID 'YOUR_SECRET_KEY'
   ```

3. **Check result:**
   - ✅ Unique URL → Credentials work, update config
   - ⚠️ `/fromwordpress/e1` → Need real merchant account
   - ❌ Error → Check credentials

4. **Update Bagisto config:**
   ```bash
   # If credentials work:
   php artisan tinker --execute="
     DB::table('core_config')
       ->updateOrInsert(
         ['code' => 'sales.payment_methods.payzy.shop_id'],
         ['value' => 'YOUR_SHOP_ID']
       );
     DB::table('core_config')
       ->updateOrInsert(
         ['code' => 'sales.payment_methods.payzy.secret_key'],
         ['value' => 'YOUR_SECRET_KEY']
       );
   "
   php artisan config:clear
   ```

## 💡 Troubleshooting

### Problem: Command not found
```bash
chmod +x validate_payzy_credentials.sh
chmod +x quick_test_payzy.sh
```

### Problem: PHP not found
Use the full path:
```bash
/usr/bin/php validate_payzy.php
```

### Problem: curl not found
Install curl:
```bash
# macOS
brew install curl

# Ubuntu/Debian
sudo apt-get install curl
```

### Problem: jq not found (for pretty JSON)
Install jq:
```bash
# macOS
brew install jq

# Ubuntu/Debian
sudo apt-get install jq
```

Or use Python instead:
```bash
curl ... | python3 -m json.tool
```

## 🔗 Payzy Resources

- **Website:** https://payzy.lk
- **Merchant Portal:** https://merchant.payzy.lk/
- **Documentation:** https://payzy.lk/dev-doc
- **Support/FAQ:** https://payzy.lk/FAQ

## 📞 Getting Help

1. **For integration issues:**
   - Check logs: `tail -f storage/logs/laravel.log | grep Payzy`
   - Run comprehensive test: `php test_payzy_comprehensive.php`

2. **For credential issues:**
   - Run validator: `php validate_payzy.php`
   - Contact Payzy support with validator output

3. **For merchant account:**
   - Visit: https://merchant.payzy.lk/
   - Register and wait for approval
   - Get your Shop ID and Secret Key

---

**Created:** October 25, 2025  
**For:** Payzy Payment Gateway Integration
