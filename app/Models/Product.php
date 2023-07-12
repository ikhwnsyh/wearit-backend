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

    public function assets()
    {
        return $this->hasMany('App\Models\Asset');
    }
}
