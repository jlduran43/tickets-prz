<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    protected $table = 'comunas';

    protected $fillable = [
        'region_id',
        'nombre',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
