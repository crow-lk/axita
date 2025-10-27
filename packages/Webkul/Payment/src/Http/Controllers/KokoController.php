<?php

namespace Webkul\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;

class KokoController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected OrderRepository $orderRepository) {}

    /**
     * Initiate the KOKO payment workflow.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process()
    {
        $cart = Cart::getCart();

        if (! $cart) {
            Log::warning('KOKO: Missing cart instance during payment initiation.');

            session()->flash('error', 'Cart not found.');

            return redirect()->route('shop.checkout.cart.index');
        }

        Cart::collectTotals();
        Cart::refreshCart();

        $cart = Cart::getCart();

        $merchantId = core()->getConfigData('sales.payment_methods.koko.merchant_id');
        $apiKey = core()->getConfigData('sales.payment_methods.koko.api_key');
        $sandbox = (bool) core()->getConfigData('sales.payment_methods.koko.sandbox');

        $endpoint = $sandbox
            ? core()->getConfigData('sales.payment_methods.koko.sandbox_api_url')
            : core()->getConfigData('sales.payment_methods.koko.production_api_url');

        if (! $merchantId || ! $apiKey || ! $endpoint) {
            Log::error('KOKO: Missing configuration', [
                'merchantId' => $merchantId ? 'provided' : 'missing',
                'apiKey'     => $apiKey ? 'provided' : 'missing',
                'endpoint'   => $endpoint ? 'provided' : 'missing',
            ]);

            session()->flash('error', 'KOKO payment will be available soon. Please try again later.');

            return redirect()->route('shop.checkout.cart.index');
        }

        $billingAddress = $cart->billing_address;
        $shippingAddress = $cart->shipping_address ?? $billingAddress;

        $orderId = 'KOKO-' . $cart->id . '-' . time();

        $amount = number_format((float) $cart->grand_total, 2, '.', '');

        $payload = [
            'merchant_id'   => (string) $merchantId,
            'order_id'      => $orderId,
            'amount'        => $amount,
            'currency'      => core()->getCurrentCurrencyCode() ?: core()->getBaseCurrencyCode(),
            'customer_name' => trim($billingAddress->first_name . ' ' . $billingAddress->last_name),
            'customer_email'=> (string) $billingAddress->email,
            'customer_phone'=> (string) $billingAddress->phone,
            'billing_address' => [
                'line1'   => Arr::first((array) $billingAddress->address),
                'city'    => (string) $billingAddress->city,
                'state'   => (string) $billingAddress->state,
                'country' => (string) $billingAddress->country,
                'zip'     => (string) $billingAddress->postcode,
            ],
            'shipping_address' => [
                'line1'   => Arr::first((array) $shippingAddress->address),
                'city'    => (string) $shippingAddress->city,
                'state'   => (string) $shippingAddress->state,
                'country' => (string) $shippingAddress->country,
                'zip'     => (string) $shippingAddress->postcode,
            ],
            'success_url'   => route('koko.success'),
            'cancel_url'    => route('koko.cancel'),
        ];

        $items = [];
        foreach ($cart->items as $item) {
            $items[] = [
                'name'     => $item->name,
                'sku'      => $item->sku,
                'quantity' => (int) $item->quantity,
                'price'    => number_format((float) $item->total, 2, '.', ''),
            ];
        }

        if (! empty($items)) {
            $payload['items'] = $items;
        }

        $http = Http::withToken($apiKey)
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'X-KOKO-Merchant' => $merchantId,
            ]);

        try {
            $response = $http->post($endpoint, $payload);
        } catch (\Throwable $exception) {
            Log::error('KOKO: Failed to reach API', [
                'message' => $exception->getMessage(),
            ]);

            session()->flash('error', 'Unable to connect to KOKO payment service. Please try again later.');

            return redirect()->route('shop.checkout.cart.index');
        }

        if ($response->failed()) {
            Log::error('KOKO: API responded with error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            session()->flash('error', 'KOKO payment initialization failed. Please try again.');

            return redirect()->route('shop.checkout.cart.index');
        }

        $data = $response->json();

        $redirectUrl = data_get($data, 'redirect_url')
            ?? data_get($data, 'data.redirect_url')
            ?? data_get($data, 'payment_url')
            ?? data_get($data, 'data.payment_url');

        if (! $redirectUrl) {
            Log::error('KOKO: Redirect URL missing in API response', ['response' => $data]);

            session()->flash('error', 'KOKO payment could not be started. Please contact support.');

            return redirect()->route('shop.checkout.cart.index');
        }

        session()->put('koko_session', [
            'cart_id'        => $cart->id,
            'order_id'       => $orderId,
            'amount'         => $amount,
            'sandbox'        => $sandbox,
            'endpoint'       => $endpoint,
            'payload'        => $payload,
            'created_at'     => now()->toIso8601String(),
        ]);

        return redirect($redirectUrl);
    }

    /**
     * Handle success response from KOKO.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        $session = session()->get('koko_session');

        if (! $session) {
            Log::warning('KOKO: Success callback without session context.', ['query' => $request->all()]);

            session()->flash('error', 'Payment session expired. Please try again.');

            return redirect()->route('shop.checkout.cart.index');
        }

        $status = strtolower((string) $request->input('status', ''));
        $paymentStatus = strtolower((string) $request->input('payment_status', ''));
        $statusCode = (string) $request->input('status_code');

        $successfulStatuses = ['success', 'successful', 'paid', 'completed', 'approved'];

        $isSuccessful = in_array($status, $successfulStatuses, true)
            || in_array($paymentStatus, $successfulStatuses, true)
            || in_array(strtolower((string) $request->input('state', '')), $successfulStatuses, true)
            || $statusCode === '200'
            || $request->boolean('success');

        if (! $isSuccessful) {
            Log::warning('KOKO: Payment reported failure.', [
                'status'         => $status,
                'payment_status' => $paymentStatus,
                'status_code'    => $statusCode,
                'query'          => $request->all(),
            ]);

            session()->forget('koko_session');
            session()->flash('error', 'Payment was not approved. Please try again or use a different method.');

            return redirect()->route('shop.checkout.cart.index');
        }

        Cart::collectTotals();
        Cart::refreshCart();

        $cart = Cart::getCart();

        if (! $cart || $cart->id !== $session['cart_id']) {
            Log::error('KOKO: Cart mismatch during success handling.', [
                'expected_cart_id' => $session['cart_id'] ?? null,
                'actual_cart_id'   => $cart?->id,
            ]);

            session()->forget('koko_session');
            session()->flash('error', 'Unable to locate your cart for order creation. Please contact support.');

            return redirect()->route('shop.checkout.cart.index');
        }

        $orderData = (new OrderResource($cart))->jsonSerialize();

        $order = $this->orderRepository->create($orderData);

        $order->payment->update([
            'additional' => array_filter([
                'koko_order_id'    => $session['order_id'] ?? null,
                'koko_reference'   => $request->input('reference') ?? $request->input('transaction_id'),
                'koko_status'      => $status ?: $paymentStatus,
                'koko_raw_payload' => $request->all(),
            ]),
        ]);

        Cart::deActivateCart();

        session()->forget('koko_session');
        session()->flash('success', trans('shop::app.checkout.success.message'));

        return redirect()->route('shop.checkout.onepage.success');
    }

    /**
     * Handle cancellation.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        session()->forget('koko_session');
        session()->flash('warning', 'KOKO payment was cancelled.');

        return redirect()->route('shop.checkout.cart.index');
    }
}
