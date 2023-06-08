<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function detailProduct()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function detailSize()
    {
        return $this->belongsTo('App\Models\Size', 'product_id');
    }
}
