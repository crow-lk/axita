<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use Webkul\Product\Models\Product;

try {
    // Search for the Acer RAM product
    $product = Product::where('name', 'LIKE', '%Acer%')
                     ->orWhere('name', 'LIKE', '%BL.9BWWA.206%')
                     ->orWhere('name', 'LIKE', '%SD100%')
                     ->orWhere('sku', 'LIKE', '%BL.9BWWA.206%')
                     ->first();

    if ($product) {
        echo "Product found!\n";
        echo "Name: " . $product->name . "\n";
        echo "SKU: " . $product->sku . "\n";
        echo "Status: " . $product->status . "\n";
        echo "Type: " . $product->type . "\n";
        
        // Check if the product is salable (status = 1 means active/salable)
        if ($product->status == 1) {
            echo "Is Salable: Yes (Status: Active)\n";
        } else {
            echo "Is Salable: No (Status: Inactive)\n";
        }
    } else {
        echo "Product not found. Let me search for all products containing 'RAM' or '16GB':\n\n";
        
        $ramProducts = Product::where('name', 'LIKE', '%RAM%')
                             ->orWhere('name', 'LIKE', '%16GB%')
                             ->orWhere('name', 'LIKE', '%DDR4%')
                             ->get();
        
        if ($ramProducts->count() > 0) {
            echo "Found " . $ramProducts->count() . " RAM-related products:\n";
            foreach ($ramProducts as $ramProduct) {
                echo "- Name: " . $ramProduct->name . " | SKU: " . $ramProduct->sku . " | Status: " . ($ramProduct->status ? 'Active' : 'Inactive') . "\n";
            }
        } else {
            echo "No RAM-related products found in the database.\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}