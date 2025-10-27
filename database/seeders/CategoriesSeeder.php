<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tai Nghe',
                'description' => 'Tai nghe, headphone các loại',
            ],
            [
                'name' => 'Màn hình',
                'description' => 'Màn hình máy tính gaming, văn phòng',
            ],
            [
                'name' => 'Sạc pin',
                'description' => 'Sạc, pin dự phòng, sạc không dây',
            ],
            [
                'name' => 'Bàn phím',
                'description' => 'Bàn phím cơ, bàn phím gaming',
            ],
            [
                'name' => 'Chuột',
                'description' => 'Chuột gaming, chuột văn phòng',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'status' => 'active',
            ]);
        }
    }
}
