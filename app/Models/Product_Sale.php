<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_Sale extends Model
{
	protected $table = 'product_sales';
    protected $fillable =[
        "sale_id", "product_id", "product_batch_id", "variant_id", "is_custom", "custom_name", "custom_code", "custom_unit", "custom_tax_id", "custom_tax_rate", "custom_tax_name", "custom_tax_method", 'imei_number', "qty", "return_qty", "sale_unit_id", "net_unit_price", "discount", "tax_rate", "tax", "total", "is_packing", "is_delivered","topping_id"
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'custom_tax_rate' => 'decimal:4',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
