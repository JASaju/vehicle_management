<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (! in_array($driver, ['mysql', 'pgsql'], true)) {
            throw new RuntimeException('Parking sensor migration requires MySQL or PostgreSQL.');
        }

        Schema::table('parking_slots', function (Blueprint $table) {
            $table->boolean('sensor_occupied')->nullable();
            $table->dateTime('sensor_updated_at')->nullable();
            $table->string('last_detected_plate', 50)->nullable();
            $table->dateTime('last_plate_detected_at')->nullable();
        });

        Schema::table('parking_records', function (Blueprint $table) {
            $table->string('detected_plate', 50)->nullable();
            $table->string('source', 20)->default('manual');
        });

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE parking_records MODIFY vehicle_id BIGINT UNSIGNED NULL');
        } else {
            DB::statement('ALTER TABLE parking_records ALTER COLUMN vehicle_id DROP NOT NULL');
        }
    }

    public function down(): void
    {
        if (DB::table('parking_records')->whereNull('vehicle_id')->exists()) {
            throw new RuntimeException('Cannot roll back parking sensor support while unidentified parking records exist.');
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE parking_records MODIFY vehicle_id BIGINT UNSIGNED NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE parking_records ALTER COLUMN vehicle_id SET NOT NULL');
        }

        Schema::table('parking_records', function (Blueprint $table) {
            $table->dropColumn(['detected_plate', 'source']);
        });

        Schema::table('parking_slots', function (Blueprint $table) {
            $table->dropColumn([
                'sensor_occupied',
                'sensor_updated_at',
                'last_detected_plate',
                'last_plate_detected_at',
            ]);
        });
    }
};
