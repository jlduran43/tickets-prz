<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'ticket_offline_scans',
            function (Blueprint $table) {

                $table->id();

                $table
                    ->uuid('scan_uuid')
                    ->unique();

                $table
                    ->foreignId('venta_id')
                    ->nullable()
                    ->constrained('ventas')
                    ->nullOnDelete();

                $table
                    ->string('token_ticket')
                    ->nullable();

                $table
                    ->string('device_id')
                    ->nullable();

                $table
                    ->timestamp('scanned_at')
                    ->nullable();

                $table
                    ->timestamp('received_at')
                    ->nullable();

                $table
                    ->string('resultado')
                    ->nullable();

                $table
                    ->json('payload')
                    ->nullable();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'ticket_offline_scans'
        );
    }
};