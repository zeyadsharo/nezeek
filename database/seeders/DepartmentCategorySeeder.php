<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // trancate the table
        // \App\Models\DepartmentCategory::truncate();
        
        // $departmentCategories = json_decode(file_get_contents(base_path("database/data/DepartmentCategories.json")));
//  {
//         "CategoryID": 1,
//         "ArabicTitle": "الكترونيات",
//         "KurdishTitle": "الكترونيك", 
//         "DisplayOrder": 1, 
//         "Icon": "1.svg"
//     },

        // foreach ($departmentCategories as $departmentCategory) {
        //     \App\Models\DepartmentCategory::create([
        //         'id' => $departmentCategory->CategoryID,
        //         'arabic_title' => $departmentCategory->ArabicTitle,
        //         'kurdish_title' => $departmentCategory->KurdishTitle,
        //         'display_order' => $departmentCategory->DisplayOrder,
        //         'icon' => $departmentCategory->Icon,
        //     ]);
        // }
    }
}
