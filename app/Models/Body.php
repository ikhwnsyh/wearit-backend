<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Body extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function models()
    {
        return $this->belongsTo('App\Models\Kategori', 'kategori_id');
    }
}
