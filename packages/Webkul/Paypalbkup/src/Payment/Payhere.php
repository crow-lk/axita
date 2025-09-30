<?php

namespace Webkul\Paypal\Payment;

use Illuminate\Support\Facades\Storage;
use Webkul\Payment\Payment\Payment;

abstract class Payhere extends Payment
{
    /**
     * Format a currency value.
     */
    public function formatCurrencyValue($number): float
    {
        return round((float) $number, 2);
    }

    /**
     * Format phone number (remove special characters).
     */
    public function formatPhone($phone): string
    {
        return preg_replace('/[^0-9]/', '', (string) $phone);
    }

    /**
     * Get default PayHere image.
     */
    public function getImage()
    {
        return bagisto_asset('images/payhere.png', 'shop');
    }
}
