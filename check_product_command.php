<?php

use Illuminate\Console\Command;
use Webkul\Product\Models\Product;

class CheckProductCommand extends Command
{
    protected $signature = 'product:check {search}';
    protected $description = 'Check product salable status';

    public function handle()
    {
        $search = $this->argument('search');
        
        $product = Product::where('name', 'LIKE', "%{$search}%")
                         ->orWhere('sku', 'LIKE', "%{$search}%")
                         ->first();

        if ($product) {
            $this->info("Product Found:");
            $this->info("Name: " . $product->name);
            $this->info("SKU: " . $product->sku);
            $this->info("Status: " . ($product->status ? 'Active (Salable)' : 'Inactive (Not Salable)'));
            $this->info("Type: " . $product->type);
        } else {
            $this->error("Product not found with search term: {$search}");
        }
    }
}