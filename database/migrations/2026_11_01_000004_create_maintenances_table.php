<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->enum('type', ['oil_change', 'tire', 'brake', 'battery', 'general_service', 'repair', 'other'])->default('general_service');
            $table->string('description')->nullable();
            $table->date('service_date')->index();
            $table->date('next_service_date')->nullable();
            $table->unsignedInteger('odometer')->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->string('workshop', 150)->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed'])->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
