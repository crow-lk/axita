# PayZY Support Request - Error Investigation

## Issue: Payment Gateway Returns Error Page

### Problem Description
When initiating a payment, PayZY's API always returns the same error URL instead of a unique payment link:

**Response from PayZY:**
```json
{
  "status": 201,
  "url": "https://app.payzypay.xyz/fromwordpress/e1"
}
```

This URL shows empty payment data:
- shopName: ''
- storeId: ''
- billAmount: 0
- Empty installment plans

### Our Integration Details

**Shop ID:** 2
**Test Mode:** ON
**Platform:** custom
**API Version:** 1.0

### Request Data Being Sent

```json
{
  "x_test_mode": "on",
  "x_shopid": "2",
  "x_amount": "13000.00",
  "x_order_id": "ORDER-125-1761416349",
  "x_response_url": "https://e213c772a853.ngrok-free.app/payzy/success",
  "x_first_name": "Kavindu",
  "x_last_name": "Lakshitha",
  "x_company": "",
  "x_address": "45 Andersons Creek Road, Doncaster VIC 3108",
  "x_country": "LK",
  "x_state": "",
  "x_city": "Doncaster",
  "x_zip": "3108",
  "x_phone": "0450547700",
  "x_email": "kavindu.lakshitha.ofc@gmail.com",
  "x_ship_to_first_name": "Kavindu",
  "x_ship_to_last_name": "Lakshitha",
  "x_ship_to_company": "",
  "x_ship_to_address": "45 Andersons Creek Road, Doncaster VIC 3108",
  "x_ship_to_country": "LK",
  "x_ship_to_state": "",
  "x_ship_to_city": "Doncaster",
  "x_ship_to_zip": "3108",
  "x_freight": "500.00",
  "x_platform": "custom",
  "x_version": "1.0",
  "signed_field_names": "x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names",
  "signature": "M0nXoy2PSuPEsLGGzqqlOupKV53Bg/zsWUVuf6T5hHc="
}
```

### API Endpoint
```
POST https://api.payzypay.xyz/checkout/custom-checkout
```

### Signature Generation
We're using HMAC SHA256 with Base64 encoding as per your documentation:
- Secret Key Length: 60 characters
- Signature: `M0nXoy2PSuPEsLGGzqqlOupKV53Bg/zsWUVuf6T5hHc=`

### Data String for Signature
```
x_test_mode=on,x_shopid=2,x_amount=13000.00,x_order_id=ORDER-125-1761416349,x_response_url=https://e213c772a853.ngrok-free.app/payzy/success,x_first_name=Kavindu,x_last_name=Lakshitha,x_company=,x_address=45 Andersons Creek Road, Doncaster VIC 3108,x_country=LK,x_state=,x_city=Doncaster,x_zip=3108,x_phone=0450547700,x_email=kavindu.lakshitha.ofc@gmail.com,x_ship_to_first_name=Kavindu,x_ship_to_last_name=Lakshitha,x_ship_to_company=,x_ship_to_address=45 Andersons Creek Road, Doncaster VIC 3108,x_ship_to_country=LK,x_ship_to_state=,x_ship_to_city=Doncaster,x_ship_to_zip=3108,x_freight=500.00,x_platform=custom,x_version=1.0,signed_field_names=x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names
```

### Console Errors on PayZY Page
When the error page loads, we see:
- CORS errors from `analytics.payz y.lk`
- 500 Internal Server Error on `/users/login`
- Empty data: `shopName: '', storeId: '', billAmount: 0`

### Questions for PayZY Support

1. **Is Shop ID "2" valid and active for test mode?**
2. **Is our secret key correct and not expired?**
3. **Are there any additional configuration steps needed in the PayZY admin panel?**
4. **Is there an IP whitelist or domain restriction we need to configure?**
5. **Why is the API returning `/fromwordpress/e1` instead of a unique payment URL?**
6. **Are there any logs on your end showing why our requests are being rejected?**
7. **Is the signature format correct?** (We're using HMAC SHA256 + Base64)
8. **Is ngrok URL acceptable for test mode?** (Using: https://e213c772a853.ngrok-free.app)

### Expected Behavior
According to your documentation, we should receive:
```json
{
  "url": "https://app.payzypay.xyz/payment/UNIQUE_ID"
}
```

### Actual Behavior
We always receive:
```json
{
  "url": "https://app.payzypay.xyz/fromwordpress/e1"
}
```

Which shows as an error page with no order data.

### Our Environment
- Platform: Laravel 10 (Bagisto eCommerce)
- PHP Version: 8.2
- Integration: Custom API (following your documentation)
- Test Environment: Local development with ngrok tunnel

### Request
Please investigate why our Shop ID 2 requests are being rejected and returning the error page. We've verified our signature generation matches your sample code exactly.

---

**Contact Information:**
- Shop ID: 2
- Email: [Your Email]
- Phone: [Your Phone]
- Company: [Your Company]

**Timestamp of Last Test:**
2025-10-25 23:49:09 (Order ID: ORDER-125-1761416349)
