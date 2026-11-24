<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types')->restrictOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->string('registration_no', 50)->unique();
            $table->string('brand', 80);
            $table->string('model', 80);
            $table->unsignedSmallInteger('year');
            $table->string('color', 40)->nullable();
            $table->string('chassis_no', 80)->nullable();
            $table->string('engine_no', 80)->nullable();
            $table->enum('fuel_type', ['petrol', 'diesel', 'octane', 'cng', 'lpg', 'electric', 'hybrid'])->default('petrol');
            $table->unsignedTinyInteger('seat_capacity')->default(4);
            $table->unsignedInteger('odometer')->default(0);
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->date('tax_token_expiry')->nullable();
            $table->date('fitness_expiry')->nullable();
            $table->enum('status', ['active', 'in_maintenance', 'inactive', 'sold'])->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
