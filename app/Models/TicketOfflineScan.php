<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketOfflineScan extends Model
{
    protected $fillable = [
        'scan_uuid',
        'venta_id',
        'token_ticket',
        'device_id',
        'scanned_at',
        'received_at',
        'resultado',
        'payload',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'received_at' => 'datetime',
        'payload' => 'array',
    ];
}