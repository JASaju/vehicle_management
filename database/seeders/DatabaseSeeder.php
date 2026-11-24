<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\FuelLog;
use App\Models\Maintenance;
use App\Models\ParkingRecord;
use App\Models\ParkingSlot;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! User::where('role', 'admin')->exists()) {
            $email = trim((string) config('bootstrap_admin.email'));
            $password = config('bootstrap_admin.password');

            if (! filter_var($email, FILTER_VALIDATE_EMAIL) || ! is_string($password) || strlen($password) < 12) {
                throw new RuntimeException(
                    'Set a valid ADMIN_EMAIL and a unique ADMIN_PASSWORD of at least 12 characters before seeding.'
                );
            }

            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                throw new RuntimeException(
                    'ADMIN_EMAIL already belongs to a non-administrator account. Choose another address.'
                );
            }

            User::create([
                'name' => 'Administrator',
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true,
            ]);
        }

        $types = collect(['Sedan', 'SUV', 'Microbus', 'Pickup', 'Truck', 'Motorcycle'])
            ->mapWithKeys(fn ($name) => [$name => VehicleType::firstOrCreate(['name' => $name])]);

        $drivers = collect([
            ['Abdul Karim', '01711000001', 'karim@example.com', 'DL-1001', '2028-03-15', 'Mirpur, Dhaka', true],
            ['Rafiqul Islam', '01711000002', 'rafiq@example.com', 'DL-1002', '2027-08-20', 'Uttara, Dhaka', true],
            ['Jamal Uddin', '01711000003', null, 'DL-1003', '2026-12-10', 'Gazipur', true],
            ['Sohel Rana', '01711000004', 'sohel@example.com', 'DL-1004', '2029-01-05', 'Savar, Dhaka', true],
            ['Mizanur Rahman', '01711000005', null, 'DL-1005', '2026-05-30', 'Narayanganj', false],
        ])->map(fn ($d) => Driver::firstOrCreate(['license_number' => $d[3]], [
            'name' => $d[0], 'phone' => $d[1], 'email' => $d[2],
            'license_expiry' => $d[4], 'address' => $d[5], 'is_active' => $d[6],
        ]))->values();

        $vehicles = collect([
            ['Sedan', 0, 'DHAKA METRO-GA-11-2345', 'Toyota', 'Corolla', 2020, 'White', 'octane', 5, 48200, '2020-06-10', 3200000, '2027-01-31', '2027-03-31', '2027-06-30', 'active'],
            ['SUV', 1, 'DHAKA METRO-HA-22-7788', 'Toyota', 'Land Cruiser', 2019, 'Black', 'diesel', 7, 92100, '2019-09-01', 9800000, '2026-12-15', '2026-11-20', '2027-02-28', 'active'],
            ['Microbus', 2, 'DHAKA METRO-CHA-13-4521', 'Toyota', 'HiAce', 2018, 'Silver', 'octane', 12, 134500, '2018-04-22', 4200000, '2026-11-25', '2027-04-30', '2026-12-31', 'in_maintenance'],
            ['Pickup', 3, 'DHAKA METRO-THA-11-9090', 'Mitsubishi', 'L200', 2021, 'Blue', 'diesel', 5, 61000, '2021-02-14', 3600000, '2027-05-31', '2027-05-31', '2027-08-31', 'active'],
            ['Truck', null, 'DHAKA METRO-DA-11-1122', 'Tata', 'LPT 1613', 2017, 'Red', 'diesel', 3, 210800, '2017-11-05', 5200000, '2026-10-15', '2026-09-30', '2026-10-31', 'inactive'],
            ['Motorcycle', null, 'DHAKA METRO-LA-33-5566', 'Honda', 'CB Hornet', 2022, 'Black', 'octane', 2, 15400, '2022-07-19', 310000, '2027-07-31', '2027-07-31', '2027-07-31', 'active'],
        ])->map(fn ($v) => Vehicle::firstOrCreate(['registration_no' => $v[2]], [
            'vehicle_type_id' => $types[$v[0]]->id,
            'driver_id' => $v[1] === null ? null : $drivers[$v[1]]->id,
            'brand' => $v[3], 'model' => $v[4], 'year' => $v[5], 'color' => $v[6],
            'fuel_type' => $v[7], 'seat_capacity' => $v[8], 'odometer' => $v[9],
            'purchase_date' => $v[10], 'purchase_price' => $v[11],
            'insurance_expiry' => $v[12], 'tax_token_expiry' => $v[13], 'fitness_expiry' => $v[14],
            'status' => $v[15],
        ]))->values();

        if (Maintenance::count() === 0) {
            foreach ([
                [0, 'oil_change', 'Engine oil + filter', '2026-08-12', '2027-02-12', 46500, 6500, 'AutoCare Mirpur', 'completed'],
                [1, 'brake', 'Front brake pads replaced', '2026-09-03', '2027-03-03', 90500, 18500, 'Prime Motors', 'completed'],
                [2, 'repair', 'Gearbox repair', '2026-10-28', null, 134000, 85000, 'Nitol Workshop', 'in_progress'],
                [3, 'tire', '4 new tires', '2026-07-20', '2028-07-20', 59800, 52000, 'Tire Zone', 'completed'],
                [0, 'general_service', 'Annual service', '2026-11-20', '2027-11-20', null, 12000, 'AutoCare Mirpur', 'scheduled'],
            ] as $m) {
                Maintenance::create([
                    'vehicle_id' => $vehicles[$m[0]]->id, 'type' => $m[1], 'description' => $m[2],
                    'service_date' => $m[3], 'next_service_date' => $m[4], 'odometer' => $m[5],
                    'cost' => $m[6], 'workshop' => $m[7], 'status' => $m[8],
                ]);
            }
        }

        if (FuelLog::count() === 0) {
            foreach ([
                [0, 0, '2026-10-02', 35, 125, 47800, 'Padma Oil Mirpur'],
                [0, 0, '2026-10-20', 32.5, 125, 48200, 'Padma Oil Mirpur'],
                [1, 1, '2026-10-05', 60, 109, 91600, 'Meghna Petrol Pump'],
                [3, 3, '2026-10-15', 48, 109, 60700, 'Jamuna Oil Savar'],
                [5, null, '2026-10-25', 8, 125, 15400, 'Padma Oil Uttara'],
            ] as $f) {
                FuelLog::create([
                    'vehicle_id' => $vehicles[$f[0]]->id,
                    'driver_id' => $f[1] === null ? null : $drivers[$f[1]]->id,
                    'fuel_date' => $f[2], 'liters' => $f[3], 'price_per_liter' => $f[4],
                    'total_cost' => round($f[3] * $f[4], 2), 'odometer' => $f[5], 'station' => $f[6],
                ]);
            }
        }

        // Ground floor: row A (A1-A3) and row B (B1-B2).
        $slots = collect();
        foreach (['A' => [1, 2, 3], 'B' => [1, 2]] as $zone => $numbers) {
            foreach ($numbers as $number) {
                $slots[$zone.$number] = ParkingSlot::firstOrCreate(
                    ['floor' => 'Ground Floor', 'code' => $zone.$number],
                    ['zone' => $zone, 'number' => $number, 'is_active' => true],
                );
            }
        }

        // Two vehicles already parked, so the map is not empty on first run.
        if (ParkingRecord::count() === 0) {
            ParkingRecord::create(['parking_slot_id' => $slots['A1']->id, 'vehicle_id' => $vehicles[0]->id, 'parked_at' => now()->subHours(5)]);
            ParkingRecord::create(['parking_slot_id' => $slots['A2']->id, 'vehicle_id' => $vehicles[1]->id, 'parked_at' => now()->subHours(2)]);
        }
    }
}
