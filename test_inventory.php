<?php

// Include the Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';

// Start the kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Webkul\Product\Models\ProductFlat;

echo "Testing Product inventory access...\n\n";

// Find the Acer RAM product
$product = ProductFlat::where('sku', 'AC000002')->first();

if ($product) {
    echo "Found Product: " . $product->name . "\n";
    echo "Product ID: " . $product->product_id . "\n";
    
    // Get the actual product model
    $actualProduct = $product->product;
    
    if ($actualProduct) {
        echo "Actual Product Type: " . get_class($actualProduct) . "\n";
        
        // Get inventories
        $inventories = $actualProduct->inventories;
        echo "Number of inventory records: " . $inventories->count() . "\n";
        
        $totalQuantity = $inventories->sum('qty');
        echo "Total Quantity: " . $totalQuantity . "\n";
        
        foreach ($inventories as $inventory) {
            echo "  - Source ID: " . $inventory->inventory_source_id . " | Qty: " . $inventory->qty . "\n";
        }
        
        // Test the method we added
        if (method_exists($actualProduct, 'inventories')) {
            echo "Inventories method exists: Yes\n";
        } else {
            echo "Inventories method exists: No\n";
        }
        
    } else {
        echo "Could not get actual product model\n";
    }
    
} else {
    echo "Product not found with SKU: AC000002\n";
}