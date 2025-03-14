<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductProperty extends Model
{
    protected $table = 'product_properties';

    protected $fillable = [
        'product_id',
        'property_value_id',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo
     */
    public function propertyValue(): BelongsTo
    {
        return $this->belongsTo(PropertyValue::class);
    }
}
