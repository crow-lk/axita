<?php

// Include the Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';

// Start the kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Webkul\Shop\Http\Controllers\API\ProductController;
use Webkul\Shop\Http\Resources\ProductResource;
use Webkul\Product\Models\ProductFlat;
use Illuminate\Http\Request;

echo "Testing API Product response with quantity...\n\n";

// Create a mock request
$request = new Request([
    'sku' => 'AC000002',
    'limit' => 1
]);

app()->instance('request', $request);

// Find the product directly
$product = ProductFlat::where('sku', 'AC000002')->first();

if ($product) {
    echo "Testing ProductResource directly...\n";
    
    // Create the resource
    $resource = new ProductResource($product->product);
    
    // Manually create the array response (simulating toArray call)
    $productTypeInstance = $product->product->getTypeInstance();
    
    $data = [
        'id'          => $product->product->id,
        'sku'         => $product->product->sku,
        'name'        => $product->name,
        'is_saleable' => (bool) $productTypeInstance->isSaleable(),
        'quantity'    => $product->product->inventories()->sum('qty'),
    ];
    
    echo "Product Data:\n";
    echo "  ID: " . $data['id'] . "\n";
    echo "  SKU: " . $data['sku'] . "\n"; 
    echo "  Name: " . $data['name'] . "\n";
    echo "  Is Saleable: " . ($data['is_saleable'] ? 'Yes' : 'No') . "\n";
    echo "  Quantity: " . $data['quantity'] . "\n\n";
    
    // Test the stock status logic from our blade component
    if ($data['quantity'] <= 0) {
        echo "Stock Badge Style: background-color:#dc2626 (Red - Out of Stock)\n";
        echo "Stock Badge Text: Out of Stock\n";
    } elseif ($data['quantity'] <= 5) {
        echo "Stock Badge Style: background-color:#f59e0b (Orange - Low Stock)\n";
        echo "Stock Badge Text: Low Stock (" . $data['quantity'] . ")\n";
    } else {
        echo "Stock Badge Style: background-color:#16a34a (Green - In Stock)\n";
        echo "Stock Badge Text: In Stock\n";
    }
    
} else {
    echo "Product not found\n";
}