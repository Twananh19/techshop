<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;

class SlidersSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Sale Lớn Cuối Năm',
                'subtitle' => 'Giảm giá lên đến 50% cho tất cả sản phẩm',
                'image' => 'https://via.placeholder.com/1920x600/3B82F6/FFFFFF?text=Sale+50%25',
                'link' => '/products',
                'order' => 1,
                'status' => 'active',
            ],
            [
                'title' => 'Tai Nghe Cao Cấp',
                'subtitle' => 'Trải nghiệm âm thanh đỉnh cao với Sony, Bose, Apple',
                'image' => 'https://via.placeholder.com/1920x600/10B981/FFFFFF?text=Premium+Headphones',
                'link' => '/category/tai-nghe',
                'order' => 2,
                'status' => 'active',
            ],
            [
                'title' => 'Màn Hình Gaming',
                'subtitle' => 'Refresh rate cao, độ trễ thấp cho game thủ',
                'image' => 'https://via.placeholder.com/1920x600/8B5CF6/FFFFFF?text=Gaming+Monitors',
                'link' => '/category/man-hinh',
                'order' => 3,
                'status' => 'active',
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
