# Checkout Simplification and Gateway Charge Feature

## Conventional Commit Message

```
feat(checkout): simplify checkout flow and add gateway charge feature

BREAKING CHANGE: Checkout flow changed from multi-step to single-page layout

Checkout Simplification:
- Remove multi-step navigation with accordion components
- Display address, shipping, and payment sections simultaneously
- Remove "Proceed" buttons after address section
- Auto-select first shipping method on load and after address entry
- Load shipping/payment methods immediately to eliminate shimmer effects
- Add shipping_rates and payment_methods to CartResource API

Gateway Charge Feature:
- Add gateway_charge and base_gateway_charge columns to cart table
- Add gateway_charge configuration field to all payment methods (0-100%)
- Implement gateway charge calculation in Cart::collectTotals()
- Add Payment::getGatewayChargePercentage() and calculateGatewayCharge()
- Display gateway charge in cart summary below delivery charges
- Add translations for gateway charge in admin and shop

Bug Fixes:
- Fix payment class instantiation in Cart::collectTotals()
- Fix VeeValidate validation errors by moving validation to backend
- Add server-side validation for gateway_charge (nullable|numeric|min:0|max:100)

Files Modified:
- packages/Webkul/Checkout/src/Database/Migrations/2025_01_24_000000_add_gateway_charge_columns_to_cart_table.php
- packages/Webkul/Admin/src/Config/system.php
- packages/Webkul/Payment/src/Payment/Payment.php
- packages/Webkul/Checkout/src/Cart.php
- packages/Webkul/Shop/src/Http/Resources/CartResource.php
- packages/Webkul/Shop/src/Resources/views/checkout/onepage/index.blade.php
- packages/Webkul/Shop/src/Resources/views/checkout/onepage/address.blade.php
- packages/Webkul/Shop/src/Resources/views/checkout/onepage/address/guest.blade.php
- packages/Webkul/Shop/src/Resources/views/checkout/onepage/address/customer.blade.php
- packages/Webkul/Shop/src/Resources/views/checkout/onepage/shipping.blade.php
- packages/Webkul/Shop/src/Resources/views/checkout/onepage/payment.blade.php
- packages/Webkul/Shop/src/Resources/views/checkout/onepage/summary.blade.php
- packages/Webkul/Admin/src/Http/Requests/ConfigurationForm.php
- packages/Webkul/Admin/src/Resources/lang/en/app.php
- packages/Webkul/Shop/src/Resources/lang/en/app.php
```

---

## Detailed Summary of Changes

### 1. Checkout Page Simplification

**WHY:** Improve user experience by reducing checkout friction - showing all steps at once instead of multi-step navigation.

**WHAT CHANGED:**

#### Frontend Components

**index.blade.php (packages/Webkul/Shop/src/Resources/views/checkout/onepage/)**
- Removed step-based conditional rendering
- All sections (address, shipping, payment) now visible simultaneously
- Modified `getCart()` method (lines 193-200) to load shipping/payment methods from cart data on mount
- Updated `stepProcessed()` method to handle both shipping and payment method responses
- Changed `canPlaceOrder` from data property to computed property

**address.blade.php**
- Removed accordion wrapper component (`x-shop::accordion`)
- Replaced with simple heading: `<h2 class="mb-4 text-2xl font-medium max-md:text-base">`
- Simplified structure to show address form directly

**guest.blade.php**
- Removed "Proceed" button section (lines 93-101 deleted)
- Form now submits automatically after address entry
- Cleaner interface without unnecessary navigation

**customer.blade.php**
- Removed "Proceed" button section (lines 246-254 deleted)
- Address selection flows directly to next sections

**shipping.blade.php**
- Removed accordion wrapper
- Added direct heading display
- Added `cart` prop to component
- **Auto-selection Logic (lines 120-142)**:
  - Added `initializeShippingMethod()` function
  - Auto-selects first shipping rate on page load if methods available
  - Checks if shipping method already selected in cart
  - Prevents multiple auto-selections with `autoSelectedOnce` flag
  - Automatically calls `store()` to save selection

**payment.blade.php**
- Removed accordion wrapper
- Added direct heading
- Added `cart` prop for tracking selected method
- Added `selectedPaymentMethod` data property
- Set selected method on mount if already chosen in cart

#### API/Backend Changes

**CartResource.php (packages/Webkul/Shop/src/Http/Resources/)**
- Added `shipping_rates` field with `getGroupedShippingRates()` method
  - Groups shipping rates by carrier
  - Returns null if no billing address
  - Uses `Shipping::collectRates()` to fetch available rates
- Added `payment_methods` field with `getAvailablePaymentMethods()` method
  - Returns null if no billing address
  - Uses `Payment::getPaymentMethods()` to fetch available methods
- **Purpose:** Prevents shimmer effects by providing data immediately on page load

---

### 2. Gateway Charge Feature

**WHY:** Add configurable payment gateway charges (percentage-based) per payment method to cover transaction fees.

**WHAT CHANGED:**

#### Database Schema

**Migration: 2025_01_24_000000_add_gateway_charge_columns_to_cart_table.php**
```php
Schema::table('cart', function (Blueprint $table) {
    $table->decimal('gateway_charge', 12, 4)->default(0)->after('base_sub_total_incl_tax');
    $table->decimal('base_gateway_charge', 12, 4)->default(0)->after('gateway_charge');
});
```
- Stores calculated gateway charge for current currency
- Stores base gateway charge for base currency
- Default value: 0 (no charge if not configured)

#### Admin Configuration

**system.php (packages/Webkul/Admin/src/Config/)**
Added `gateway_charge` field to 4 payment methods:
1. Cash on Delivery (line 1408)
2. Money Transfer (line 1521)
3. PayPal Standard (line 1592)
4. PayPal Smart Button (line 1688)

Configuration:
```php
[
    'name'          => 'gateway_charge',
    'title'         => 'admin::app.configuration.index.sales.payment-methods.gateway-charge',
    'type'          => 'text',
    'info'          => 'admin::app.configuration.index.sales.payment-methods.gateway-charge-info',
    'validation'    => '',  // Empty to avoid VeeValidate errors
    'channel_based' => true,  // Can be different per channel
    'locale_based'  => false,
]
```

#### Backend Validation

**ConfigurationForm.php (packages/Webkul/Admin/src/Http/Requests/)**
Lines 37-40: Added server-side validation for gateway_charge
```php
if ($field['name'] === 'gateway_charge') {
    return [$key => 'nullable|numeric|min:0|max:100'];
}
```
- Validates gateway charge is between 0-100%
- Optional field (nullable)
- Numeric only
- **WHY:** VeeValidate (frontend) doesn't support 'nullable', so validation moved to backend

#### Payment Logic

**Payment.php (packages/Webkul/Payment/src/Payment/)**

Added two methods:

1. **getGatewayChargePercentage()**
```php
public function getGatewayChargePercentage()
{
    return (float) $this->getConfigData('gateway_charge') ?: 0;
}
```
- Returns configured percentage for current payment method
- Returns 0 if not configured

2. **calculateGatewayCharge($subtotal)**
```php
public function calculateGatewayCharge($subtotal)
{
    $percentage = $this->getGatewayChargePercentage();
    if ($percentage <= 0) {
        return 0;
    }
    return ($subtotal * $percentage) / 100;
}
```
- Calculates charge based on cart subtotal
- Formula: subtotal × percentage ÷ 100

#### Cart Calculation

**Cart.php (packages/Webkul/Checkout/src/)**
Lines 884-906: Modified `collectTotals()` method

```php
$this->cart->gateway_charge = $this->cart->base_gateway_charge = 0;

if ($this->cart->payment && $this->cart->payment->method) {
    try {
        $paymentMethodClass = config('payment_methods.'.$this->cart->payment->method.'.class');
        if ($paymentMethodClass) {
            $payment = app($paymentMethodClass);
            $gatewayCharge = $payment->calculateGatewayCharge($this->cart->base_sub_total);
            $this->cart->base_gateway_charge = round($gatewayCharge, 2);
            $this->cart->gateway_charge = round(core()->convertPrice($gatewayCharge), 2);
            $this->cart->grand_total += $this->cart->gateway_charge;
            $this->cart->base_grand_total += $this->cart->base_gateway_charge;
        }
    } catch (\Exception $e) {
        // If payment method not found, gateway charge remains 0
    }
}
```

**Key Points:**
- Calculates gateway charge after payment method is selected
- Uses base_sub_total as calculation base
- Converts to cart currency for display
- Adds to grand total
- **BUG FIX:** Changed from non-existent `payment()->getPayment()` to proper class instantiation

#### API Response

**CartResource.php**
Added fields:
```php
'gateway_charge'           => $this->gateway_charge ?? 0,
'formatted_gateway_charge' => core()->formatPrice($this->gateway_charge ?? 0),
```

#### Frontend Display

**summary.blade.php (packages/Webkul/Shop/src/Resources/views/checkout/onepage/)**
Lines 185-201: Added gateway charge display

```php
<!-- Gateway Charge -->
<div
    class="flex justify-between text-right"
    v-if="cart.gateway_charge && parseFloat(cart.gateway_charge) > 0"
>
    <p class="text-base max-sm:text-sm">
        @lang('shop::app.checkout.onepage.summary.gateway-charge')
    </p>
    <p class="text-base font-medium max-sm:text-sm">
        @{{ cart.formatted_gateway_charge }}
    </p>
</div>
```
- Displayed below "Delivery Charges"
- Only shows if gateway_charge > 0
- Responsive styling

#### Translations

**admin/app.php (packages/Webkul/Admin/src/Resources/lang/en/)**
Lines 3852-3853:
```php
'gateway-charge'      => 'Gateway Charge (%)',
'gateway-charge-info' => 'Additional charge as a percentage of the subtotal (0-100). Leave empty or 0 for no charge.',
```

**shop/app.php (packages/Webkul/Shop/src/Resources/lang/en/)**
Line 834:
```php
'gateway-charge' => 'Gateway Charge',
```

---

### 3. Bug Fixes

#### Cart Add Error (Call to undefined method getPayment)

**Problem:** After implementing gateway charge, users couldn't add items to cart

**Error:** `Call to undefined method Webkul\Payment\Payment::getPayment()`

**Root Cause:** Tried to call non-existent `payment()->getPayment()` method in Cart.php

**Fix (Cart.php lines 891-894):**
```php
// Before (incorrect):
$payment = payment()->getPayment($this->cart->payment->method);

// After (correct):
$paymentMethodClass = config('payment_methods.'.$this->cart->payment->method.'.class');
if ($paymentMethodClass) {
    $payment = app($paymentMethodClass);
}
```

#### VeeValidate Validation Errors

**Problem:** Console error when saving payment method configuration

**Error:**
- `Error: No such validator 'nullable' exists.`
- `Error: No such validator 'min_value_value' exists.`

**Root Cause:**
- Used Laravel validation syntax instead of VeeValidate syntax
- VeeValidate (frontend validator) doesn't support `nullable`, `min:`, `max:` rules

**Fix:**
1. **system.php**: Removed all frontend validation for gateway_charge fields
   ```php
   'validation' => '',  // Empty validation
   ```

2. **ConfigurationForm.php**: Added backend validation
   ```php
   if ($field['name'] === 'gateway_charge') {
       return [$key => 'nullable|numeric|min:0|max:100'];
   }
   ```

**Result:**
- No VeeValidate errors in browser console
- Gateway charge still validated server-side
- Configuration saves successfully

---

## Files Modified

### Database
- `packages/Webkul/Checkout/src/Database/Migrations/2025_01_24_000000_add_gateway_charge_columns_to_cart_table.php`

### Configuration
- `packages/Webkul/Admin/src/Config/system.php`
- `packages/Webkul/Admin/src/Http/Requests/ConfigurationForm.php`

### Backend Logic
- `packages/Webkul/Payment/src/Payment/Payment.php`
- `packages/Webkul/Checkout/src/Cart.php`
- `packages/Webkul/Shop/src/Http/Resources/CartResource.php`

### Frontend Views
- `packages/Webkul/Shop/src/Resources/views/checkout/onepage/index.blade.php`
- `packages/Webkul/Shop/src/Resources/views/checkout/onepage/address.blade.php`
- `packages/Webkul/Shop/src/Resources/views/checkout/onepage/address/guest.blade.php`
- `packages/Webkul/Shop/src/Resources/views/checkout/onepage/address/customer.blade.php`
- `packages/Webkul/Shop/src/Resources/views/checkout/onepage/shipping.blade.php`
- `packages/Webkul/Shop/src/Resources/views/checkout/onepage/payment.blade.php`
- `packages/Webkul/Shop/src/Resources/views/checkout/onepage/summary.blade.php`

### Translations
- `packages/Webkul/Admin/src/Resources/lang/en/app.php`
- `packages/Webkul/Shop/src/Resources/lang/en/app.php`

---

## Testing Checklist

- [ ] Run migration: `php artisan migrate`
- [ ] Clear caches: `php artisan config:clear && php artisan cache:clear`
- [ ] Test checkout flow as guest
- [ ] Test checkout flow as logged-in customer
- [ ] Verify shipping method auto-selects
- [ ] Configure gateway charge for a payment method (e.g., 2.5%)
- [ ] Add items to cart and verify gateway charge calculation
- [ ] Verify gateway charge displays in cart summary
- [ ] Test with 0% gateway charge (should not display)
- [ ] Verify configuration saves without VeeValidate errors
- [ ] Test with different channels (if multi-channel setup)
- [ ] Verify grand total includes gateway charge
- [ ] Test place order functionality

---

## Configuration Instructions

### Setting Up Gateway Charge

1. Navigate to: Admin Panel → Configuration → Sales → Payment Methods
2. Select a payment method (e.g., Cash on Delivery)
3. Find "Gateway Charge (%)" field
4. Enter percentage (0-100), e.g., 2.5 for 2.5%
5. Click "Save Configuration"
6. Gateway charge will be calculated on checkout based on cart subtotal

### Example Calculation

- Cart Subtotal: $100
- Gateway Charge: 2.5%
- Gateway Charge Amount: $100 × 2.5% = $2.50
- Grand Total: $100 + $2.50 + (shipping) + (tax) = Total
