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
    Schema::create('repair_orders', function (Blueprint $table) {
        $table->id();
        $table->string('tracking_code', 30)->unique(); // Ej: REP-8923
        $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
        $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
        $table->string('device_type', 100); // Celular, Tablet, etc.
        $table->string('brand', 100);       // Samsung, Xiaomi, iPhone
        $table->string('model', 100);       // Galaxy A54, Redmi Note 13
        $table->string('imei_serial', 100)->nullable();
        $table->text('reported_fault');     // Pantalla rota, no enciende, cambio de pin
        $table->text('diagnosis')->nullable();
        $table->enum('status', [
            'Recibido',
            'En Revision',
            'En Reparacion',
            'Listo para Entrega',
            'Entregado',
            'Cancelado'
        ])->default('Recibido');
        $table->decimal('estimated_cost', 10, 2)->default(0.00);
        $table->decimal('advance_payment', 10, 2)->default(0.00); // Anticipo
        $table->date('promised_date')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_orders');
    }
};
