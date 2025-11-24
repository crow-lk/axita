<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $attributeId = DB::table('attributes')->where('code', 'preorder')->value('id');

        if (! $attributeId) {
            return;
        }

        DB::table('attributes')
            ->where('id', $attributeId)
            ->update(['admin_name' => 'Pre Order']);

        $locales = DB::table('locales')->pluck('code')->toArray();

        foreach ($locales as $locale) {
            $exists = DB::table('attribute_translations')
                ->where('attribute_id', $attributeId)
                ->where('locale', $locale)
                ->exists();

            if (! $exists) {
                DB::table('attribute_translations')->insert([
                    'attribute_id' => $attributeId,
                    'locale'       => $locale,
                    'name'         => 'Pre Order',
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $attributeId = DB::table('attributes')->where('code', 'preorder')->value('id');

        if (! $attributeId) {
            return;
        }

        DB::table('attribute_translations')
            ->where('attribute_id', $attributeId)
            ->delete();
    }
};
