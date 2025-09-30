<?php

// Include the Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';

// Start the kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Webkul\Product\Models\ProductFlat;
use Illuminate\Support\Facades\DB;

echo "Checking inventory/quantity for Acer SD100 16GB RAM DDR4 product...\n\n";

// Find the product first
$product = ProductFlat::where('sku', 'AC000002')->first();

if ($product) {
    echo "Product Found:\n";
    echo "Name: " . $product->name . "\n";
    echo "SKU: " . $product->sku . "\n";
    echo "Status: " . ($product->status ? 'Active (Salable)' : 'Inactive (Not Salable)') . "\n\n";
    
    // Check product inventory from product_inventories table
    $inventories = DB::table('product_inventories')
                    ->where('product_id', $product->product_id)
                    ->get();
    
    if ($inventories->count() > 0) {
        echo "Inventory Information:\n";
        $totalQty = 0;
        foreach ($inventories as $inventory) {
            echo "  - Inventory Source ID: " . $inventory->inventory_source_id . "\n";
            echo "    Quantity: " . $inventory->qty . "\n";
            echo "    Vendor ID: " . ($inventory->vendor_id ?? 'Default') . "\n";
            $totalQty += $inventory->qty;
            echo "    ---\n";
        }
        echo "Total Available Quantity: " . $totalQty . "\n\n";
    } else {
        echo "No inventory records found.\n\n";
    }
    
    // Check product inventory indices (aggregated inventory)
    $inventoryIndices = DB::table('product_inventory_indices')
                         ->where('product_id', $product->product_id)
                         ->get();
    
    if ($inventoryIndices->count() > 0) {
        echo "Inventory Indices (Per Channel):\n";
        foreach ($inventoryIndices as $index) {
            echo "  - Channel ID: " . $index->channel_id . "\n";
            echo "    Available Quantity: " . $index->qty . "\n";
            echo "    Last Updated: " . $index->updated_at . "\n";
            echo "    ---\n";
        }
    } else {
        echo "No inventory indices found.\n";
    }
    
    // Check ordered inventories (reserved/allocated stock)
    $orderedInventories = DB::table('product_ordered_inventories')
                           ->where('product_id', $product->product_id)
                           ->get();
    
    if ($orderedInventories->count() > 0) {
        echo "\nOrdered/Reserved Stock:\n";
        foreach ($orderedInventories as $ordered) {
            echo "  - Channel ID: " . $ordered->channel_id . "\n";
            echo "    Reserved Quantity: " . $ordered->qty . "\n";
            echo "    ---\n";
        }
    }
    
} else {
    echo "Product not found with SKU: AC000002\n";
}