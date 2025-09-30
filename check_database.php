<?php

// Include the Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';

// Start the kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check available tables and columns
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Checking database structure...\n\n";

// Get all tables that contain 'product'
$tables = DB::select("SHOW TABLES LIKE '%product%'");
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    echo "Table: $tableName\n";
    
    // Get columns for this table
    $columns = Schema::getColumnListing($tableName);
    echo "Columns: " . implode(', ', $columns) . "\n\n";
}

// Try to use ProductFlat model instead
try {
    $productFlatClass = '\Webkul\Product\Models\ProductFlat';
    if (class_exists($productFlatClass)) {
        echo "Using ProductFlat model...\n\n";
        
        // Search for the specific product using ProductFlat
        $searchTerms = ['Acer', 'SD100', 'BL.9BWWA.206', '16GB', 'DDR4'];
        
        foreach ($searchTerms as $term) {
            echo "Searching for term: '$term'\n";
            
            $products = $productFlatClass::where('name', 'LIKE', "%{$term}%")
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
                    echo "    ---\n";
                }
            } else {
                echo "No products found for '$term'\n";
            }
            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "Error with ProductFlat: " . $e->getMessage() . "\n";
}