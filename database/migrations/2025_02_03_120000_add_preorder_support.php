<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_flat', function (Blueprint $table) {
            if (! Schema::hasColumn('product_flat', 'preorder')) {
                $table->boolean('preorder')->nullable()->after('featured');
            }
        });

        $now = now();

        $attributeId = DB::table('attributes')->where('code', 'preorder')->value('id');

        if (! $attributeId) {
            $attributeId = DB::table('attributes')->insertGetId([
                'code'                => 'preorder',
                'admin_name'          => 'Preorder',
                'type'                => 'boolean',
                'swatch_type'         => null,
                'validation'          => null,
                'regex'               => null,
                'position'            => 8,
                'is_required'         => 0,
                'is_unique'           => 0,
                'is_filterable'       => 0,
                'is_comparable'       => 0,
                'is_configurable'     => 0,
                'is_user_defined'     => 0,
                'is_visible_on_front' => 0,
                'value_per_locale'    => 0,
                'value_per_channel'   => 0,
                'default_value'       => null,
                'enable_wysiwyg'      => 0,
                'created_at'          => $now,
                'updated_at'          => $now,
            ]);
        }

        // Attach the attribute to all Settings groups so it appears with other toggles.
        $settingsGroups = DB::table('attribute_groups')
            ->where('name', 'Settings')
            ->pluck('id');

        foreach ($settingsGroups as $groupId) {
            $alreadyMapped = DB::table('attribute_group_mappings')
                ->where('attribute_group_id', $groupId)
                ->where('attribute_id', $attributeId)
                ->exists();

            if ($alreadyMapped) {
                continue;
            }

            $nextPosition = (int) DB::table('attribute_group_mappings')
                ->where('attribute_group_id', $groupId)
                ->max('position');

            DB::table('attribute_group_mappings')->insert([
                'attribute_group_id' => $groupId,
                'attribute_id'       => $attributeId,
                'position'           => $nextPosition + 1,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_flat', function (Blueprint $table) {
            if (Schema::hasColumn('product_flat', 'preorder')) {
                $table->dropColumn('preorder');
            }
        });

        $attributeId = DB::table('attributes')->where('code', 'preorder')->value('id');

        if ($attributeId) {
            DB::table('attribute_group_mappings')
                ->where('attribute_id', $attributeId)
                ->delete();

            DB::table('attributes')
                ->where('id', $attributeId)
                ->delete();
        }
    }
};
