<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            $table->string('folio')->unique();
            $table->string('nombre_cliente')->nullable();
            $table->string('rut_cliente')->nullable();
            $table->string('medio_pago');
            $table->string('correo')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('webpay_token')->nullable()->index();
            $table->string('webpay_buy_order')->nullable()->unique();
            $table->string('webpay_session_id')->nullable();
            $table->string('token_ticket')->nullable()->unique();
            $table->string('ultimos_digitos_tarjeta')->nullable();
            $table->string('buy_order', 26)->nullable()->unique();
            $table->string('session_id', 61)->nullable();
            $table->string('token_ws')->nullable();
            $table->string('estado')->default('PENDIENTE');
            $table->string('authorization_code')->nullable();
            $table->integer('response_code')->nullable();
            $table->string('payment_type_code')->nullable();
            $table->string('card_number')->nullable();

            $table->date('fecha');

            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            $table->unsignedInteger('cantidad_personas')->default(1);

            $table->timestamp('pagada_at')->nullable();
            $table->timestamp('vigente_hasta')->nullable();

            $table->foreignId('region_id')
                ->nullable()
                ->constrained('regiones')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('comuna_id')
                ->nullable()
                ->constrained('comunas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
