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
            'arabic_title' => 'المعلومات الأساسية',
            'title' => 'Basic Information',
            'description' => 'Basic information about the item',
            'display_order' => 1,
            'icon' => 'info-circle',
            'color' => '#3B82F6',
            'is_collapsible' => false,
            'is_expanded_by_default' => true,
        ]);

        $specifications = PropertyGroup::create([
            'name' => 'specifications',
            'arabic_title' => 'المواصفات',
            'title' => 'Specifications',
            'description' => 'Technical specifications and details',
            'display_order' => 2,
            'icon' => 'cog',
            'color' => '#10B981',
            'is_collapsible' => true,
            'is_expanded_by_default' => false,
        ]);

        $dimensions = PropertyGroup::create([
            'name' => 'dimensions',
            'arabic_title' => 'الأبعاد',
            'title' => 'Dimensions',
            'description' => 'Physical dimensions and measurements',
            'display_order' => 3,
            'icon' => 'ruler',
            'color' => '#F59E0B',
            'is_collapsible' => true,
            'is_expanded_by_default' => false,
        ]);

        $pricing = PropertyGroup::create([
            'name' => 'pricing',
            'arabic_title' => 'التسعير',
            'title' => 'Pricing',
            'description' => 'Pricing and cost information',
            'display_order' => 4,
            'icon' => 'currency-dollar',
            'color' => '#EF4444',
            'is_collapsible' => true,
            'is_expanded_by_default' => false,
        ]);

        // Create Properties
        $properties = [
            // Basic Info Group
            [
                'name' => 'title',
                'arabic_title' => 'العنوان',
                'title' => 'Title',
                'description' => 'Main title of the item',
                'type' => 'text',
                'is_required' => true,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_length' => 3,
                'max_length' => 255,
                'placeholder' => 'Enter item title',
                'help_text' => 'Enter a descriptive title for the item',
                'group' => 'basic_info',
                'display_order' => 1,
            ],
            [
                'name' => 'description',
                'arabic_title' => 'الوصف',
                'title' => 'Description',
                'description' => 'Detailed description of the item',
                'type' => 'textarea',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => false,
                'is_sortable' => false,
                'min_length' => 10,
                'max_length' => 1000,
                'placeholder' => 'Enter detailed description',
                'help_text' => 'Provide a comprehensive description',
                'group' => 'basic_info',
                'display_order' => 2,
            ],
            [
                'name' => 'brand',
                'arabic_title' => 'العلامة التجارية',
                'title' => 'Brand',
                'description' => 'Brand or manufacturer name',
                'type' => 'select',
                'options' => ['Apple', 'Samsung', 'Sony', 'LG', 'Other'],
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'placeholder' => 'Select brand',
                'help_text' => 'Choose the brand from the list',
                'group' => 'basic_info',
                'display_order' => 3,
            ],
            [
                'name' => 'model',
                'arabic_title' => 'الموديل',
                'title' => 'Model',
                'description' => 'Model number or name',
                'type' => 'text',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_length' => 2,
                'max_length' => 100,
                'placeholder' => 'Enter model number',
                'help_text' => 'Enter the specific model number',
                'group' => 'basic_info',
                'display_order' => 4,
            ],

            // Specifications Group
            [
                'name' => 'color',
                'arabic_title' => 'اللون',
                'title' => 'Color',
                'description' => 'Available colors',
                'type' => 'multiselect',
                'options' => ['Black', 'White', 'Red', 'Blue', 'Green', 'Yellow', 'Silver', 'Gold'],
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => false,
                'min_selections' => 1,
                'max_selections' => 5,
                'placeholder' => 'Select colors',
                'help_text' => 'Choose one or more available colors',
                'group' => 'specifications',
                'display_order' => 1,
            ],
            [
                'name' => 'material',
                'arabic_title' => 'المادة',
                'title' => 'Material',
                'description' => 'Primary material used',
                'type' => 'select',
                'options' => ['Plastic', 'Metal', 'Glass', 'Wood', 'Fabric', 'Leather', 'Ceramic'],
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => false,
                'placeholder' => 'Select material',
                'help_text' => 'Choose the primary material',
                'group' => 'specifications',
                'display_order' => 2,
            ],
            [
                'name' => 'warranty',
                'arabic_title' => 'الضمان',
                'title' => 'Warranty',
                'description' => 'Warranty period in months',
                'type' => 'number',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 120,
                'unit' => 'months',
                'placeholder' => 'Enter warranty period',
                'help_text' => 'Enter warranty period in months',
                'group' => 'specifications',
                'display_order' => 3,
            ],
            [
                'name' => 'in_stock',
                'arabic_title' => 'متوفر في المخزون',
                'title' => 'In Stock',
                'description' => 'Availability status',
                'type' => 'checkbox',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => false,
                'help_text' => 'Check if item is currently in stock',
                'group' => 'specifications',
                'display_order' => 4,
            ],

            // Dimensions Group
            [
                'name' => 'length',
                'arabic_title' => 'الطول',
                'title' => 'Length',
                'description' => 'Length measurement',
                'type' => 'decimal',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 1000,
                'unit' => 'cm',
                'placeholder' => 'Enter length',
                'help_text' => 'Enter length in centimeters',
                'group' => 'dimensions',
                'display_order' => 1,
            ],
            [
                'name' => 'width',
                'arabic_title' => 'العرض',
                'title' => 'Width',
                'description' => 'Width measurement',
                'type' => 'decimal',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 1000,
                'unit' => 'cm',
                'placeholder' => 'Enter width',
                'help_text' => 'Enter width in centimeters',
                'group' => 'dimensions',
                'display_order' => 2,
            ],
            [
                'name' => 'height',
                'arabic_title' => 'الارتفاع',
                'title' => 'Height',
                'description' => 'Height measurement',
                'type' => 'decimal',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 1000,
                'unit' => 'cm',
                'placeholder' => 'Enter height',
                'help_text' => 'Enter height in centimeters',
                'group' => 'dimensions',
                'display_order' => 3,
            ],
            [
                'name' => 'weight',
                'arabic_title' => 'الوزن',
                'title' => 'Weight',
                'description' => 'Weight measurement',
                'type' => 'decimal',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 1000,
                'unit' => 'kg',
                'placeholder' => 'Enter weight',
                'help_text' => 'Enter weight in kilograms',
                'group' => 'dimensions',
                'display_order' => 4,
            ],

            // Pricing Group
            [
                'name' => 'price',
                'arabic_title' => 'السعر',
                'title' => 'Price',
                'description' => 'Current selling price',
                'type' => 'decimal',
                'is_required' => true,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 999999.99,
                'unit' => 'USD',
                'placeholder' => 'Enter price',
                'help_text' => 'Enter the current selling price',
                'group' => 'pricing',
                'display_order' => 1,
            ],
            [
                'name' => 'original_price',
                'arabic_title' => 'السعر الأصلي',
                'title' => 'Original Price',
                'description' => 'Original price before discount',
                'type' => 'decimal',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 999999.99,
                'unit' => 'USD',
                'placeholder' => 'Enter original price',
                'help_text' => 'Enter the original price before any discounts',
                'group' => 'pricing',
                'display_order' => 2,
            ],
            [
                'name' => 'discount_percentage',
                'arabic_title' => 'نسبة الخصم',
                'title' => 'Discount Percentage',
                'description' => 'Discount percentage',
                'type' => 'number',
                'is_required' => false,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'min_value' => 0,
                'max_value' => 100,
                'unit' => '%',
                'placeholder' => 'Enter discount percentage',
                'help_text' => 'Enter discount percentage (0-100)',
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
                'arabic_title' => 'الإلكترونيات',
                'title' => 'Electronics',
                'description' => 'Electronic devices and gadgets',
                'slug' => 'electronics',
                'display_order' => 1,
                'is_active' => true,
                'is_featured' => true,
            ]);

            $phones = Category::create([
                'name' => 'phones',
                'sector_id' => $sector->id,
                'parent_id' => $electronics->id,
                'arabic_title' => 'الهواتف',
                'title' => 'Phones',
                'description' => 'Mobile phones and smartphones',
                'slug' => 'phones',
                'display_order' => 1,
                'is_active' => true,
            ]);

            $laptops = Category::create([
                'name' => 'laptops',
                'sector_id' => $sector->id,
                'parent_id' => $electronics->id,
                'title' => 'أجهزة الكمبيوتر المحمولة',
                'description' => 'Laptops and portable computers',
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
