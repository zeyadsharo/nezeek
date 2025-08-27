<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryProperty;
use App\Models\Property;
use App\Models\Sector;
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
            CategoryPropertySeeder::class,
        ]);

        // check if the user not exists
        if (User::where('email', 'zeyad@gmail.com')->first()) {
            return;
        }
        //create user zeyad@gmail.com
        $user = User::create([
            'name' => 'Zeyad',
            'email' => 'zeyad@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'Super admin',
        ]);

        // Create additional Arabic users for testing
        $users = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'password' => bcrypt('12345678'),
                'role' => 'مدير',
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'fatima@example.com',
                'password' => bcrypt('12345678'),
                'role' => 'مشرف',
            ],
            [
                'name' => 'محمد حسن',
                'email' => 'mohammed@example.com',
                'password' => bcrypt('12345678'),
                'role' => 'مستخدم',
            ],
        ];

        foreach ($users as $userData) {
            if (!User::where('email', $userData['email'])->first()) {
                User::create($userData);
            }
        }

        // Seed sectors
        // $sectors = [
        //     ['id' => 100, 'arabic_title' => 'مطاعم', 'kurdish_title' => 'مطاعم', 'display_order' => 1, 'display_state' => 1, 'activation_state' => 1, 'icon' => '__.png'],
        //     ['id' => 101, 'arabic_title' => 'معارض', 'kurdish_title' => 'معارض', 'display_order' => 1, 'display_state' => 1, 'activation_state' => 1, 'icon' => '__.png'],
        //     ['id' => 102, 'arabic_title' => 'عقارات', 'kurdish_title' => 'عقارات', 'display_order' => 1, 'display_state' => 1, 'activation_state' => 1, 'icon' => '__.png'],
        // ];

        // foreach ($sectors as $sector) {
        //     Sector::create($sector);
        // }

        // // Seed categories
        // $categories = [
        //     ['id' => 200, 'sector_id' => 100, 'arabic_title' => 'اكلات شرقية', 'kurdish_title' => 'اكلات شرقية', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
        //     ['id' => 201, 'sector_id' => 100, 'arabic_title' => 'معجنات', 'kurdish_title' => 'معجنات', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
        //     ['id' => 207, 'sector_id' => 101, 'arabic_title' => 'سيارات', 'kurdish_title' => 'سيارات', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
        //     ['id' => 208, 'sector_id' => 101, 'arabic_title' => 'مركبات', 'kurdish_title' => 'مركبات', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
        //     ['id' => 209, 'sector_id' => 101, 'arabic_title' => 'صيني', 'kurdish_title' => 'صيني', 'display_order' => 1, 'parent_id' => 207, 'icon' => '__.png'],
        //     ['id' => 210, 'sector_id' => 101, 'arabic_title' => 'كوري', 'kurdish_title' => 'كوري', 'display_order' => 1, 'parent_id' => 207, 'icon' => '__.png'],
        //     ['id' => 211, 'sector_id' => 101, 'arabic_title' => 'MG', 'kurdish_title' => 'MG', 'display_order' => 1, 'parent_id' => 209, 'icon' => '__.png'],
        //     ['id' => 212, 'sector_id' => 101, 'arabic_title' => 'Accent', 'kurdish_title' => 'Accent', 'display_order' => 1, 'parent_id' => 210, 'icon' => '__.png'],
        //     ['id' => 213, 'sector_id' => 101, 'arabic_title' => 'Tecson', 'kurdish_title' => 'Tecson', 'display_order' => 1, 'parent_id' => 210, 'icon' => '__.png'],
        //     ['id' => 214, 'sector_id' => 101, 'arabic_title' => 'شفل', 'kurdish_title' => 'شفل', 'display_order' => 1, 'parent_id' => 208, 'icon' => '__.png'],
        //     ['id' => 215, 'sector_id' => 101, 'arabic_title' => 'قلاب', 'kurdish_title' => 'قلاب', 'display_order' => 1, 'parent_id' => 208, 'icon' => '__.png'],
        //     ['id' => 216, 'sector_id' => 101, 'arabic_title' => 'MG-6', 'kurdish_title' => 'MG-6', 'display_order' => 1, 'parent_id' => 210, 'icon' => '__.png'],
        //     ['id' => 217, 'sector_id' => 101, 'arabic_title' => 'MG-10', 'kurdish_title' => 'MG-10', 'display_order' => 1, 'parent_id' => 210, 'icon' => '__.png'],
        //     ['id' => 218, 'sector_id' => 101, 'arabic_title' => 'MG-6X', 'kurdish_title' => 'MG-6X', 'display_order' => 1, 'parent_id' => 216, 'icon' => '__.png'],
        //     ['id' => 219, 'sector_id' => 101, 'arabic_title' => 'MG-6s', 'kurdish_title' => 'MG-6s', 'display_order' => 1, 'parent_id' => 216, 'icon' => '__.png'],
        //     ['id' => 220, 'sector_id' => 102, 'arabic_title' => 'ارضي سكنية', 'kurdish_title' => 'ارضي سكنية', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
        //     ['id' => 221, 'sector_id' => 102, 'arabic_title' => 'شقق', 'kurdish_title' => 'شقق', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
        //     ['id' => 222, 'sector_id' => 102, 'arabic_title' => 'ارضي زراعية', 'kurdish_title' => 'ارضي زراعية', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
        //     ['id' => 223, 'sector_id' => 102, 'arabic_title' => 'دكاكين', 'kurdish_title' => 'دكاكين', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
        // ];

        // foreach ($categories as $category) {
        //     Category::create($category);
        // }

        // // Seed properties
        // $properties = [
        //     ['id' => 300, 'name' => 'Model', 'arabic_title' => 'موديل', 'kurdish_title' => 'مودل', 'is_required' => 1, 'type' => 'textbox'],
        //     ['id' => 301, 'name' => 'Numerical Size', 'arabic_title' => 'حجم', 'kurdish_title' => 'حجم', 'is_required' => 0, 'type' => 'number'],
        //     ['id' => 302, 'name' => 'Letter Size', 'arabic_title' => 'حجم', 'kurdish_title' => 'حجم', 'is_required' => 0, 'type' => 'select', 'values' => json_encode(['3xs', '2xs', 'xs', 's', 'm', 'l', 'xl', 'xxl', '3xl', '4xl', '5xl'])],
        //     ['id' => 303, 'name' => 'Descriptive Size', 'arabic_title' => 'حجم', 'kurdish_title' => 'حجم', 'is_required' => 0, 'type' => 'select', 'values' => json_encode(['صغير', 'وسط', 'كبير'])],
        //     ['id' => 304, 'name' => 'Real value Size', 'arabic_title' => 'حجم', 'kurdish_title' => 'حجم', 'is_required' => 0, 'type' => 'textbox'],
        //     ['id' => 305, 'name' => 'RAM Size', 'arabic_title' => 'سعة الذاكرة', 'kurdish_title' => 'سعة الذاكرة', 'is_required' => 0, 'type' => 'textbox', 'unit' => 'MB'],
        //     ['id' => 306, 'name' => 'Free Shipping', 'arabic_title' => 'شحن مجاني', 'kurdish_title' => 'شحن مجاني', 'is_required' => 0, 'type' => 'select', 'values' => json_encode(['نعم', 'لا'])],
        //     ['id' => 307, 'name' => 'Used Status', 'arabic_title' => 'مستعمل', 'kurdish_title' => 'مستعمل', 'is_required' => 0, 'type' => 'select', 'values' => json_encode(['جديد', 'مستعمل'])],
        //     ['id' => 308, 'name' => 'Production Date', 'arabic_title' => 'تاريخ الانتاج', 'kurdish_title' => 'تاريخ الانتاج', 'is_required' => 0, 'type' => 'date'],
        //     ['id' => 309, 'name' => 'Expiry Date', 'arabic_title' => 'تاريخ الانتهاء', 'kurdish_title' => 'تاريخ الانتهاء', 'is_required' => 0, 'type' => 'date'],
        //     ['id' => 310, 'name' => 'Length', 'arabic_title' => 'الطول', 'kurdish_title' => 'الطول', 'is_required' => 0, 'type' => 'textbox', 'unit' => 'm'],
        //     ['id' => 311, 'name' => 'Usage percent', 'arabic_title' => 'نسبة الاستخدام', 'kurdish_title' => 'نسبة الاستخدام', 'is_required' => 0, 'type' => 'number', 'unit' => '%'],
        //     ['id' => 312, 'name' => 'Production Year', 'arabic_title' => 'سنة الصنع', 'kurdish_title' => 'سنة الصنع', 'is_required' => 0, 'type' => 'textbox'],
        //     ['id' => 313, 'name' => 'Color', 'arabic_title' => 'اللون', 'kurdish_title' => 'اللون', 'is_required' => 0, 'type' => 'textbox'],
        //     ['id' => 314, 'name' => 'width', 'arabic_title' => 'عرض', 'kurdish_title' => 'عرض', 'is_required' => 0, 'type' => 'textbox', 'unit' => 'm'],
        //     ['id' => 315, 'name' => 'Area', 'arabic_title' => 'مساحة', 'kurdish_title' => 'مساحة', 'is_required' => 0, 'type' => 'textbox', 'unit' => 'm'],
        //     ['id' => 316, 'name' => 'Location', 'arabic_title' => 'الموقع', 'kurdish_title' => 'الموقع', 'is_required' => 0, 'type' => 'textbox'],
        // ];

        // foreach ($properties as $property) {
        //     Property::create($property);
        // }

        // // Seed category properties
        // $categoryProperties = [
        //     ['id' => 400, 'category_id' => 201, 'property_id' => 303, 'display_order' => 0],
        //     ['id' => 401, 'category_id' => 212, 'property_id' => 300, 'display_order' => 1],
        //     ['id' => 402, 'category_id' => 212, 'property_id' => 307, 'display_order' => 3],
        //     ['id' => 403, 'category_id' => 212, 'property_id' => 311, 'display_order' => 3],
        //     ['id' => 404, 'category_id' => 212, 'property_id' => 312, 'display_order' => 2],
        //     ['id' => 405, 'category_id' => 216, 'property_id' => 300, 'display_order' => 1],
        //     ['id' => 406, 'category_id' => 216, 'property_id' => 307, 'display_order' => 3],
        //     ['id' => 407, 'category_id' => 216, 'property_id' => 311, 'display_order' => 3],
        //     ['id' => 408, 'category_id' => 216, 'property_id' => 312, 'display_order' => 2],
        //     ['id' => 409, 'category_id' => 220, 'property_id' => 315, 'display_order' => 1],
        //     ['id' => 410, 'category_id' => 220, 'property_id' => 316, 'display_order' => 2],
        //     ['id' => 411, 'category_id' => 221, 'property_id' => 315, 'display_order' => 1],
        //     ['id' => 412, 'category_id' => 221, 'property_id' => 316, 'display_order' => 2]
        // ];
        // foreach ($categoryProperties as $key => $value) {
        //     CategoryProperty::create($value);
        // }
    }
}
