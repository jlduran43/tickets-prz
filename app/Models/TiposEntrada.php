<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposEntrada extends Model
{
    protected $fillable = [
        'nombre',
        'precio',
        'activo',
    ];
}