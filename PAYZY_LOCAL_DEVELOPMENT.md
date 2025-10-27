# Payzy Local Development Testing Guide

## The Problem with Local Testing

**IMPORTANT**: Payzy's payment flow requires that their server redirects the customer back to your `x_response_url` after payment. 

When testing locally with domains like:
- `http://localhost:8000`
- `https://axita.test` (Laravel Valet)
- `http://127.0.0.1:8000`

**Payzy's server CANNOT reach your local machine** because these URLs are not publicly accessible on the internet.

## ❌ What Doesn't Work

```
Your Local Machine (axita.test)
     ↓ 
  [Send payment request to Payzy]
     ↓
Payzy Server (receives request, shows payment page)
     ↓
Customer pays on Payzy
     ↓
Payzy tries to redirect to: https://axita.test/payzy/success
     ❌ FAILS - Payzy server cannot reach axita.test
```

## ✅ Solution 1: Use ngrok to Expose Your Local Server

### What is ngrok?
ngrok creates a secure tunnel from the public internet to your local machine, giving you a temporary public URL.

### Step-by-Step Guide:

#### 1. Install ngrok
```bash
# Using Homebrew (recommended for macOS)
brew install ngrok

# Or download from https://ngrok.com/download
```

#### 2. Start ngrok tunnel
```bash
# If using Laravel Valet on axita.test (port 80)
ngrok http axita.test:80

# Or if using php artisan serve (port 8000)
ngrok http 8000
```

#### 3. ngrok will display output like:
```
Session Status                online
Account                       your_account
Version                       3.x.x
Region                        United States (us)
Forwarding                    https://abc123.ngrok.io -> http://axita.test:80
```

#### 4. Update your .env file with the ngrok URL:
```bash
APP_URL=https://abc123.ngrok.io
```

#### 5. Clear cache:
```bash
cd /Users/kaviya/Documents/crowlk/axita
php artisan config:clear
php artisan cache:clear
```

#### 6. Test payment flow:
- Access your site via the ngrok URL: `https://abc123.ngrok.io`
- Add items to cart
- Proceed to checkout with Payzy
- Payzy will now be able to redirect back to your local server!

### Important Notes about ngrok:
- **Free tier**: URLs change every time you restart ngrok
- **Paid tier**: You get a permanent URL
- **Update APP_URL** each time you get a new ngrok URL
- Keep the ngrok terminal window open while testing

## ✅ Solution 2: Use a Staging Server

Deploy your application to a publicly accessible server (even a cheap VPS):
- DigitalOcean Droplet ($6/month)
- AWS Lightsail ($5/month)
- Linode ($5/month)

This gives you a permanent URL for testing.

## ✅ Solution 3: Use Payzy's Test Mode Correctly

Even with test credentials, you still need:
1. A publicly accessible URL for `x_response_url`
2. Valid test credentials from Payzy
3. Test mode enabled (`x_test_mode = 'on'`)

### Current Test Credentials:
```
Shop ID: 2
Secret Key: $2b$12$82C876HIXARFRAF8iQB6JO2C5Zc9NeEZqCwcLY2eJe2klTw.EGvWy
Test Mode: on
```

**Note**: These demo credentials from documentation may not create real payment sessions. Contact Payzy support for actual test merchant account credentials.

## Testing Checklist

### Before Testing:
- [ ] ngrok is running and URL is updated in .env
- [ ] Cache is cleared
- [ ] Payzy configuration is saved in admin panel
- [ ] Test mode is enabled
- [ ] Shop ID and Secret Key are correct

### During Testing:
- [ ] Access site via ngrok URL
- [ ] Add product to cart
- [ ] Proceed to checkout
- [ ] Fill in billing/shipping information
- [ ] Select Payzy payment method
- [ ] Click "Place Order"
- [ ] Monitor Laravel logs: `tail -f storage/logs/laravel.log`
- [ ] Verify redirect to Payzy payment page
- [ ] Complete payment on Payzy
- [ ] Verify redirect back to your success page
- [ ] Check order is created in admin panel

### Check Logs:
```bash
# In your project directory
tail -f storage/logs/laravel.log

# Look for these log entries:
# - "Payzy Payment Request" - Request sent to Payzy
# - "Payzy API Response" - Response from Payzy
# - "Redirecting to Payzy payment URL" - Redirect to payment page
# - "Payzy success callback" - Return from Payzy
# - "Payzy signature verification" - Signature validation
```

## Common Issues and Solutions

### Issue 1: "Invalid temp order id" on Payzy page
**Cause**: Demo credentials don't create real payment sessions  
**Solution**: Contact Payzy support for actual test merchant credentials

### Issue 2: Redirect doesn't work
**Cause**: Using local domain (axita.test) instead of ngrok URL  
**Solution**: Access site via ngrok URL, not local domain

### Issue 3: Signature verification failed
**Cause**: Secret key mismatch or incorrect field order  
**Solution**: 
- Verify secret key is exactly correct (case-sensitive)
- Check logs for signature generation details
- Ensure all fields are in correct order

### Issue 4: Payment session expired
**Cause**: Session data cleared or cookies not working  
**Solution**: 
- Ensure cookies are enabled
- Don't have multiple tabs open
- Complete payment flow without refreshing

## Alternative: Skip Local Testing

If ngrok is too complicated, you can:

1. **Test directly on staging/production server** with test mode enabled
2. **Use Payzy's test cards** (if they provide any)
3. **Review code logic** without actual payment testing
4. **Wait for production credentials** and test on live server

## Important Security Notes

⚠️ **Never commit ngrok URLs or test credentials to version control**

⚠️ **Always use HTTPS in production**

⚠️ **Switch to production credentials and disable test mode before going live**

## Getting Help

1. **Check Laravel logs**: `storage/logs/laravel.log`
2. **Enable debug mode**: Set `APP_DEBUG=true` in .env
3. **Contact Payzy support**: For test credentials and integration help
4. **Review Payzy docs**: https://payzy.lk/dev-doc

## Production Deployment Checklist

Before deploying to production:

- [ ] Set `APP_ENV=production` in .env
- [ ] Set `APP_DEBUG=false` in .env
- [ ] Update APP_URL to actual domain (not ngrok)
- [ ] Update Payzy credentials to production keys
- [ ] Set Payzy test mode to "off"
- [ ] Clear all caches
- [ ] Test with small amount first
- [ ] Monitor logs for any issues
- [ ] Verify orders are created correctly

---

**Summary**: You CAN test Payzy in local development, but you MUST use ngrok (or similar) to expose your local server to the internet so Payzy can redirect back to your application after payment.
