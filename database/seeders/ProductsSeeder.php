<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\InventoryItem;
use App\Models\Category;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $productsByCategory = [
            'Tai Nghe' => [
                ['name' => 'Tai nghe Sony WH-1000XM5', 'price' => 8990000, 'description' => 'Tai nghe chống ồn cao cấp với chất lượng âm thanh tuyệt vời', 'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&h=800&fit=crop'],
                ['name' => 'Tai nghe Apple AirPods Pro 2', 'price' => 6490000, 'description' => 'Tai nghe true wireless cao cấp của Apple với ANC', 'image' => 'https://images.unsplash.com/photo-1606841837239-c5a1a4a07af7?w=800&h=800&fit=crop'],
                ['name' => 'Tai nghe JBL Tune 770NC', 'price' => 2990000, 'description' => 'Tai nghe over-ear với công nghệ chống ồn chủ động', 'image' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&h=800&fit=crop'],
                ['name' => 'Tai nghe Samsung Galaxy Buds2 Pro', 'price' => 4490000, 'description' => 'Tai nghe không dây cao cấp với ANC thông minh', 'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&h=800&fit=crop'],
                ['name' => 'Tai nghe Bose QuietComfort 45', 'price' => 7990000, 'description' => 'Tai nghe chống ồn hàng đầu từ Bose', 'image' => 'https://images.unsplash.com/photo-1545127398-14699f92334b?w=800&h=800&fit=crop'],
                ['name' => 'Tai nghe Sennheiser Momentum 4', 'price' => 8490000, 'description' => 'Tai nghe premium với pin 60 giờ', 'image' => 'https://images.unsplash.com/photo-1487215078519-e21cc028cb29?w=800&h=800&fit=crop'],
                ['name' => 'Tai nghe Xiaomi Redmi Buds 4 Pro', 'price' => 1790000, 'description' => 'Tai nghe không dây giá rẻ chất lượng tốt', 'image' => 'https://images.unsplash.com/photo-1613040809024-b4ef7ba99bc3?w=800&h=800&fit=crop'],
                ['name' => 'Tai nghe Logitech G733', 'price' => 3490000, 'description' => 'Tai nghe gaming không dây RGB', 'image' => 'https://images.unsplash.com/photo-1599669454699-248893623440?w=800&h=800&fit=crop'],
                ['name' => 'Tai nghe Razer Kraken V3 Pro', 'price' => 5990000, 'description' => 'Tai nghe gaming cao cấp với âm thanh 7.1', 'image' => 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?w=800&h=800&fit=crop'],
                ['name' => 'Tai nghe HyperX Cloud Alpha', 'price' => 2490000, 'description' => 'Tai nghe gaming với dual chamber drivers', 'image' => 'https://images.unsplash.com/photo-1612287230202-1ff1d85d1bdf?w=800&h=800&fit=crop'],
            ],
            'Màn hình' => [
                ['name' => 'Màn hình LG UltraGear 27GN950', 'price' => 16990000, 'description' => 'Màn hình gaming 4K 144Hz với HDR', 'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&h=800&fit=crop'],
                ['name' => 'Màn hình ASUS ROG Swift PG279QM', 'price' => 14990000, 'description' => 'Màn hình gaming 240Hz G-Sync', 'image' => 'https://images.unsplash.com/photo-1585792180666-f7347c490ee2?w=800&h=800&fit=crop'],
                ['name' => 'Màn hình Dell UltraSharp U2723DE', 'price' => 12990000, 'description' => 'Màn hình văn phòng QHD IPS USB-C', 'image' => 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?w=800&h=800&fit=crop'],
                ['name' => 'Màn hình Samsung Odyssey G7', 'price' => 11990000, 'description' => 'Màn hình cong gaming 240Hz QLED', 'image' => 'https://images.unsplash.com/photo-1580982324660-8cf3fc01d0d4?w=800&h=800&fit=crop'],
                ['name' => 'Màn hình BenQ PD2725U', 'price' => 15990000, 'description' => 'Màn hình thiết kế đồ họa 4K chuyên nghiệp', 'image' => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=800&h=800&fit=crop'],
                ['name' => 'Màn hình AOC 24G2', 'price' => 4990000, 'description' => 'Màn hình gaming giá rẻ 144Hz IPS', 'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&h=800&fit=crop'],
                ['name' => 'Màn hình ViewSonic VX2718-2KPC', 'price' => 5490000, 'description' => 'Màn hình cong QHD 165Hz', 'image' => 'https://images.unsplash.com/photo-1543512214-318c7553f230?w=800&h=800&fit=crop'],
                ['name' => 'Màn hình MSI Optix MAG274QRF', 'price' => 8990000, 'description' => 'Màn hình gaming QHD 165Hz Rapid IPS', 'image' => 'https://images.unsplash.com/photo-1586210579191-33b45e38fa8c?w=800&h=800&fit=crop'],
                ['name' => 'Màn hình Gigabyte M27Q', 'price' => 7490000, 'description' => 'Màn hình QHD 170Hz KVM Switch', 'image' => 'https://images.unsplash.com/photo-1609921212029-bb5a28e60960?w=800&h=800&fit=crop'],
                ['name' => 'Màn hình Philips 276E8VJSB', 'price' => 3990000, 'description' => 'Màn hình 4K IPS giá tốt', 'image' => 'https://images.unsplash.com/photo-1527443195645-1133f7f28990?w=800&h=800&fit=crop'],
            ],
            'Sạc pin' => [
                ['name' => 'Sạc Anker PowerPort III 65W', 'price' => 790000, 'description' => 'Sạc nhanh USB-C công suất 65W', 'image' => 'https://images.unsplash.com/photo-1591290619762-c588085880de?w=800&h=800&fit=crop'],
                ['name' => 'Sạc Baseus GaN 100W', 'price' => 1290000, 'description' => 'Sạc GaN 100W 4 cổng sạc nhanh', 'image' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=800&h=800&fit=crop'],
                ['name' => 'Sạc Apple 20W USB-C', 'price' => 590000, 'description' => 'Sạc nhanh chính hãng Apple 20W', 'image' => 'https://images.unsplash.com/photo-1604671801908-6f0c6a092c05?w=800&h=800&fit=crop'],
                ['name' => 'Sạc Samsung 45W', 'price' => 690000, 'description' => 'Sạc siêu nhanh 45W cho Samsung', 'image' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=800&h=800&fit=crop'],
                ['name' => 'Sạc Ugreen 65W GaN', 'price' => 890000, 'description' => 'Sạc GaN nhỏ gọn 3 cổng', 'image' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=800&h=800&fit=crop'],
                ['name' => 'Sạc Xiaomi 67W', 'price' => 490000, 'description' => 'Sạc nhanh 67W cho Xiaomi', 'image' => 'https://images.unsplash.com/photo-1611078489935-0cb964de46d6?w=800&h=800&fit=crop'],
                ['name' => 'Sạc không dây Samsung 15W', 'price' => 990000, 'description' => 'Đế sạc không dây nhanh 15W', 'image' => 'https://images.unsplash.com/photo-1591719430342-a184c8284f92?w=800&h=800&fit=crop'],
                ['name' => 'Sạc không dây Anker 313', 'price' => 690000, 'description' => 'Đế sạc không dây Qi 10W', 'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&h=800&fit=crop'],
                ['name' => 'Sạc dự phòng Anker 325 20000mAh', 'price' => 1190000, 'description' => 'Pin sạc dự phòng 20000mAh PD 20W', 'image' => 'https://images.unsplash.com/photo-1609592363626-b8c1b3a7d3f1?w=800&h=800&fit=crop'],
                ['name' => 'Sạc dự phòng Xiaomi 10000mAh', 'price' => 490000, 'description' => 'Pin sạc dự phòng nhỏ gọn 10000mAh', 'image' => 'https://images.unsplash.com/photo-1606402179428-a57976d71fa4?w=800&h=800&fit=crop'],
            ],
            'Bàn phím' => [
                ['name' => 'Bàn phím Logitech MX Keys', 'price' => 2790000, 'description' => 'Bàn phím văn phòng cao cấp với đèn nền', 'image' => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=800&h=800&fit=crop'],
                ['name' => 'Bàn phím Keychron K8 Pro', 'price' => 2990000, 'description' => 'Bàn phím cơ hot-swap QMK/VIA', 'image' => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&h=800&fit=crop'],
                ['name' => 'Bàn phím Razer BlackWidow V3', 'price' => 3490000, 'description' => 'Bàn phím gaming cơ học RGB', 'image' => 'https://images.unsplash.com/photo-1601445638532-3c6f6c8aa33d?w=800&h=800&fit=crop'],
                ['name' => 'Bàn phím Corsair K70 RGB', 'price' => 3990000, 'description' => 'Bàn phím gaming cơ học Cherry MX', 'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&h=800&fit=crop'],
                ['name' => 'Bàn phím ASUS ROG Azoth', 'price' => 5990000, 'description' => 'Bàn phím gaming 75% cao cấp OLED', 'image' => 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=800&h=800&fit=crop'],
                ['name' => 'Bàn phím Leopold FC900R', 'price' => 2490000, 'description' => 'Bàn phím cơ Cherry MX chất lượng cao', 'image' => 'https://images.unsplash.com/photo-1560762484-813fc97650a0?w=800&h=800&fit=crop'],
                ['name' => 'Bàn phím Akko 3098B', 'price' => 1290000, 'description' => 'Bàn phím cơ bluetooth giá tốt', 'image' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=800&h=800&fit=crop'],
                ['name' => 'Bàn phím Ducky One 3', 'price' => 2690000, 'description' => 'Bàn phím cơ hot-swap Cherry MX', 'image' => 'https://images.unsplash.com/photo-1595044426077-d36d9236d54a?w=800&h=800&fit=crop'],
                ['name' => 'Bàn phím Anne Pro 2', 'price' => 1790000, 'description' => 'Bàn phím 60% bluetooth RGB', 'image' => 'https://images.unsplash.com/photo-1624705002806-5d72df19c3ad?w=800&h=800&fit=crop'],
                ['name' => 'Bàn phím Royal Kludge RK61', 'price' => 890000, 'description' => 'Bàn phím cơ 60% giá rẻ', 'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&h=800&fit=crop'],
            ],
            'Chuột' => [
                ['name' => 'Chuột Logitech MX Master 3S', 'price' => 2490000, 'description' => 'Chuột văn phòng cao cấp đa thiết bị', 'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&h=800&fit=crop'],
                ['name' => 'Chuột Razer DeathAdder V3 Pro', 'price' => 3990000, 'description' => 'Chuột gaming không dây cao cấp', 'image' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&h=800&fit=crop'],
                ['name' => 'Chuột Logitech G Pro X Superlight', 'price' => 3490000, 'description' => 'Chuột gaming siêu nhẹ 63g', 'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&h=800&fit=crop'],
                ['name' => 'Chuột SteelSeries Rival 5', 'price' => 1490000, 'description' => 'Chuột gaming 9 nút bấm', 'image' => 'https://images.unsplash.com/photo-1585752975537-6661908d2bb4?w=800&h=800&fit=crop'],
                ['name' => 'Chuột Corsair Dark Core RGB Pro', 'price' => 2490000, 'description' => 'Chuột gaming wireless RGB', 'image' => 'https://images.unsplash.com/photo-1600080972464-8e5f35f63d08?w=800&h=800&fit=crop'],
                ['name' => 'Chuột ASUS ROG Gladius III', 'price' => 1990000, 'description' => 'Chuột gaming hot-swap switch', 'image' => 'https://images.unsplash.com/photo-1629131726692-1accd0c53ce0?w=800&h=800&fit=crop'],
                ['name' => 'Chuột HyperX Pulsefire Haste', 'price' => 990000, 'description' => 'Chuột gaming siêu nhẹ honeycomb', 'image' => 'https://images.unsplash.com/photo-1613141411244-0e4de6b3b346?w=800&h=800&fit=crop'],
                ['name' => 'Chuột Glorious Model O', 'price' => 1190000, 'description' => 'Chuột gaming nhẹ 67g RGB', 'image' => 'https://images.unsplash.com/photo-1563297007-0686b7003af7?w=800&h=800&fit=crop'],
                ['name' => 'Chuột Logitech G304', 'price' => 690000, 'description' => 'Chuột gaming wireless giá rẻ', 'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=800&h=800&fit=crop'],
                ['name' => 'Chuột Razer Viper Mini', 'price' => 790000, 'description' => 'Chuột gaming nhỏ gọn giá tốt', 'image' => 'https://images.unsplash.com/photo-1570056472165-021bfba17a06?w=800&h=800&fit=crop'],
            ],
        ];

        foreach ($categories as $category) {
            $products = $productsByCategory[$category->name] ?? [];
            
            foreach ($products as $index => $productData) {
                $stockQty = rand(10, 100);
                
                // Tạo inventory item trước
                $inventory = InventoryItem::create([
                    'name' => $productData['name'],
                    'sku' => strtoupper(str_replace('-', '', $category->slug)) . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'description' => $productData['description'],
                    'brand' => explode(' ', $productData['name'])[1] ?? 'TechShop', // Lấy từ thứ 2 làm brand
                    'category_id' => $category->id,
                    'cost_price' => $productData['price'] * 0.7, // Giá vốn = 70% giá bán
                    'stock_quantity' => $stockQty,
                ]);

                // Tạo sản phẩm link với inventory
                Product::create([
                    'inventory_item_id' => $inventory->id,
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'image' => $productData['image'] ?? null,
                    'stock' => $stockQty,
                    'status' => 'active',
                    'is_featured' => $index < 3 ? 1 : 0, // 3 sản phẩm đầu mỗi danh mục là featured
                    'published_at' => now(),
                ]);
            }
        }
    }
}
