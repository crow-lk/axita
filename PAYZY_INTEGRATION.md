# Payzy Payment Gateway Integration

This document describes the Payzy payment gateway integration for your Bagisto e-commerce store.

## Overview

The Payzy payment gateway has been successfully integrated into your Bagisto application. It allows customers to make secure online payments using credit cards, debit cards, and other payment methods supported by Payzy.

## Files Created/Modified

### New Files Created:

1. **Payment Class**: `packages/Webkul/Payment/src/Payment/Payzy.php`
   - Main payment method class that extends the base Payment class
   - Handles payment method availability and configuration

2. **Controller**: `packages/Webkul/Payment/src/Http/Controllers/PayzyController.php`
   - Handles payment processing, success, cancellation, and callback
   - Integrates with Payzy API for payment transactions

3. **Routes**: `packages/Webkul/Payment/src/Http/routes.php`
   - Defines routes for payment processing, success, cancel, and callback endpoints

### Modified Files:

1. **Payment Methods Config**: `packages/Webkul/Payment/src/Config/paymentmethods.php`
   - Added Payzy payment method configuration

2. **System Configuration**: `packages/Webkul/Admin/src/Config/system.php`
   - Added Payzy admin configuration fields including:
     - Title
     - Description
     - Logo
     - API Key
     - Secret Key
     - Sandbox Mode
     - Invoice Generation Settings
     - Order Status Settings
     - Active Status
     - Sort Order

3. **Service Provider**: `packages/Webkul/Payment/src/Providers/PaymentServiceProvider.php`
   - Added route loading for Payzy routes

4. **Language Files**: `packages/Webkul/Admin/src/Resources/lang/en/app.php`
   - Added translations for Payzy payment method

## Configuration

### Admin Panel Configuration

1. Log in to your Bagisto admin panel
2. Navigate to **Configuration → Sales → Payment Methods**
3. Find the **Payzy** section
4. Configure the following settings:

   - **Status**: Enable/Disable the payment method
   - **Title**: Display name for the payment method (e.g., "Payzy Payment")
   - **Description**: Brief description of the payment method
   - **Logo**: Upload a logo image (recommended size: 55px x 45px)
   - **API Key**: Your Payzy API key (required)
   - **Secret Key**: Your Payzy secret key (required)
   - **Sandbox**: Enable for testing, disable for production
   - **Generate Invoice**: Automatically generate invoice after order
   - **Invoice Status**: Set invoice status (Pending/Paid)
   - **Order Status**: Set order status (Pending/Processing/Pending Payment)
   - **Sort Order**: Display order in payment methods list

### API Keys

To get your Payzy API credentials:

1. Sign up for a Payzy merchant account at [Payzy website]
2. Navigate to your merchant dashboard
3. Generate API keys for:
   - **Sandbox** (for testing)
   - **Production** (for live transactions)

## Routes

The following routes are available:

- **GET** `/payzy/process` - Initiates payment processing
- **GET** `/payzy/success` - Handles successful payment callback
- **GET** `/payzy/cancel` - Handles payment cancellation
- **POST** `/payzy/callback` - Webhook endpoint for payment notifications (CSRF exempt)

## Payment Flow

1. Customer selects Payzy as payment method during checkout
2. Customer proceeds to place order
3. System redirects customer to Payzy payment gateway
4. Customer completes payment on Payzy platform
5. Payzy redirects back to success/cancel URL based on payment status
6. System verifies payment with Payzy API
7. Order is created and customer receives confirmation

## Important Notes

### Sandbox Testing

- Set **Sandbox** to **Yes** in admin configuration
- Use test API credentials provided by Payzy
- Test with Payzy test cards

### Production Deployment

- Set **Sandbox** to **No** in admin configuration
- Use production API credentials
- Ensure SSL certificate is properly configured
- Test thoroughly before going live

### Webhook Configuration

Configure the webhook URL in your Payzy merchant dashboard:
```
https://yourdomain.com/payzy/callback
```

This endpoint receives payment status updates from Payzy.

### Security

- Store API keys securely
- Use HTTPS for all transactions
- The callback endpoint is CSRF-exempt to receive webhooks from Payzy
- Payment verification is performed server-side for security

## Customization

### Modifying API Integration

The Payzy API integration logic is in `PayzyController.php`. Update the following based on Payzy's actual API documentation:

- API endpoints
- Request/response format
- Authentication method
- Payment verification process

### Adding Custom Fields

To add more configuration fields:

1. Update `packages/Webkul/Admin/src/Config/system.php`
2. Add fields in the Payzy section
3. Access in controller using: `core()->getConfigData('sales.payment_methods.payzy.field_name')`

### Styling

Add a custom logo by uploading it in the admin panel or placing a default image at:
```
public/themes/shop/default/assets/images/payzy.png
```

## Troubleshooting

### Payment Not Working

1. Verify API keys are correct
2. Check sandbox mode setting matches your API keys
3. Review logs at `storage/logs/laravel.log`
4. Ensure routes are registered: `php artisan route:list | grep payzy`

### Configuration Not Showing

1. Clear cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

### Webhook Issues

1. Verify webhook URL is configured in Payzy dashboard
2. Check callback logs in `storage/logs/laravel.log`
3. Ensure domain is accessible from internet (for production)

## Support

For Payzy API documentation and support:
- Payzy Documentation: [Contact Payzy]
- Bagisto Documentation: https://bagisto.com/en/documentation/

## Cache Commands

After making any configuration changes, run:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Version Information

- Bagisto Version: Compatible with Bagisto v2.x
- PHP Version: 8.1+
- Laravel Version: 10.x+

## License

This integration follows the same license as your Bagisto installation.
