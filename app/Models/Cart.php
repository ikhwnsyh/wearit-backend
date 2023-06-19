<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function dataProduct()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function productSize()
    {
        return $this->belongsTo('App\Models\Size', 'size_id');
    }
}
