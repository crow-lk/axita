<?php

namespace Webkul\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;

/**
 * Payzy Payment Controller
 * 
 * Handles Payzy payment gateway integration for Bagisto.
 */
class PayzyController extends Controller
{
    /**
     * Payzy API endpoint.
     */
    const PAYZY_API_URL = 'https://api.payzy.lk/checkout/custom-checkout';

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(protected OrderRepository $orderRepository)
    {
    }

    /**
     * Process payment.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process()
    {
        $cart = Cart::getCart();

        if (! $cart) {
            Log::error('PAYZY: Cart not found');
            session()->flash('error', 'Cart not found.');
            return redirect()->route('shop.checkout.cart.index');
        }

        Cart::collectTotals();
        Cart::refreshCart();

        $cart = Cart::getCart();

        // Get Payzy configuration
        $shopId = core()->getConfigData('sales.payment_methods.payzy.shop_id');
        $secretKey = core()->getConfigData('sales.payment_methods.payzy.secret_key');
        $testMode = core()->getConfigData('sales.payment_methods.payzy.sandbox') ? 'on' : 'off';

        if (! $shopId || ! $secretKey) {
            Log::error('PAYZY: Configuration missing');
            session()->flash('error', 'Payzy payment gateway is not properly configured.');
            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            // Get billing and shipping addresses
            $billingAddress = $cart->billing_address;
            $shippingAddress = $cart->shipping_address ?? $billingAddress;

            // Generate unique order ID
            $orderId = 'ORDER-' . $cart->id . '-' . time();

            // Helper function to get string value
            $getValue = function($value, $default = '') {
                if (is_array($value)) {
                    return implode(' ', $value);
                }
                return (string) ($value ?? $default);
            };

            // Prepare payment data
            $paymentData = [
                'x_test_mode'           => $testMode,
                'x_shopid'              => (string) $shopId,
                'x_amount'              => number_format($cart->grand_total, 2, '.', ''),
                'x_order_id'            => $orderId,
                'x_response_url'        => config('app.url') . '/payzy/success',
                'x_first_name'          => $getValue($billingAddress->first_name),
                'x_last_name'           => $getValue($billingAddress->last_name),
                'x_company'             => $getValue($billingAddress->company_name),
                'x_address'             => $getValue($billingAddress->address),
                'x_country'             => $getValue($billingAddress->country),
                'x_state'               => $getValue($billingAddress->state),
                'x_city'                => $getValue($billingAddress->city),
                'x_zip'                 => $getValue($billingAddress->postcode),
                'x_phone'               => $getValue($billingAddress->phone),
                'x_email'               => $getValue($billingAddress->email),
                'x_ship_to_first_name'  => $getValue($shippingAddress->first_name),
                'x_ship_to_last_name'   => $getValue($shippingAddress->last_name),
                'x_ship_to_company'     => $getValue($shippingAddress->company_name),
                'x_ship_to_address'     => $getValue($shippingAddress->address),
                'x_ship_to_country'     => $getValue($shippingAddress->country),
                'x_ship_to_state'       => $getValue($shippingAddress->state),
                'x_ship_to_city'        => $getValue($shippingAddress->city),
                'x_ship_to_zip'         => $getValue($shippingAddress->postcode),
                'x_freight'             => number_format($cart->selected_shipping_rate->price ?? 0, 2, '.', ''),
                'x_platform'            => 'custom',
                'x_version'             => '1.0',
                'signed_field_names'    => 'x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names',
            ];

            // Generate signature
            $signature = $this->generateSignature($paymentData, $secretKey);
            $paymentData['signature'] = $signature;

            // Store payment data in session for verification
            session()->put('payzy_order_data', $paymentData);
            session()->put('payzy_cart_id', $cart->id);

            // Make API request to Payzy
            $response = Http::post(self::PAYZY_API_URL, $paymentData);

            if ($response->successful()) {
                $data = $response->json();
                
                // Redirect to Payzy payment page
                if (isset($data['url'])) {
                    return redirect($data['url']);
                } elseif (isset($data['data']['url'])) {
                    return redirect($data['data']['url']);
                } else {
                    Log::error('PAYZY: API response missing URL', ['response' => $data]);
                }
            }

            // If payment initialization failed
            Log::error('PAYZY: Payment initialization failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            session()->flash('error', 'Unable to process payment. Please try again.');
            
            return redirect()->route('shop.checkout.cart.index');
        } catch (\Exception $e) {
            Log::error('PAYZY: Exception occurred', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            session()->flash('error', 'Payment error: ' . $e->getMessage());
            
            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle payment success callback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        $orderId = $request->input('x_order_id');
        $responseCode = $request->input('response_code');
        $signature = $request->input('signature');

        // Get stored payment data
        $storedData = session()->get('payzy_order_data');
        $cartId = session()->get('payzy_cart_id');

        if (! $storedData) {
            Log::error('PAYZY: Payment session expired');
            session()->flash('error', 'Payment session expired.');
            return redirect()->route('shop.checkout.cart.index');
        }

        // Verify payment signature
        if ($this->verifyPaymentSignature($request, $storedData)) {
            // Check if payment is successful (response_code = 00)
            if ($responseCode === '00') {
                Cart::collectTotals();
                Cart::refreshCart();

                $cart = Cart::getCart();

                if ($cart && $cart->id == $cartId) {
                    // Prepare order data using OrderResource
                    $data = (new OrderResource($cart))->jsonSerialize();
                    
                    // Create order
                    $order = $this->orderRepository->create($data);

                    // Update order payment info
                    $order->payment->update([
                        'additional' => [
                            'payzy_order_id' => $orderId,
                            'response_code' => $responseCode,
                        ],
                    ]);

                    Cart::deActivateCart();
                    
                    session()->forget('payzy_order_data');
                    session()->forget('payzy_cart_id');

                    session()->flash('success', trans('shop::app.checkout.success.message'));

                    return redirect()->route('shop.checkout.onepage.success');
                } else {
                    Log::error('PAYZY: Cart mismatch or not found');
                    session()->flash('error', 'Cart not found or expired.');
                }
            } else {
                Log::warning('PAYZY: Payment failed', ['response_code' => $responseCode]);
                session()->flash('error', 'Payment failed. Response code: ' . $responseCode);
            }
        } else {
            Log::error('PAYZY: Signature verification failed');
            session()->flash('error', 'Payment verification failed. Invalid signature.');
        }
        
        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle payment cancellation.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        session()->forget('payzy_order_data');
        session()->forget('payzy_cart_id');
        session()->flash('warning', 'Payment was cancelled.');
        
        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Generate HMAC SHA256 signature.
     *
     * @param  array  $data
     * @param  string  $secretKey
     * @return string
     */
    protected function generateSignature(array $data, string $secretKey): string
    {
        $signedFields = explode(',', $data['signed_field_names']);
        $dataString = '';

        foreach ($signedFields as $field) {
            $value = $data[$field] ?? '';
            
            // PayZY documentation has x_version without = sign
            if ($field === 'x_version') {
                $dataString .= $field . $value . ',';
            } else {
                $dataString .= $field . '=' . $value . ',';
            }
        }

        // Remove trailing comma
        $dataString = rtrim($dataString, ',');

        // Generate HMAC SHA256 hash and convert to Base64
        $hash = hash_hmac('sha256', $dataString, $secretKey, true);
        $signature = base64_encode($hash);
        
        return $signature;
    }

    /**
     * Verify payment signature from Payzy response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $storedData
     * @return bool
     */
    protected function verifyPaymentSignature(Request $request, array $storedData): bool
    {
        $receivedSignature = $request->input('signature');
        $responseCode = $request->input('response_code');
        $secretKey = core()->getConfigData('sales.payment_methods.payzy.secret_key');

        // Replace spaces with + in signature (URL encoding issue)
        $receivedSignature = str_replace(' ', '+', $receivedSignature);

        // Prepare data for verification
        $verificationData = [
            'response_code'         => $responseCode,
            'x_test_mode'           => $storedData['x_test_mode'] ?? '',
            'x_shopid'              => $storedData['x_shopid'] ?? '',
            'x_amount'              => $storedData['x_amount'] ?? '',
            'x_order_id'            => $storedData['x_order_id'] ?? '',
            'x_response_url'        => $storedData['x_response_url'] ?? '',
            'x_first_name'          => $storedData['x_first_name'] ?? '',
            'x_last_name'           => $storedData['x_last_name'] ?? '',
            'x_company'             => $storedData['x_company'] ?? '',
            'x_address'             => $storedData['x_address'] ?? '',
            'x_country'             => $storedData['x_country'] ?? '',
            'x_state'               => $storedData['x_state'] ?? '',
            'x_city'                => $storedData['x_city'] ?? '',
            'x_zip'                 => $storedData['x_zip'] ?? '',
            'x_phone'               => $storedData['x_phone'] ?? '',
            'x_email'               => $storedData['x_email'] ?? '',
            'x_ship_to_first_name'  => $storedData['x_ship_to_first_name'] ?? '',
            'x_ship_to_last_name'   => $storedData['x_ship_to_last_name'] ?? '',
            'x_ship_to_company'     => $storedData['x_ship_to_company'] ?? '',
            'x_ship_to_address'     => $storedData['x_ship_to_address'] ?? '',
            'x_ship_to_country'     => $storedData['x_ship_to_country'] ?? '',
            'x_ship_to_state'       => $storedData['x_ship_to_state'] ?? '',
            'x_ship_to_city'        => $storedData['x_ship_to_city'] ?? '',
            'x_ship_to_zip'         => $storedData['x_ship_to_zip'] ?? '',
            'x_freight'             => $storedData['x_freight'] ?? '',
            'x_platform'            => $storedData['x_platform'] ?? '',
            'x_version'             => $storedData['x_version'] ?? '',
            'signed_field_names'    => 'response_code,x_test_mode,x_shopid,x_amount,x_order_id,x_response_url,x_first_name,x_last_name,x_company,x_address,x_country,x_state,x_city,x_zip,x_phone,x_email,x_ship_to_first_name,x_ship_to_last_name,x_ship_to_company,x_ship_to_address,x_ship_to_country,x_ship_to_state,x_ship_to_city,x_ship_to_zip,x_freight,x_platform,x_version,signed_field_names',
        ];

        // Generate signature for verification
        $calculatedSignature = $this->generateSignature($verificationData, $secretKey);

        return $calculatedSignature === $receivedSignature;
    }
}
