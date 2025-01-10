<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Sector;
use App\Models\Category;
use App\Models\Property;
use App\Models\CategoryProperty;

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
            FeatureSeeder::class,
        ]);

        //check if the user not exists
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

        // Seed sectors
        $sectors = [
            ['id' => 'S100', 'arabic_title' => 'مطاعم', 'kurdish_title' => 'مطاعم', 'display_order' => 1, 'display_state' => 1, 'activation_state' => 1, 'icon' => '__.png'],
            ['id' => 'S101', 'arabic_title' => 'معارض', 'kurdish_title' => 'معارض', 'display_order' => 1, 'display_state' => 1, 'activation_state' => 1, 'icon' => '__.png'],
            ['id' => 'S102', 'arabic_title' => 'عقارات', 'kurdish_title' => 'عقارات', 'display_order' => 1, 'display_state' => 1, 'activation_state' => 1, 'icon' => '__.png'],
        ];

        foreach ($sectors as $sector) {
            Sector::create($sector);
        }

        // Seed categories
        $categories = [
            ['id' => 'C200', 'sector_id' => 'S100', 'arabic_title' => 'اكلات شرقية', 'kurdish_title' => 'اكلات شرقية', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
            ['id' => 'C201', 'sector_id' => 'S100', 'arabic_title' => 'معجنات', 'kurdish_title' => 'معجنات', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
            ['id' => 'C207', 'sector_id' => 'S101', 'arabic_title' => 'سيارات', 'kurdish_title' => 'سيارات', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
            ['id' => 'C208', 'sector_id' => 'S101', 'arabic_title' => 'مركبات', 'kurdish_title' => 'مركبات', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
            ['id' => 'C209', 'sector_id' => 'S101', 'arabic_title' => 'صيني', 'kurdish_title' => 'صيني', 'display_order' => 1, 'parent_id' => 'C207', 'icon' => '__.png'],
            ['id' => 'C210', 'sector_id' => 'S101', 'arabic_title' => 'كوري', 'kurdish_title' => 'كوري', 'display_order' => 1, 'parent_id' => 'C207', 'icon' => '__.png'],
            ['id' => 'C211', 'sector_id' => 'S101', 'arabic_title' => 'MG', 'kurdish_title' => 'MG', 'display_order' => 1, 'parent_id' => 'C209', 'icon' => '__.png'],
            ['id' => 'C212', 'sector_id' => 'S101', 'arabic_title' => 'Accent', 'kurdish_title' => 'Accent', 'display_order' => 1, 'parent_id' => 'C210', 'icon' => '__.png'],
            ['id' => 'C213', 'sector_id' => 'S101', 'arabic_title' => 'Tecson', 'kurdish_title' => 'Tecson', 'display_order' => 1, 'parent_id' => 'C210', 'icon' => '__.png'],
            ['id' => 'C214', 'sector_id' => 'S101', 'arabic_title' => 'شفل', 'kurdish_title' => 'شفل', 'display_order' => 1, 'parent_id' => 'C208', 'icon' => '__.png'],
            ['id' => 'C215', 'sector_id' => 'S101', 'arabic_title' => 'قلاب', 'kurdish_title' => 'قلاب', 'display_order' => 1, 'parent_id' => 'C208', 'icon' => '__.png'],
            ['id' => 'C216', 'sector_id' => 'S101', 'arabic_title' => 'MG-6', 'kurdish_title' => 'MG-6', 'display_order' => 1, 'parent_id' => 'C210', 'icon' => '__.png'],
            ['id' => 'C217', 'sector_id' => 'S101', 'arabic_title' => 'MG-10', 'kurdish_title' => 'MG-10', 'display_order' => 1, 'parent_id' => 'C210', 'icon' => '__.png'],
            ['id' => 'C218', 'sector_id' => 'S101', 'arabic_title' => 'MG-6X', 'kurdish_title' => 'MG-6X', 'display_order' => 1, 'parent_id' => 'C216', 'icon' => '__.png'],
            ['id' => 'C219', 'sector_id' => 'S101', 'arabic_title' => 'MG-6s', 'kurdish_title' => 'MG-6s', 'display_order' => 1, 'parent_id' => 'C216', 'icon' => '__.png'],
            ['id' => 'C220', 'sector_id' => 'S102', 'arabic_title' => 'ارضي سكنية', 'kurdish_title' => 'ارضي سكنية', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
            ['id' => 'C221', 'sector_id' => 'S102', 'arabic_title' => 'شقق', 'kurdish_title' => 'شقق', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
            ['id' => 'C222', 'sector_id' => 'S102', 'arabic_title' => 'ارضي زراعية', 'kurdish_title' => 'ارضي زراعية', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
            ['id' => 'C223', 'sector_id' => 'S102', 'arabic_title' => 'دكاكين', 'kurdish_title' => 'دكاكين', 'display_order' => 1, 'parent_id' => null, 'icon' => '__.png'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Seed properties
        $properties = [
            ['id' => 'P300', 'name' => 'Model', 'arabic_title' => 'موديل', 'kurdish_title' => 'مودل', 'is_required' => 1, 'type' => 'textbox'],
            ['id' => 'P301', 'name' => 'Numerical Size', 'arabic_title' => 'حجم', 'kurdish_title' => 'حجم', 'is_required' => 0, 'type' => 'number'],
            ['id' => 'P302', 'name' => 'Letter Size', 'arabic_title' => 'حجم', 'kurdish_title' => 'حجم', 'is_required' => 0, 'type' => 'select', 'values' => json_encode(['3xs', '2xs', 'xs', 's', 'm', 'l', 'xl', 'xxl', '3xl', '4xl', '5xl'])],
            ['id' => 'P303', 'name' => 'Descriptive Size', 'arabic_title' => 'حجم', 'kurdish_title' => 'حجم', 'is_required' => 0, 'type' => 'select', 'values' => json_encode(['صغير', 'وسط', 'كبير'])],
            ['id' => 'P304', 'name' => 'Real value Size', 'arabic_title' => 'حجم', 'kurdish_title' => 'حجم', 'is_required' => 0, 'type' => 'textbox'],
            ['id' => 'P305', 'name' => 'RAM Size', 'arabic_title' => 'سعة الذاكرة', 'kurdish_title' => 'سعة الذاكرة', 'is_required' => 0, 'type' => 'textbox', 'unit' => 'MB'],
            ['id' => 'P306', 'name' => 'Free Shipping', 'arabic_title' => 'شحن مجاني', 'kurdish_title' => 'شحن مجاني', 'is_required' => 0, 'type' => 'select', 'values' => json_encode(['نعم', 'لا'])],
            ['id' => 'P307', 'name' => 'Used Status', 'arabic_title' => 'مستعمل', 'kurdish_title' => 'مستعمل', 'is_required' => 0, 'type' => 'select', 'values' => json_encode(['جديد', 'مستعمل'])],
            ['id' => 'P308', 'name' => 'Production Date', 'arabic_title' => 'تاريخ الانتاج', 'kurdish_title' => 'تاريخ الانتاج', 'is_required' => 0, 'type' => 'date'],
            ['id' => 'P309', 'name' => 'Expiry Date', 'arabic_title' => 'تاريخ الانتهاء', 'kurdish_title' => 'تاريخ الانتهاء', 'is_required' => 0, 'type' => 'date'],
            ['id' => 'P310', 'name' => 'Length', 'arabic_title' => 'الطول', 'kurdish_title' => 'الطول', 'is_required' => 0, 'type' => 'textbox', 'unit' => 'm'],
            ['id' => 'P311', 'name' => 'Usage percent', 'arabic_title' => 'نسبة الاستخدام', 'kurdish_title' => 'نسبة الاستخدام', 'is_required' => 0, 'type' => 'number', 'unit' => '%'],
            ['id' => 'P312', 'name' => 'Production Year', 'arabic_title' => 'سنة الصنع', 'kurdish_title' => 'سنة الصنع', 'is_required' => 0, 'type' => 'textbox'],
            ['id' => 'P313', 'name' => 'Color', 'arabic_title' => 'اللون', 'kurdish_title' => 'اللون', 'is_required' => 0, 'type' => 'textbox'],
            ['id' => 'P314', 'name' => 'width', 'arabic_title' => 'عرض', 'kurdish_title' => 'عرض', 'is_required' => 0, 'type' => 'textbox', 'unit' => 'm'],
            ['id' => 'P315', 'name' => 'Area', 'arabic_title' => 'مساحة', 'kurdish_title' => 'مساحة', 'is_required' => 0, 'type' => 'textbox', 'unit' => 'm'],
            ['id' => 'P316', 'name' => 'Location', 'arabic_title' => 'الموقع', 'kurdish_title' => 'الموقع', 'is_required' => 0, 'type' => 'textbox'],
        ];

        foreach ($properties as $property) {
            Property::create($property);
        }

        // Seed category properties
        $categoryProperties = [
            ['id' => 'CP400', 'category_id' => 'C201', 'property_id' => 'P303', 'display_order' => 0],
            ['id' => 'CP401', 'category_id' => 'C212', 'property_id' => 'P300', 'display_order' => 1],
            ['id' => 'CP402', 'category_id' => 'C212', 'property_id' => 'P307', 'display_order' => 3],
            ['id' => 'CP403', 'category_id' => 'C212', 'property_id' => 'P311', 'display_order' => 3],
            ['id' => 'CP404', 'category_id' => 'C212', 'property_id' => 'P312', 'display_order' => 2],
            ['id' => 'CP405', 'category_id' => 'C216', 'property_id' => 'P300', 'display_order' => 1],
            ['id' => 'CP406', 'category_id' => 'C216', 'property_id' => 'P307', 'display_order' => 3],
            ['id' => 'CP407', 'category_id' => 'C216', 'property_id' => 'P311', 'display_order' => 3],
            ['id' => 'CP408', 'category_id' => 'C216', 'property_id' => 'P312', 'display_order' => 2],
            ['id' => 'CP409', 'category_id' => 'C220', 'property_id' => 'P315', 'display_order' => 1],
            ['id' => 'CP410', 'category_id' => 'C220', 'property_id' => 'P316', 'display_order' => 2],
            ['id' => 'CP411', 'category_id' => 'C221', 'property_id' => 'P315', 'display_order' => 1],
            ['id' => 'CP412', 'category_id' => 'C221', 'property_id' => 'P316', 'display_order' => 2]
        ];
        foreach ($categoryProperties as $key => $value) {
            CategoryProperty::create($value);
        }
    }
}
