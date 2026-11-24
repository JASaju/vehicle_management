<?php

namespace Tests\Feature;

use App\Livewire\DriverTable;
use App\Livewire\FuelLogTable;
use App\Livewire\MaintenanceTable;
use App\Livewire\ParkingHistoryTable;
use App\Livewire\ParkingSlotTable;
use App\Livewire\UserTable;
use App\Livewire\VehicleTable;
use App\Models\User;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use Tests\TestCase;

class PowerGridCompatibilityTest extends TestCase
{
    public function test_all_powergrid_components_boot_and_define_their_fields_and_columns(): void
    {
        foreach ([
            DriverTable::class,
            FuelLogTable::class,
            MaintenanceTable::class,
            ParkingHistoryTable::class,
            ParkingSlotTable::class,
            UserTable::class,
            VehicleTable::class,
        ] as $tableClass) {
            $table = app($tableClass);

            $this->assertIsArray($table->setUp());
            $this->assertInstanceOf(PowerGridFields::class, $table->fields());
            $this->assertNotEmpty($table->columns());

            if ($tableClass !== VehicleTable::class) {
                $this->assertIsArray($table->filters());
            }
        }
    }

    public function test_authenticated_layout_loads_compiled_assets_and_livewire_scripts(): void
    {
        $user = new User;
        $user->forceFill([
            'id' => 1,
            'name' => 'Test Admin',
            'email' => 'admin@example.test',
            'role' => 'admin',
        ]);
        $this->actingAs($user);

        $html = view('layouts.app')->render();

        $this->assertStringContainsString('/build/assets/app-', $html);
        $this->assertStringContainsString('livewire.js?id=', $html);
        $this->assertStringContainsString('aria-label="Primary navigation"', $html);
        $this->assertStringContainsString('xl:hidden', $html);
        $this->assertStringContainsString('Security Logs', $html);
    }

    public function test_staff_navigation_does_not_expose_admin_links(): void
    {
        $user = new User;
        $user->forceFill([
            'id' => 2,
            'name' => 'Test Staff',
            'email' => 'staff@example.test',
            'role' => 'staff',
        ]);
        $this->actingAs($user);

        $html = view('layouts.app')->render();

        $this->assertStringNotContainsString('Security Logs', $html);
        $this->assertStringNotContainsString(route('users.index'), $html);
    }
}
