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
         // trancate the table
    //      {
    //     "AreaID": 1,
    //     "ArabicTitle": "موصل",
    //     "KurdishTitle": "ميسل",
    //     "Parent": null,
    //     "Latitude": 36.3544,
    //     "Longitude": 43.1432
    // },
                //    \App\Models\Area::truncate();
                $areas = json_decode(file_get_contents(base_path("database/data/areas.json")));
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
