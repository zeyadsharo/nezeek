<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if sectors already exist
        if (Sector::count() > 0) {
            return;
        }

        // Create sectors with Arabic titles and descriptions
        $sectors = [
            [
                'id' => 1,
                'title' => 'قاعات أفراح',
                'description' => 'قاعات الاحتفالات والمناسبات السعيدة',
                'display_order' => 1,
                'icon' => 'heroicon-o-heart',
                'display_state' => true,
                'activation_state' => true,
            ],
            [
                'id' => 2,
                'title' => 'مطاعم',
                'description' => 'المطاعم والمقاهي والمطاعم الشعبية',
                'display_order' => 2,
                'icon' => 'heroicon-o-cake',
                'display_state' => true,
                'activation_state' => true,
            ],
            [
                'id' => 3,
                'title' => 'معارض',
                'description' => 'معارض السيارات والأثاث والإلكترونيات',
                'display_order' => 3,
                'icon' => 'heroicon-o-building-storefront',
                'display_state' => true,
                'activation_state' => true,
            ],
            [
                'id' => 4,
                'title' => 'أطباء',
                'description' => 'العيادات الطبية والمستشفيات والصيدليات',
                'display_order' => 4,
                'icon' => 'heroicon-o-heart',
                'display_state' => true,
                'activation_state' => true,
            ],
            [
                'id' => 5,
                'title' => 'محلات تجارية',
                'description' => 'المحلات التجارية والأسواق والمراكز التجارية',
                'display_order' => 5,
                'icon' => 'heroicon-o-shopping-bag',
                'display_state' => true,
                'activation_state' => true,
            ],
            [
                'id' => 6,
                'title' => 'خدمات تعليمية',
                'description' => 'المدارس والجامعات والمعاهد التدريبية',
                'display_order' => 6,
                'icon' => 'heroicon-o-academic-cap',
                'display_state' => true,
                'activation_state' => true,
            ],
            [
                'id' => 7,
                'title' => 'خدمات نقل',
                'description' => 'شركات النقل والمواصلات والتوصيل',
                'display_order' => 7,
                'icon' => 'heroicon-o-truck',
                'display_state' => true,
                'activation_state' => true,
            ],
            [
                'id' => 8,
                'title' => 'خدمات مالية',
                'description' => 'البنوك وشركات التأمين والصرافات',
                'display_order' => 8,
                'icon' => 'heroicon-o-credit-card',
                'display_state' => true,
                'activation_state' => true,
            ],
            [
                'id' => 9,
                'title' => 'خدمات قانونية',
                'description' => 'المحامون والمكاتب القانونية والاستشارات',
                'display_order' => 9,
                'icon' => 'heroicon-o-scale',
                'display_state' => true,
                'activation_state' => true,
            ],
            [
                'id' => 10,
                'title' => 'خدمات سياحية',
                'description' => 'وكالات السفر والفنادق والمنتجعات',
                'display_order' => 10,
                'icon' => 'heroicon-o-globe-alt',
                'display_state' => true,
                'activation_state' => true,
            ],
        ];

        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
    }
}
