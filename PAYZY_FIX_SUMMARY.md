# PayZY Payment Integration - Issue Fixed

## Problem: "Invalid temp orderId" Error

### Root Cause Identified ✓

The main issue was that the `x_response_url` was using the local domain (`https://axita.test/payzy/success`) instead of the ngrok public URL (`https://e213c772a853.ngrok-free.app/payzy/success`).

PayZY needs a **publicly accessible URL** to redirect customers back to your site after payment. Local `.test` domains are not accessible from the internet.

### Changes Made

#### 1. **Fixed x_response_url to Use APP_URL** ✓
**Changed from:** `route('payzy.success')` (which resolves to local domain)
**Changed to:** `url('/payzy/success')` (which uses APP_URL from .env)

**Location:** `packages/Webkul/Payment/src/Http/Controllers/PayzyController.php` line ~92

#### 2. **Fixed x_freight Field** ✓
**Changed from:** `'x_freight' => 'x_freight'` (literal string)
**Changed to:** `'x_freight' => number_format($cart->selected_shipping_rate->price ?? 0, 2, '.', '')`

**Location:** `packages/Webkul/Payment/src/Http/Controllers/PayzyController.php` line ~111

#### 3. **Updated Order ID Format** ✓
**Changed from:** `ORD-{cart_id}-{timestamp}`
**Changed to:** `ORDER-{cart_id}-{timestamp}`

**Location:** `packages/Webkul/Payment/src/Http/Controllers/PayzyController.php` line ~67

## Testing Steps

### 1. Verify ngrok is Running

```bash
# Check if ngrok is running
ps aux | grep ngrok
```

Your ngrok URL should match the APP_URL in .env:
- **.env:** `APP_URL=https://e213c772a853.ngrok-free.app`
- **ngrok URL:** Should show the same domain

If ngrok is not running or the URL changed, start it:
```bash
ngrok http 80
# or
ngrok http 8080
# (use whatever port Herd is serving on)
```

Then update APP_URL in .env with the new ngrok URL and clear cache:
```bash
php artisan config:clear
php artisan cache:clear
```

### 2. Test Payment Flow

1. **Add items to cart** on your site
2. **Go to checkout**
3. **Fill in billing/shipping information** (use real-looking data)
4. **Select PayZY** as payment method
5. **Click "Place Order"**

### 3. What Should Happen Now

1. You should be redirected to PayZY's payment page (different URL each time, not `/fromwordpress/e1`)
2. The payment page should load properly (not show "invalid temp orderId")
3. After completing payment, you should be redirected back to your site
4. Order should be created successfully

### 4. Monitor Logs

In a separate terminal, run:
```bash
tail -f storage/logs/laravel.log | grep Payzy
```

Look for these key log entries:
- **"Payzy Payment Request"** - Shows the data being sent
- **"x_response_url"** - Should now show your ngrok URL, not axita.test
- **"Payzy API Response"** - Should show a unique URL each time
- **"Redirecting to Payzy payment URL"** - Should redirect to a unique URL

### 5. Check Configuration

```bash
php artisan tinker --execute="
echo 'APP_URL: ' . config('app.url') . PHP_EOL;
echo 'PayZY Response URL: ' . url('/payzy/success') . PHP_EOL;
echo 'PayZY Shop ID: ' . core()->getConfigData('sales.payment_methods.payzy.shop_id') . PHP_EOL;
echo 'PayZY Test Mode: ' . (core()->getConfigData('sales.payment_methods.payzy.sandbox') ? 'on' : 'off') . PHP_EOL;
"
```

**Expected output:**
```
APP_URL: https://e213c772a853.ngrok-free.app
PayZY Response URL: https://e213c772a853.ngrok-free.app/payzy/success
PayZY Shop ID: 2
PayZY Test Mode: on
```

## Important Notes

### ngrok URL Changes

⚠️ **Important:** ngrok free tier URLs change every time you restart ngrok!

Whenever you restart ngrok:
1. Copy the new ngrok URL
2. Update APP_URL in `.env`
3. Run `php artisan config:clear`
4. Run `php artisan cache:clear`

### Production Deployment

When deploying to production:
1. Set `APP_URL` to your actual domain (e.g., `https://axita.lk`)
2. Update PayZY settings to use production credentials
3. Set `x_test_mode` to `'off'` in PayZY admin configuration

## Troubleshooting

### Issue: Still getting "invalid temp orderId"

**Check:**
1. Is ngrok running? `ps aux | grep ngrok`
2. Does APP_URL in .env match ngrok URL?
3. Did you clear cache after changing .env?
4. Check logs - what URL is being sent as x_response_url?

**Solution:**
```bash
# Verify ngrok status
curl -s http://localhost:4040/api/tunnels | grep -o 'https://[^"]*'

# This should match your APP_URL in .env
# If different, update .env and clear cache
nano .env  # or use your preferred editor
php artisan config:clear && php artisan cache:clear
```

### Issue: Response URL still shows axita.test

**Cause:** Config is cached or APP_URL is not set correctly

**Solution:**
```bash
# Clear all caches
php artisan optimize:clear

# Verify APP_URL
grep APP_URL .env

# Should show:
# APP_URL=https://e213c772a853.ngrok-free.app
```

### Issue: PayZY redirects to /fromwordpress/e1 (same URL every time)

**Cause:** PayZY is rejecting the request and showing an error page

**Possible reasons:**
1. Response URL is not publicly accessible
2. Signature is incorrect
3. Shop ID or secret key is wrong
4. Order ID format is invalid

**Solution:**
1. Check that x_response_url in logs shows ngrok URL
2. Test ngrok URL is accessible: Visit your ngrok URL in a browser
3. Verify credentials in PayZY admin panel match your config
4. Contact PayZY support with the error details

### Issue: ngrok "Visit Site" button required

If ngrok shows an interstitial page with "Visit Site" button:

**Solution:** This is normal for free ngrok. Visitors must click "Visit Site" once.
For production, use a paid ngrok plan or deploy to a real domain.

## Verification Checklist

- [x] x_response_url fixed to use APP_URL
- [x] x_freight uses numeric value instead of literal string
- [x] Order ID format updated
- [x] Signature generation verified
- [x] Cache cleared
- [ ] ngrok running and URL matches APP_URL
- [ ] Test payment completes successfully
- [ ] Callback URL receives payment confirmation
- [ ] Order is created in database

## Files Modified

1. **packages/Webkul/Payment/src/Http/Controllers/PayzyController.php**
   - Line ~67: Order ID format (ORD → ORDER)
   - Line ~92: Response URL (route → url helper)
   - Line ~111: x_freight (string → numeric)

## Quick Test Command

```bash
# Test that everything is configured correctly
cd /Users/kaviya/Documents/crowlk/axita
php artisan tinker --execute="
\$data = [
    'x_test_mode' => 'on',
    'x_shopid' => '2',
    'x_amount' => '100.00',
    'x_order_id' => 'TEST-001',
    'x_response_url' => url('/payzy/success'),
];
echo 'Response URL: ' . \$data['x_response_url'] . PHP_EOL;
echo 'Should be: https://e213c772a853.ngrok-free.app/payzy/success' . PHP_EOL;
echo 'Match: ' . (\$data['x_response_url'] === 'https://e213c772a853.ngrok-free.app/payzy/success' ? 'YES ✓' : 'NO ✗') . PHP_EOL;
"
```

## Need Help?

If issues persist after following this guide:

1. **Collect information:**
   - Log output showing the Payzy Payment Request
   - Screenshot of the error from PayZY site
   - Your ngrok URL
   - Output of the verification checklist

2. **Contact PayZY Support:**
   - Provide your Shop ID: 2
   - Show them the signature you're generating
   - Send the full request payload from logs
   - Explain you're using test mode with ngrok

3. **Check these files:**
   - `.env` - Ensure APP_URL is correct
   - `storage/logs/laravel.log` - Check for errors
   - PayZY Controller - Verify all changes are applied

---

**Summary:** The main fix was changing the response URL from using `route()` helper (which uses local domain) to `url()` helper (which uses APP_URL from .env). This ensures PayZY gets a publicly accessible ngrok URL to redirect customers back to your site after payment.
