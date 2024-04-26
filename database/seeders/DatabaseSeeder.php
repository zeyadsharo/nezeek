<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AreaSeeder::class,
            SectorSeeder::class,
        ]);

        //create user zeyad@gmail.com
        $user = User::create([
            'name' => 'Zeyad',
            'email' =>'zeyad@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
