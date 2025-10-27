<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Storage;

class Koko extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'koko';

    /**
     * Get redirect url.
     *
     * @return string|null
     */
    public function getRedirectUrl()
    {
        return route('koko.process');
    }

    /**
     * Determine availability.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return (bool) $this->getConfigData('active');
    }

    /**
     * Get payment method image.
     *
     * @return string|null
     */
    public function getImage()
    {
        $configuredImage = $this->getConfigData('image');

        if ($configuredImage) {
            return Storage::url($configuredImage);
        }

        if (Storage::disk('public')->exists('logos/kokologo.png')) {
            return Storage::url('logos/kokologo.png');
        }

        return null;
    }

    /**
     * Additional details shown on order view.
     *
     * @return array
     */
    public function getAdditionalDetails()
    {
        $instructions = $this->getConfigData('instructions');

        if (empty($instructions)) {
            return [];
        }

        return [
            'title' => trans('admin::app.configuration.index.sales.payment-methods.instructions'),
            'value' => $instructions,
        ];
    }
}
