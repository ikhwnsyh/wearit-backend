<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function productSize()
    {
        return $this->hasMany('App\Models\Size');
    }

    public function image()
    {
        return $this->hasMany('App\Models\Image');
    }

    //ini cuma bisa ngambil asset 3d berdasarkan produk_id aja. bukan yang sesuai sama kategori tubuh user
    public function assets()
    {
        return $this->hasMany('App\Models\Asset');
    }
}
