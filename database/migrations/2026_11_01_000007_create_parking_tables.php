<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row per bay: floor "Ground Floor", zone "A", number 1  =>  code "A1".
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();
            $table->string('floor', 50)->default('Ground Floor');
            $table->string('zone', 5);
            $table->unsignedSmallInteger('number');
            $table->string('code', 12);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['floor', 'code']);
        });

        // One row per parking stay. released_at IS NULL means "parked right now".
        Schema::create('parking_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_slot_id')->constrained('parking_slots')->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->dateTime('parked_at');
            $table->dateTime('released_at')->nullable();
            $table->timestamps();

            $table->index(['parking_slot_id', 'released_at']);
            $table->index(['vehicle_id', 'released_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_records');
        Schema::dropIfExists('parking_slots');
    }
};
