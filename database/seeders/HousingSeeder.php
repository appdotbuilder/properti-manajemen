<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\House;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HousingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@housing.com',
            'password' => Hash::make('password'),
            'role' => 'administrator',
            'phone' => '08123456789',
            'status' => 'active',
        ]);

        // Create housing manager
        $manager = User::create([
            'name' => 'Housing Manager',
            'email' => 'manager@housing.com',
            'password' => Hash::make('password'),
            'role' => 'housing_manager',
            'phone' => '08123456790',
            'status' => 'active',
        ]);

        // Create sales staff
        $sales = User::create([
            'name' => 'Sales Staff',
            'email' => 'sales@housing.com',
            'password' => Hash::make('password'),
            'role' => 'sales_staff',
            'phone' => '08123456791',
            'status' => 'active',
        ]);

        // Create some resident users
        $residents = [];
        for ($i = 1; $i <= 5; $i++) {
            $residents[] = User::create([
                'name' => "Penghuni $i",
                'email' => "resident$i@housing.com",
                'password' => Hash::make('password'),
                'role' => 'resident',
                'phone' => '0812345679' . $i,
                'status' => 'active',
            ]);
        }

        // Create houses
        $availableHouses = House::factory(15)->available()->create();
        $occupiedHouses = House::factory(20)->occupied()->create();
        $soldHouses = House::factory(5)->sold()->create();

        // Create residents for occupied houses
        foreach ($occupiedHouses as $house) {
            $resident = Resident::factory()->active()->create([
                'house_id' => $house->id,
                'user_id' => fake()->optional(0.8)->randomElement($residents)?->id,
            ]);

            // Create some payments for this resident
            Payment::factory(random_int(1, 5))->create([
                'house_id' => $house->id,
                'resident_id' => $resident->id,
            ]);

            // Create some complaints for this resident
            if (fake()->boolean(60)) {
                Complaint::factory(random_int(1, 3))->create([
                    'house_id' => $house->id,
                    'resident_id' => $resident->id,
                    'assigned_to' => fake()->optional(0.7)->randomElement([$admin->id, $manager->id]),
                ]);
            }
        }

        // Create some residents for sold houses too
        foreach ($soldHouses->take(3) as $house) {
            $resident = Resident::factory()->active()->create([
                'house_id' => $house->id,
                'user_id' => fake()->optional(0.8)->randomElement($residents)?->id,
            ]);

            // Create payments
            Payment::factory(random_int(1, 3))->create([
                'house_id' => $house->id,
                'resident_id' => $resident->id,
            ]);
        }
    }
}