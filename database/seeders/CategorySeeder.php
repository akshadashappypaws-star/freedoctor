<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category_name' => 'Rural Camp',
                'icon_class' => 'agriculture',
                'description' => 'Healthcare camps in rural and remote areas'
            ],
            [
                'category_name' => 'Dental Camp',
                'icon_class' => 'medical_services',
                'description' => 'Dental checkups and treatments'
            ],
            [
                'category_name' => 'Mental Health Camp',
                'icon_class' => 'psychology',
                'description' => 'Mental health awareness and counseling'
            ],
            [
                'category_name' => 'Heart Camp',
                'icon_class' => 'favorite',
                'description' => 'Cardiac health checkups and screenings'
            ],
            [
                'category_name' => 'Eye Camp',
                'icon_class' => 'visibility',
                'description' => 'Eye checkups and vision screening'
            ],
            [
                'category_name' => 'Women Camp',
                'icon_class' => 'female',
                'description' => 'Women-specific healthcare services'
            ],
            [
                'category_name' => 'Children Camp',
                'icon_class' => 'child_care',
                'description' => 'Pediatric healthcare and immunization'
            ],
            [
                'category_name' => 'Elderly Camp',
                'icon_class' => 'elderly',
                'description' => 'Healthcare services for senior citizens'
            ],
            [
                'category_name' => 'General Camp',
                'icon_class' => 'local_hospital',
                'description' => 'General healthcare and basic checkups'
            ],
            [
                'category_name' => 'Emergency Camp',
                'icon_class' => 'emergency',
                'description' => 'Emergency medical services and first aid'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
