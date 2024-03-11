<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // trancate the table
        // \App\Models\Sector::truncate();

    //     {
    //     "SectorID": 1,
    //     "ArabicTitle": "قاعات افراح",
    //     "KurdishTitle": "هوليين شاهيانا",
    //     "Description": "", 
    //     "DisplayOrder": 1,
    //     "DisplayState": 1,
    //     "Icon": "1.svg",
    //     "ActivationState": 1
    // },
        $sectors = json_decode(file_get_contents(base_path("database/data/sectors.json")));
        foreach ($sectors as $sector) {
            \App\Models\Sector::create([
                'id' => $sector->SectorID,
                'arabic_title' => $sector->ArabicTitle,
                'kurdish_title' => $sector->KurdishTitle,
                'description' => $sector->Description,
                'display_order' => $sector->DisplayOrder,
                'display_state' => $sector->DisplayState,
                'icon' => $sector->Icon,
                'activation_state' => $sector->ActivationState,
            ]);
        }
    }
}
