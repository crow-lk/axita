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
use Illuminate\Support\Facades\Route;
use Webkul\Sales\Repositories\OrderRepository;

Route::any('/payhere', function (Request $request, OrderRepository $orderRepository) {
	// TODO: Validate the request
	$merchant_id         = $_POST['merchant_id'];
$order_id            = $_POST['order_id'];
$payhere_amount      = $_POST['payhere_amount'];
$payhere_currency    = $_POST['payhere_currency'];
$status_code         = $_POST['status_code'];
$md5sig              = $_POST['md5sig'];

$merchant_secret = 'NTU1MTcxOTU2MzU4MzQ3MTQ1NzI4NDE3NTQ2NTczNTMxNTE3MDAz'; // Replace with your Merchant Secret

$local_md5sig = strtoupper(
    md5(
        $merchant_id . 
        $order_id . 
        $payhere_amount . 
        $payhere_currency . 
        $status_code . 
        strtoupper(md5($merchant_secret)) 
    ) 
);
       
if (($local_md5sig === $md5sig) AND ($status_code == 2) ){
	$order = $orderRepository->findOneByField(['cart_id' => $request->order_id]);
	$orderRepository->update(['status' => 'processing'], $order->id);

	if ($this->order->canInvoice()) {
		$invoice = $this->invoiceRepository->create($this->prepareInvoiceData());
	}
}
});

Route::any('/payhere', function (Request $request) {
	$merchant_id         = $_POST['merchant_id'];
$order_id            = $_POST['order_id'];
$payhere_amount      = $_POST['payhere_amount'];
$payhere_currency    = $_POST['payhere_currency'];
$status_code         = $_POST['status_code'];
$md5sig              = $_POST['md5sig'];

$merchant_secret = 'NTU1MTcxOTU2MzU4MzQ3MTQ1NzI4NDE3NTQ2NTczNTMxNTE3MDAz'; // Replace with your Merchant Secret

$local_md5sig = strtoupper(
    md5(
        $merchant_id . 
        $order_id . 
        $payhere_amount . 
        $payhere_currency . 
        $status_code . 
        strtoupper(md5($merchant_secret)) 
    ) 
);
       
if (($local_md5sig === $md5sig) AND ($status_code == 2) ){
        
}
});