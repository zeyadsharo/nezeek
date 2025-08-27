<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if areas already exist
        if (Area::count() > 0) {
            return;
        }

        // Create main areas with Arabic titles
        $areas = [
            [
                'id' => 1,
                'title' => 'موصل',
                'parent_id' => null,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 2,
                'title' => 'مركز موصل',
                'parent_id' => 1,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 3,
                'title' => 'سنجار',
                'parent_id' => 1,
                'latitude' => 36.3167,
                'longitude' => 41.875,
            ],
            [
                'id' => 4,
                'title' => 'بعاج',
                'parent_id' => 1,
                'latitude' => 36.3167,
                'longitude' => 41.875,
            ],
            [
                'id' => 5,
                'title' => 'مركز سنجار',
                'parent_id' => 3,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 6,
                'title' => 'ناحية الشمال',
                'parent_id' => 3,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 7,
                'title' => 'تل قصب',
                'parent_id' => 3,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 8,
                'title' => 'تل بنات',
                'parent_id' => 3,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 9,
                'title' => 'حي الشهداء',
                'parent_id' => 5,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 10,
                'title' => 'حي الجزيرة',
                'parent_id' => 5,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 11,
                'title' => 'مجمع التاميم',
                'parent_id' => 6,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 12,
                'title' => 'مجمع الحطين',
                'parent_id' => 6,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 13,
                'title' => 'مجمع القادسية',
                'parent_id' => 6,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 14,
                'title' => 'حي النور',
                'parent_id' => 2,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
            [
                'id' => 15,
                'title' => 'حي الوحدة',
                'parent_id' => 2,
                'latitude' => 36.3544,
                'longitude' => 43.1432,
            ],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
