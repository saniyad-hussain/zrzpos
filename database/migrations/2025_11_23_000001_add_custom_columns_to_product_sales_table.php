<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_sales', function (Blueprint $table) {
            $table->integer('product_id')->nullable()->change();
            $table->boolean('is_custom')->default(false)->after('variant_id');
            $table->string('custom_name')->nullable()->after('is_custom');
            $table->string('custom_code')->nullable()->after('custom_name');
            $table->string('custom_unit')->nullable()->after('custom_code');
            $table->unsignedBigInteger('custom_tax_id')->nullable()->after('custom_unit');
            $table->decimal('custom_tax_rate', 15, 4)->nullable()->after('custom_tax_id');
            $table->string('custom_tax_name')->nullable()->after('custom_tax_rate');
            $table->string('custom_tax_method')->nullable()->after('custom_tax_name');
        });
    }

    public function down(): void
    {
        Schema::table('product_sales', function (Blueprint $table) {
            $table->dropColumn([
                'is_custom',
                'custom_name',
                'custom_code',
                'custom_unit',
                'custom_tax_id',
                'custom_tax_rate',
                'custom_tax_name',
                'custom_tax_method',
            ]);
            $table->integer('product_id')->nullable(false)->change();
        });
    }
};
