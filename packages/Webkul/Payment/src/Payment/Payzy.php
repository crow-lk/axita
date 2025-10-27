<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Storage;

class Payzy extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'payzy';

    /**
     * Get redirect url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('payzy.process');
    }

    /**
     * Is available.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->getConfigData('active');
    }

    /**
     * Get payment method image.
     *
     * @return string|null
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        if ($url) {
            return Storage::url($url);
        }

        // Return null if no image is configured to avoid errors
        return null;
    }
}
