<?php

// Include the Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';

// Start the kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Webkul\Shop\Http\Resources\ProductResource;
use Webkul\Product\Models\ProductFlat;

echo "Testing ProductResource with quantity information...\n\n";

// Find the Acer RAM product
$product = ProductFlat::where('sku', 'AC000002')->first();

if ($product) {
    echo "Found Product: " . $product->name . "\n";
    echo "Creating ProductResource...\n\n";
    
    // Create a ProductResource instance
    $resource = new ProductResource($product->product);
    $result = $resource->toArray(app('request'));
    
    // Display relevant fields including quantity
    echo "Product ID: " . $result['id'] . "\n";
    echo "Name: " . $result['name'] . "\n";
    echo "SKU: " . $result['sku'] . "\n";
    echo "Is Saleable: " . ($result['is_saleable'] ? 'Yes' : 'No') . "\n";
    echo "Quantity: " . ($result['quantity'] ?? 'Not available') . "\n";
    
    if (isset($result['quantity'])) {
        if ($result['quantity'] <= 0) {
            echo "Stock Status: Out of Stock\n";
        } elseif ($result['quantity'] <= 5) {
            echo "Stock Status: Low Stock (" . $result['quantity'] . " remaining)\n";
        } else {
            echo "Stock Status: In Stock\n";
        }
    }
} else {
    echo "Product not found with SKU: AC000002\n";
}