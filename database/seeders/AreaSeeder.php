<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $areas = json_decode(file_get_contents(base_path("database/data/areas.json")));
               
               //check if the area already exists
                if (\App\Models\Area::where('id', $areas[0]->AreaID)->first()) {
                    return;
                }
               
               
                foreach ($areas as $area) {
                    \App\Models\Area::create([
                        'id' => $area->AreaID,
                        'arabic_title' => $area->ArabicTitle,
                        'kurdish_title' => $area->KurdishTitle,
                        'parent_id' => $area->Parent,
                        'latitude' => $area->Latitude,
                        'longitude' => $area->Longitude,
                    ]);
                }


    }
}
