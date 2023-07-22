<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany('App\Models\Detail');
    }

    public function ekspedisi()
    {
        return $this->belongsTo('App\Models\Ekspedisi', 'ekspedisi_id');
    }

    public function statusName()
    {
        return $this->belongsTo('App\Models\Status', 'status_id');
    }

    public function userTransaction()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function userAddress()
    {
        return $this->belongsTo('App\Models\Alamat', 'alamat_id');
    }
}
