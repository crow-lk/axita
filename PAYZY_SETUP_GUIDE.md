# Payzy Payment Gateway - Setup Guide

## Overview
This guide explains how to configure and use the Payzy payment gateway integration in your Bagisto e-commerce store.

## Test Credentials Provided

### Payzy Configuration
- **Shop ID**: `2`
- **Secret Key**: `$2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy`

### Customer Portal (For Testing)
- **URL**: Development Server
- **Username**: `test@payzy.lk`
- **Password**: `Test@!123`

## Configuration Steps

### 1. Access Admin Panel
1. Log in to your Bagisto admin panel
2. Navigate to: **Configuration → Sales → Payment Methods → Payzy**

### 2. Configure Payzy Settings

Fill in the following fields:

| Field | Value | Description |
|-------|-------|-------------|
| **Status** | Active | Enable/disable the payment method |
| **Title** | Payzy Payment Gateway | Display name shown to customers |
| **Description** | Secure online payment | Description shown during checkout |
| **Shop ID** | `2` | Your Payzy merchant shop ID |
| **Secret Key** | `$2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy` | Your Payzy secret key for signature generation |
| **Sandbox Mode** | ON (for testing) | Toggle between test and live mode |
| **Generate Invoice** | Yes/No | Auto-generate invoice after order |
| **Invoice Status** | Paid/Pending | Default invoice status |
| **Order Status** | Processing | Default order status after payment |
| **Sort Order** | 3 | Display order in payment methods list |

### 3. Save Configuration
Click **Save** to apply the changes.

## How It Works

### Payment Flow

1. **Customer initiates checkout**
   - Customer completes shipping and billing information
   - Selects "Payzy" as payment method

2. **Payment processing**
   - Customer is redirected to Payzy payment page
   - Secure payment data is sent with HMAC-SHA256 signature
   - Customer enters payment details on Payzy's secure page

3. **Payment completion**
   - Payzy processes the payment
   - Customer is redirected back to your store
   - Payment signature is verified
   - Order is created if payment is successful

### Technical Implementation

#### API Endpoint
```
https://api.payzypay.xyz/checkout/custom-checkout
```

#### Signature Generation
The integration uses HMAC-SHA256 hashing to secure payment data:

1. All payment fields are concatenated with their values
2. HMAC-SHA256 hash is generated using the secret key
3. Hash is base64 encoded
4. Signature is sent with the payment request

#### Payment Parameters Sent to Payzy

```php
[
    'x_test_mode'           => 'on/off',
    'x_shopid'              => '2',
    'x_amount'              => '100.00',
    'x_order_id'            => 'ORDER_ID',
    'x_response_url'        => 'https://yourstore.com/payzy/success',
    'x_first_name'          => 'Customer First Name',
    'x_last_name'           => 'Customer Last Name',
    'x_company'             => 'Company Name',
    'x_address'             => 'Address',
    'x_country'             => 'Country',
    'x_state'               => 'State',
    'x_city'                => 'City',
    'x_zip'                 => 'Postal Code',
    'x_phone'               => 'Phone Number',
    'x_email'               => 'Email',
    // Shipping address fields
    'x_ship_to_first_name'  => 'Shipping First Name',
    // ... other shipping fields
    'x_freight'             => 'x_freight',
    'x_platform'            => 'custom',
    'x_version'             => '1.0',
    'signed_field_names'    => 'field1,field2,field3,...',
    'signature'             => 'GENERATED_SIGNATURE',
]
```

#### Response Verification

When Payzy redirects back:
1. Response includes: `x_order_id`, `response_code`, `signature`
2. System verifies signature using the same HMAC-SHA256 method
3. `response_code = 00` indicates successful payment
4. Order is created only after successful verification

## Files Modified/Created

### Created Files
1. `/packages/Webkul/Payment/src/Payment/Payzy.php`
   - Payment method class

2. `/packages/Webkul/Payment/src/Http/Controllers/PayzyController.php`
   - Handles payment processing, success, and cancellation

3. `/packages/Webkul/Payment/src/Http/routes.php`
   - Defines payment routes

### Modified Files
1. `/packages/Webkul/Payment/src/Config/paymentmethods.php`
   - Added Payzy configuration

2. `/packages/Webkul/Admin/src/Config/system.php`
   - Added admin panel settings

3. `/packages/Webkul/Admin/src/Resources/lang/en/app.php`
   - Added English translations

4. `/packages/Webkul/Payment/src/Providers/PaymentServiceProvider.php`
   - Registered routes

## Testing the Integration

### Test Mode (Sandbox)
1. Ensure **Sandbox Mode** is enabled in configuration
2. Make a test purchase on your store
3. Use test payment credentials provided by Payzy
4. Verify order is created after successful payment

### Production Mode
1. Disable **Sandbox Mode** in configuration
2. Ensure you have production credentials from Payzy
3. Test with small amount first
4. Monitor logs for any issues

## Routes Available

| Route | URL | Purpose |
|-------|-----|---------|
| Process | `/payzy/process` | Initiates payment and redirects to Payzy |
| Success | `/payzy/success` | Handles successful payment callback |
| Cancel | `/payzy/cancel` | Handles payment cancellation |

## Troubleshooting

### Common Issues

1. **Payment not processing**
   - Verify Shop ID and Secret Key are correct
   - Check if Sandbox mode is properly configured
   - Review Laravel logs: `storage/logs/laravel.log`

2. **Signature verification failed**
   - Ensure Secret Key matches exactly (case-sensitive)
   - Check all required fields are being sent
   - Verify field order in signature generation

3. **Order not created**
   - Check if response_code is '00'
   - Verify cart still exists during callback
   - Review logs for errors

### Debug Mode
Enable Laravel debug mode to see detailed errors:
```bash
# .env file
APP_DEBUG=true
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

## Security Features

1. **HMAC-SHA256 Signature**: All requests are signed and verified
2. **Session Validation**: Payment data stored in session for verification
3. **Response Verification**: Incoming responses are validated before processing
4. **Secure Key Storage**: Secret key stored in database configuration

## Support & Documentation

- **Payzy Documentation**: https://payzy.lk/dev-doc
- **Sample Integration**: `/Users/kaviya/Downloads/payzy/`
- **Customer Portal**: Contact Payzy for access details

## Additional Notes

- The integration follows Bagisto payment gateway standards
- All customer data is sent securely to Payzy
- Payment is processed on Payzy's secure servers
- Store never handles credit card information directly
- PCI compliance is managed by Payzy

## Maintenance

### Update Credentials
If you need to update credentials:
1. Go to Admin Panel → Configuration → Sales → Payment Methods → Payzy
2. Update Shop ID or Secret Key
3. Save configuration
4. Clear cache: `php artisan config:clear`

### Switch Between Test and Production
Simply toggle the **Sandbox Mode** setting and save.

---

**Last Updated**: October 24, 2025  
**Integration Version**: 1.0  
**Bagisto Compatibility**: Laravel-based Bagisto
