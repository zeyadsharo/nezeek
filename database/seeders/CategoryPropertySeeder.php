<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyGroup;
use App\Models\Sector;
use Illuminate\Database\Seeder;

class CategoryPropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Property Groups
        $basicInfo = PropertyGroup::create([
            'name' => 'basic_info',
            'title' => 'المعلومات الأساسية',
            'description' => 'المعلومات الأساسية حول العنصر',
            'display_order' => 1,
            'icon' => 'heroicon-o-information-circle',
            'color' => '#3B82F6',
            'is_collapsible' => false,
            'is_expanded_by_default' => true,
        ]);

        $specifications = PropertyGroup::create([
            'name' => 'specifications',
            'title' => 'المواصفات',
            'description' => 'المواصفات التقنية والتفاصيل',
            'display_order' => 2,
            'icon' => 'heroicon-o-cog-6-tooth',
            'color' => '#10B981',
            'is_collapsible' => true,
            'is_expanded_by_default' => false,
        ]);

        $dimensions = PropertyGroup::create([
            'name' => 'dimensions',
            'title' => 'الأبعاد',
            'description' => 'الأبعاد الفيزيائية والقياسات',
            'display_order' => 3,
            'icon' => 'heroicon-o-square-3-stack-3d',
            'color' => '#F59E0B',
            'is_collapsible' => true,
            'is_expanded_by_default' => false,
        ]);

        $pricing = PropertyGroup::create([
            'name' => 'pricing',
            'title' => 'التسعير',
            'description' => 'معلومات التسعير والتكلفة',
            'display_order' => 4,
            'icon' => 'heroicon-o-currency-dollar',
            'color' => '#EF4444',
            'is_collapsible' => true,
            'is_expanded_by_default' => false,
        ]);

        // Create Properties
        $properties = [
            // Basic Info Group
            [
                'name' => 'title',
                'title' => 'العنوان',
                'description' => 'العنوان الرئيسي للعنصر',
                'type' => 'text',
                'is_required' => true,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_length' => 3,
                'max_length' => 255,
                'placeholder' => 'أدخل عنوان العنصر',
                'help_text' => 'أدخل عنواناً وصفياً للعنصر',
                'group' => 'basic_info',
                'display_order' => 1,
            ],
            [
                'name' => 'description',
                'title' => 'الوصف',
                'description' => 'وصف مفصل للعنصر',
                'type' => 'textarea',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => false,
                'is_sortable' => false,
                'min_length' => 10,
                'max_length' => 1000,
                'placeholder' => 'أدخل وصفاً مفصلاً',
                'help_text' => 'قدم وصفاً شاملاً للعنصر',
                'group' => 'basic_info',
                'display_order' => 2,
            ],
            [
                'name' => 'brand',
                'title' => 'العلامة التجارية',
                'description' => 'اسم العلامة التجارية أو الشركة المصنعة',
                'type' => 'select',
                'options' => ['آبل', 'سامسونج', 'سوني', 'إل جي', 'أخرى'],
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'placeholder' => 'اختر العلامة التجارية',
                'help_text' => 'اختر العلامة التجارية من القائمة',
                'group' => 'basic_info',
                'display_order' => 3,
            ],
            [
                'name' => 'model',
                'title' => 'الموديل',
                'description' => 'رقم أو اسم الموديل',
                'type' => 'text',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_length' => 2,
                'max_length' => 100,
                'placeholder' => 'أدخل رقم الموديل',
                'help_text' => 'أدخل رقم الموديل المحدد',
                'group' => 'basic_info',
                'display_order' => 4,
            ],

            // Specifications Group
            [
                'name' => 'color',
                'title' => 'اللون',
                'description' => 'الألوان المتاحة',
                'type' => 'multiselect',
                'options' => ['أسود', 'أبيض', 'أحمر', 'أزرق', 'أخضر', 'أصفر', 'فضي', 'ذهبي'],
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => false,
                'min_selections' => 1,
                'max_selections' => 5,
                'placeholder' => 'اختر الألوان',
                'help_text' => 'اختر لوناً واحداً أو أكثر من الألوان المتاحة',
                'group' => 'specifications',
                'display_order' => 1,
            ],
            [
                'name' => 'material',
                'title' => 'المادة',
                'description' => 'المادة الأساسية المستخدمة',
                'type' => 'select',
                'options' => ['بلاستيك', 'معدن', 'زجاج', 'خشب', 'قماش', 'جلد', 'سيراميك'],
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => false,
                'placeholder' => 'اختر المادة',
                'help_text' => 'اختر المادة الأساسية',
                'group' => 'specifications',
                'display_order' => 2,
            ],
            [
                'name' => 'warranty',
                'title' => 'الضمان',
                'description' => 'فترة الضمان بالأشهر',
                'type' => 'number',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 120,
                'unit' => 'شهر',
                'placeholder' => 'أدخل فترة الضمان',
                'help_text' => 'أدخل فترة الضمان بالأشهر',
                'group' => 'specifications',
                'display_order' => 3,
            ],
            [
                'name' => 'in_stock',
                'title' => 'متوفر في المخزون',
                'description' => 'حالة التوفر',
                'type' => 'checkbox',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => false,
                'help_text' => 'حدد إذا كان العنصر متوفر حالياً في المخزون',
                'group' => 'specifications',
                'display_order' => 4,
            ],

            // Dimensions Group
            [
                'name' => 'length',
                'title' => 'الطول',
                'description' => 'قياس الطول',
                'type' => 'decimal',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 1000,
                'unit' => 'سم',
                'placeholder' => 'أدخل الطول',
                'help_text' => 'أدخل الطول بالسنتيمترات',
                'group' => 'dimensions',
                'display_order' => 1,
            ],
            [
                'name' => 'width',
                'title' => 'العرض',
                'description' => 'قياس العرض',
                'type' => 'decimal',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 1000,
                'unit' => 'سم',
                'placeholder' => 'أدخل العرض',
                'help_text' => 'أدخل العرض بالسنتيمترات',
                'group' => 'dimensions',
                'display_order' => 2,
            ],
            [
                'name' => 'height',
                'title' => 'الارتفاع',
                'description' => 'قياس الارتفاع',
                'type' => 'decimal',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 1000,
                'unit' => 'سم',
                'placeholder' => 'أدخل الارتفاع',
                'help_text' => 'أدخل الارتفاع بالسنتيمترات',
                'group' => 'dimensions',
                'display_order' => 3,
            ],
            [
                'name' => 'weight',
                'title' => 'الوزن',
                'description' => 'قياس الوزن',
                'type' => 'decimal',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 1000,
                'unit' => 'كجم',
                'placeholder' => 'أدخل الوزن',
                'help_text' => 'أدخل الوزن بالكيلوجرامات',
                'group' => 'dimensions',
                'display_order' => 4,
            ],

            // Pricing Group
            [
                'name' => 'price',
                'title' => 'السعر',
                'description' => 'سعر البيع الحالي',
                'type' => 'decimal',
                'is_required' => true,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 999999.99,
                'unit' => 'دولار',
                'placeholder' => 'أدخل السعر',
                'help_text' => 'أدخل سعر البيع الحالي',
                'group' => 'pricing',
                'display_order' => 1,
            ],
            [
                'name' => 'original_price',
                'title' => 'السعر الأصلي',
                'description' => 'السعر الأصلي قبل الخصم',
                'type' => 'decimal',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 999999.99,
                'unit' => 'دولار',
                'placeholder' => 'أدخل السعر الأصلي',
                'help_text' => 'أدخل السعر الأصلي قبل أي خصومات',
                'group' => 'pricing',
                'display_order' => 2,
            ],
            [
                'name' => 'discount_percentage',
                'title' => 'نسبة الخصم',
                'description' => 'نسبة الخصم',
                'type' => 'number',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 100,
                'unit' => '%',
                'placeholder' => 'أدخل نسبة الخصم',
                'help_text' => 'أدخل نسبة الخصم (0-100)',
                'group' => 'pricing',
                'display_order' => 3,
            ],
        ];

        foreach ($properties as $propertyData) {
            Property::create($propertyData);
        }

        // Create sample categories if sectors exist
        if (Sector::count() > 0) {
            $sector = Sector::first();

            $electronics = Category::create([
                'name' => 'electronics',
                'sector_id' => $sector->id,
                'title' => 'الإلكترونيات',
                'description' => 'الأجهزة الإلكترونية والأدوات الذكية',
                'slug' => 'electronics',
                'display_order' => 1,
                'is_active' => true,
                'is_featured' => true,
            ]);

            $phones = Category::create([
                'name' => 'phones',
                'sector_id' => $sector->id,
                'parent_id' => $electronics->id,
                'title' => 'الهواتف',
                'description' => 'الهواتف المحمولة والذكية',
                'slug' => 'phones',
                'display_order' => 1,
                'is_active' => true,
            ]);

            $laptops = Category::create([
                'name' => 'laptops',
                'sector_id' => $sector->id,
                'parent_id' => $electronics->id,
                'title' => 'أجهزة الكمبيوتر المحمولة',
                'description' => 'أجهزة الكمبيوتر المحمولة والحواسيب المحمولة',
                'slug' => 'laptops',
                'display_order' => 2,
                'is_active' => true,
            ]);

            // Attach properties to categories
            $allProperties = Property::all();

            // Attach all properties to electronics category
            foreach ($allProperties as $index => $property) {
                $electronics->properties()->attach($property->id, [
                    'display_order' => $index + 1,
                    'is_visible' => true,
                    'is_editable' => true,
                    'is_required' => in_array($property->name, ['title', 'price']),
                ]);
            }

            // Attach specific properties to phones category
            $phoneProperties = Property::whereIn('name', ['title', 'description', 'brand', 'model', 'color', 'price', 'in_stock'])->get();
            foreach ($phoneProperties as $index => $property) {
                $phones->properties()->attach($property->id, [
                    'display_order' => $index + 1,
                    'is_visible' => true,
                    'is_editable' => true,
                    'is_required' => in_array($property->name, ['title', 'brand', 'price']),
                ]);
            }

            // Attach specific properties to laptops category
            $laptopProperties = Property::whereIn('name', ['title', 'description', 'brand', 'model', 'color', 'material', 'warranty', 'price', 'in_stock'])->get();
            foreach ($laptopProperties as $index => $property) {
                $laptops->properties()->attach($property->id, [
                    'display_order' => $index + 1,
                    'is_visible' => true,
                    'is_editable' => true,
                    'is_required' => in_array($property->name, ['title', 'brand', 'price']),
                ]);
            }
        }
    }
}
