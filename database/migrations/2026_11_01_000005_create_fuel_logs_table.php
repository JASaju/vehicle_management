<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->date('fuel_date')->index();
            $table->decimal('liters', 8, 2);
            $table->decimal('price_per_liter', 8, 2);
            $table->decimal('total_cost', 12, 2);
            $table->unsignedInteger('odometer')->nullable();
            $table->string('station', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_logs');
    }
};
