<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'test'
        ]);
        \App\Models\Unit::insert([
            [
                'name' => 'м',
                'code_okie' => '006'
            ],
            [
                'name' => 'шт',
                'code_okie' => '055'
            ],
            [
                'name' => 'м3',
                'code_okie' => '113'
            ]
        ]);

        \App\Models\Uslug::factory(150)->create();

        \App\Models\Warehouse::insert([
            [
                'name' => 'Склад 1',
                'is_main' => 0,
            ],
            [
                'name' => 'Склад 2',
                'is_main' => 0,
            ],
            [
                'name' => 'Склад 3',
                'is_main' => 0,
            ],
            [
                'name' => 'Склад 4',
                'is_main' => 1,
            ],
        ]);
    }
}
