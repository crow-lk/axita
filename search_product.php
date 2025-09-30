<?php

// Include the Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';

// Start the kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Now we can use Eloquent models
use Webkul\Product\Models\Product;

echo "Searching for Acer SD100 16GB RAM DDR4 product...\n\n";

// Search for the specific product
$searchTerms = ['Acer', 'SD100', 'BL.9BWWA.206', '16GB', 'DDR4'];

foreach ($searchTerms as $term) {
    echo "Searching for term: '$term'\n";
    
    $products = Product::where('name', 'LIKE', "%{$term}%")
                      ->orWhere('sku', 'LIKE', "%{$term}%")
                      ->orWhere('short_description', 'LIKE', "%{$term}%")
                      ->orWhere('description', 'LIKE', "%{$term}%")
                      ->get();
    
    if ($products->count() > 0) {
        echo "Found " . $products->count() . " product(s) matching '$term':\n";
        foreach ($products as $product) {
            echo "  - Name: " . $product->name . "\n";
            echo "    SKU: " . $product->sku . "\n";
            echo "    Status: " . ($product->status ? 'Active (Salable)' : 'Inactive (Not Salable)') . "\n";
            echo "    Type: " . $product->type . "\n";
            echo "    ---\n";
        }
    } else {
        echo "No products found for '$term'\n";
    }
    echo "\n";
}

// If exact product not found, let's check for RAM products
echo "Checking for RAM-related products:\n\n";
$ramProducts = Product::where(function($query) {
    $query->where('name', 'LIKE', '%RAM%')
          ->orWhere('name', 'LIKE', '%Memory%')
          ->orWhere('name', 'LIKE', '%DDR4%')
          ->orWhere('name', 'LIKE', '%16GB%');
})->limit(10)->get();

if ($ramProducts->count() > 0) {
    echo "Found " . $ramProducts->count() . " RAM-related products:\n";
    foreach ($ramProducts as $product) {
        echo "  - Name: " . $product->name . "\n";
        echo "    SKU: " . $product->sku . "\n";
        echo "    Status: " . ($product->status ? 'Active (Salable)' : 'Inactive (Not Salable)') . "\n";
        echo "    ---\n";
    }
} else {
    echo "No RAM-related products found.\n";
}