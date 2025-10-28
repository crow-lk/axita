<?php

namespace Webkul\Shipping\Carriers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;

class FlatRate extends AbstractShipping
{
    /**
     * Shipping method carrier code.
     *
     * @var string
     */
    protected $code = 'flatrate';

    /**
     * Shipping method code.
     *
     * @var string
     */
    protected $method = 'flatrate_flatrate';

    /**
     * Calculate rate for flatrate.
     *
     * @return \Webkul\Checkout\Models\CartShippingRate|false
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        return $this->getRate();
    }

    /**
     * Get rate.
     */
    public function getRate(): CartShippingRate
    {
        $cart = Cart::getCart();

        $cartShippingRate = new CartShippingRate;

        $cartShippingRate->carrier = $this->getCode();
        $cartShippingRate->carrier_title = $this->getConfigData('title');
        $cartShippingRate->method = $this->getMethod();
        $cartShippingRate->method_title = $this->getConfigData('title');
        $cartShippingRate->method_description = $this->getConfigData('description');
        $cartShippingRate->price = 0;
        $cartShippingRate->base_price = 0;

        $defaultRate = (float) ($this->getConfigData('default_rate') ?? 0);

        if ($defaultRate <= 0) {
            $defaultRate = 500;
        }

        $calculationType = $this->getConfigData('type') ?: 'per_order';

        if ($calculationType === 'per_unit') {
            foreach ($cart->items as $item) {
                if ($item->getTypeInstance()->isStockable()) {
                    $cartShippingRate->price += core()->convertPrice($defaultRate) * $item->quantity;
                    $cartShippingRate->base_price += $defaultRate * $item->quantity;
                }
            }
        } else {
            $cartShippingRate->price = core()->convertPrice($defaultRate);
            $cartShippingRate->base_price = $defaultRate;
        }

        return $cartShippingRate;
    }
}
