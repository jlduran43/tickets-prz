<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'user_id',
        'rut',
        'telefono',
        'region_id',
        'comuna_id',
        'direccion',
        'patente',
        'recibir_noticias',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function comuna()
    {
        return $this->belongsTo(Comuna::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    protected $casts = [
        'recibir_noticias' => 'boolean',
    ];
}
