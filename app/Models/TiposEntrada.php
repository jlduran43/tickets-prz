<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TiposEntrada extends Model
{
    protected $fillable = [
        'nombre',
        'precio',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function ventaDetalles(): HasMany
    {
        return $this->hasMany(
            VentaDetalle::class,
            'tipos_entradas_id'
        );
    }
}
