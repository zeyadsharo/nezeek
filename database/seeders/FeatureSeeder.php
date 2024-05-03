<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'arabic_title' => 'منشورات',
                'kurdish_title' => 'پەڕەکان',
                'description' => 'منشورات جميلة تجذب العملاء',
            ],
            [
                'arabic_title' => 'المواعيد',
                'kurdish_title' => 'کاتێکی',
                'description' => 'مواعيد دقيقة',
            ],
            [
                'arabic_title' => 'المنتجات',
                'kurdish_title' => 'پەڕەکان',
                'description' => 'منتجات عالية الجودة',
            ],
            [
                    'arabic_title' => 'فئة المنتجات',
                    'kurdish_title' => 'کەتێگۆریی پەڕەکان',
                    'description' => 'تصنيف المنتجات حسب الفئات',
            ]
        ];

        foreach ($features as $feature) {
            //add if condition to check if the feature already exists
            \App\Models\Feature::where('arabic_title', $feature['arabic_title'])->first() ?: \App\Models\Feature::create($feature);
        }
    }
}
