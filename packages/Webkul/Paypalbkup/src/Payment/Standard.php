<?php

namespace Webkul\Paypal\Payment;

class Standard extends Paypal
{
    protected $code = 'paypal_standard'; // keep same

    protected $merchantId = '1230054'; // Set your PayHere Merchant ID
    protected $merchantSecret = 'MjEzMjA0Njk4NjM0MjEzMjM5NTgyOTk0NTA4OTYxODQwNTE4MDAw'; // Set your PayHere Secret

    public function getRedirectUrl()
    {
        return route('paypal.standard.redirect'); // keep same
    }

    public function getIPNUrl()
    {
        // return route('paypal.standard.ipn'); // keep same
    }

    public function getFormFields()
    {
        $cart = $this->getCart();
        $amount = $this->formatCurrencyValue($cart->grand_total);
        $orderId = 'axita'.$cart->id;
        $currency = 'LKR';
        $hash = $this->generateHash($this->merchantId, $orderId, $amount, $currency, $this->merchantSecret);

        $billingAddress = $cart->billing_address;
        $formFields = [
            'merchant_id' => $this->merchantId,
            'return_url'  => 'https://axita.lk',
            'cancel_url'  => 'https://axita.lk',
            'notify_url'  => 'https://axita.lk/test',
            'order_id'    => $orderId,
            'items'       => 'Order #' . $orderId,
            'currency'    => $currency,
            'amount'      =>         number_format($amount, 2, '.', '') ,
            'first_name'  => $billingAddress->first_name ?? 'Guest',
            'last_name'   => $billingAddress->last_name ?? '',
            'email'       => $billingAddress->email ?? '',
            'phone'       => $this->formatPhone($billingAddress->phone ?? ''),
            'address'     => $billingAddress->address1 ?? '',
            'city'        => $billingAddress->city ?? '',
            'country'     => $billingAddress->country ?? 'Sri Lanka',
            'hash'        => $hash,
        ];

        \Log::info('PayHere Form Fields:', $formFields);

        return $formFields;
    }

    protected function generateHash($merchantId, $orderId, $amount, $currency, $merchantSecret)
    {
        \Log::debug('Generating hash with parameters:', [
            'merchantId' => $merchantId,
            'orderId'    => $orderId,
            'amount'     => number_format($amount, 2, '.', ''),
            'currency'   => $currency,
            'merchantSecret' => $merchantSecret,
        ]);
        return strtoupper(
            md5(
                $merchantId .
                $orderId .
                number_format($amount, 2, '.', '') .
                $currency .
                strtoupper(md5($merchantSecret))
            )
        );
    }

    public function getSuccessUrl()
    {
        // return route('paypal.standard.success');
    }

    public function getCancelUrl()
    {
        // return route('paypal.standard.cancel');
    }

    public function getCart()
    {
        return cart()->getCart();
    }

    public function getTitle()
    {
        return 'PayHere';  // Change title
    }

    public function getDescription()
    {
        return 'PayHere payments';
    }

    public function isActive()
    {
        return true;
    }
}
