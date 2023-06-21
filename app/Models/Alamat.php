<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_id');
    }
    public function kabupaten()
    {
        return $this->belongsTo('App\Models\Regency', 'regency_id');
    }
    public function kecamatan()
    {
        return $this->belongsTo('App\Models\District', 'district_id');
    }
}
