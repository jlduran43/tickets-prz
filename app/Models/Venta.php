<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'folio',
        'cliente_id',
        'nombre_cliente',
        'rut_cliente',
        'correo',
        'telefono',
        'region_id',
        'comuna_id',
        'cantidad_personas',
        'fecha',
        'medio_pago',
        'subtotal',
        'total',
        'estado',
        'webpay_buy_order',
        'webpay_session_id',
        'webpay_token',
        'webpay_authorization_code',
        'webpay_response_code',
        'webpay_payment_type_code',
        'webpay_card_number',
        'pagada_at',
        'token_ticket',
        'ticket_enviado_at',
        'validada_at',
        'validada_por',
    ];

    protected $casts = [
        'vigente_hasta' => 'datetime',
        'pagada_at' => 'datetime',
        'ticket_enviado_at' => 'datetime',
        'validada_at' => 'datetime',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function comuna()
    {
        return $this->belongsTo(Comuna::class);
    }

    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class);
    }

    public function usuarioValidador()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'validada_por'
        );
    }
}
