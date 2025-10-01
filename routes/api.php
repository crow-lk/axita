<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;

Route::post('/payhere', function (
    Request $request,
    OrderRepository $orderRepository,
    InvoiceRepository $invoiceRepository
) {
    $merchantId      = $request->input('merchant_id');
    $orderId         = $request->input('order_id');
    $payhereAmount   = $request->input('payhere_amount');
    $payhereCurrency = $request->input('payhere_currency');
    $statusCode      = $request->input('status_code');
    $md5sig          = $request->input('md5sig');

    // TODO: store in config/env and read from there
    $merchantSecret = 'NTU1MTcxOTU2MzU4MzQ3MTQ1NzI4NDE3NTQ2NTczNTMxNTE3MDAz';

    $localSig = strtoupper(md5(
        $merchantId .
        $orderId .
        $payhereAmount .
        $payhereCurrency .
        $statusCode .
        strtoupper(md5($merchantSecret))
    ));

    Log::info('PayHere IPN received', [
        'orderId' => $orderId,
        'status'  => $statusCode,
        'valid'   => $localSig === $md5sig,
    ]);

    if ($localSig !== $md5sig) {
        return response('Invalid signature', 400);
    }

    // PayHere: 2 = success
    if ((int) $statusCode !== 2) {
        return response('Ignored', 200);
    }

    try {
        // Your Standard.php used order_id like 'axita'.$cart->id
        // Extract internal cart id (strip non-digits) and locate order by cart_id
        $cartId = (int) preg_replace('/\D+/', '', (string) $orderId);

        $order = $orderRepository->findOneByField(['cart_id' => $cartId]);

        if (! $order) {
            Log::warning('PayHere IPN order not found', ['cartId' => $cartId, 'rawOrderId' => $orderId]);
            return response('Order not found', 404);
        }

        // Build invoice data: invoice per all items to invoice
        $invoiceData = ['order_id' => $order->id, 'invoice' => ['items' => []]];
        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        if ($order->canInvoice()) {
            $invoiceRepository->create($invoiceData, 'paid', 'processing');
        }

        // Ensure order is at least processing
        $orderRepository->update(['status' => 'processing'], $order->id);

        return response('OK', 200);
    } catch (\Throwable $e) {
        Log::error('PayHere IPN error', ['error' => $e->getMessage()]);
        return response('Server error', 500);
    }
});