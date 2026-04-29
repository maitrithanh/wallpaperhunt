<?php

namespace Database\Seeders;

use App\Models\Albums;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Photos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create Partners (Artists)
        $artists = [
            ['full_name' => 'Minh Tran', 'email' => 'minhtran@demo.com', 'password' => bcrypt('password'), 'status' => 1],
            ['full_name' => 'Linh Nguyen', 'email' => 'linhnguyen@demo.com', 'password' => bcrypt('password'), 'status' => 1],
            ['full_name' => 'Huy Le', 'email' => 'huyle@demo.com', 'password' => bcrypt('password'), 'status' => 1],
            ['full_name' => 'An Pham', 'email' => 'anpham@demo.com', 'password' => bcrypt('password'), 'status' => 1],
            ['full_name' => 'Mai Hoang', 'email' => 'maihoang@demo.com', 'password' => bcrypt('password'), 'status' => 1],
        ];

        $partnerIds = [];
        $partnerIds = [];
        foreach ($artists as $artist) {
            $p = Partner::updateOrCreate(['email' => $artist['email']], $artist);
            $partnerIds[] = $p->id;
        }

        // Create Categories
        $categories = [
            ['name' => 'Thiên nhiên', 'slug' => 'thien-nhien', 'description' => 'Phong cảnh thiên nhiên tuyệt đẹp', 'status' => 1, 'avatar' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=500&q=80'],
            ['name' => 'Anime', 'slug' => 'anime', 'description' => 'Hình nền anime và manga', 'status' => 1, 'avatar' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=500&q=80'],
            ['name' => 'Gaming', 'slug' => 'gaming', 'description' => 'Wallpaper game HOT nhất', 'status' => 1, 'avatar' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=500&q=80'],
            ['name' => 'Minimal', 'slug' => 'minimal', 'description' => 'Phong cách tối giản, tinh tế', 'status' => 1, 'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&q=80'],
            ['name' => 'Abstract', 'slug' => 'abstract', 'description' => 'Nghệ thuật trừu tượng sáng tạo', 'status' => 1, 'avatar' => 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=500&q=80'],
            ['name' => 'Dark', 'slug' => 'dark', 'description' => 'Hình nền tối, huyền bí', 'status' => 1, 'avatar' => 'https://images.unsplash.com/photo-1536859355448-76f92ebdc33d?w=500&q=80'],
            ['name' => 'Thành phố', 'slug' => 'thanh-pho', 'description' => 'Phong cảnh đô thị hiện đại', 'status' => 1, 'avatar' => 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=500&q=80'],
            ['name' => 'Vũ trụ', 'slug' => 'vu-tru', 'description' => 'Không gian và thiên hà bao la', 'status' => 1, 'avatar' => 'https://images.unsplash.com/photo-1462331940025-496dfbfc7564?w=500&q=80'],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $avatarPath = null;
            if (isset($cat['avatar']) && str_starts_with($cat['avatar'], 'http')) {
                try {
                    $contents = @file_get_contents($cat['avatar']);
                    if ($contents) {
                        $name = $cat['slug'] . '.jpg';
                        \Illuminate\Support\Facades\Storage::disk('public')->put('categories/' . $name, $contents);
                        $avatarPath = 'categories/' . $name;
                    }
                } catch (\Exception $e) {
                    $avatarPath = null;
                }
            }
            
            $c = Category::updateOrCreate(
                ['slug' => $cat['slug']], 
                array_merge($cat, ['avatar' => $avatarPath])
            );
            $categoryIds[$cat['slug']] = $c->id;
        }

        // Create Albums
        $albums = [
            ['name' => 'Hoàng hôn tuyệt đẹp', 'description' => 'Bộ sưu tập hoàng hôn rực rỡ', 'status' => 1, 'partner_id' => $partnerIds[0], 'wallpaper_count' => 6],
            ['name' => 'Cyberpunk City', 'description' => 'Thành phố tương lai cyberpunk', 'status' => 1, 'partner_id' => $partnerIds[1], 'wallpaper_count' => 5],
            ['name' => 'Thiên hà xa xôi', 'description' => 'Khám phá vũ trụ bao la', 'status' => 1, 'partner_id' => $partnerIds[2], 'wallpaper_count' => 5],
            ['name' => 'Minimal Art', 'description' => 'Nghệ thuật tối giản đẹp mắt', 'status' => 1, 'partner_id' => $partnerIds[3], 'wallpaper_count' => 4],
            ['name' => 'Anime Collection', 'description' => 'Bộ sưu tập anime đặc sắc', 'status' => 1, 'partner_id' => $partnerIds[4], 'wallpaper_count' => 5],
        ];

        $albumIds = [];
        foreach ($albums as $album) {
            $a = Albums::updateOrCreate(['name' => $album['name']], $album);
            $albumIds[] = $a->id;
        }

        // Unsplash image URLs by category for realistic images
        $unsplashImages = [
            'thien-nhien' => [
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=1920&q=80',
                'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=1920&q=80',
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1920&q=80',
                'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=1920&q=80',
                'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?w=1920&q=80',
                'https://images.unsplash.com/photo-1465056836900-8f1e940f1eac?w=1920&q=80',
                'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=1920&q=80',
                'https://images.unsplash.com/photo-1433086966358-54859d0ed716?w=1920&q=80',
            ],
            'anime' => [
                'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=1920&q=80',
                'https://images.unsplash.com/photo-1613376023733-0a73315d9b06?w=1920&q=80',
                'https://images.unsplash.com/photo-1607604276583-c1e1e4b3d320?w=1920&q=80',
                'https://images.unsplash.com/photo-1636955779321-819753cd1741?w=1920&q=80',
                'https://images.unsplash.com/photo-1560972550-aba3456b5564?w=1920&q=80',
            ],
            'gaming' => [
                'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1920&q=80',
                'https://images.unsplash.com/photo-1612287230202-1ff1d85d1bdf?w=1920&q=80',
                'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=1920&q=80',
                'https://images.unsplash.com/photo-1552820728-8b83bb6b2b28?w=1920&q=80',
                'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?w=1920&q=80',
            ],
            'minimal' => [
                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1920&q=80',
                'https://images.unsplash.com/photo-1494438639946-1ebd1d20bf85?w=1920&q=80',
                'https://images.unsplash.com/photo-1509023464722-18d996393ca8?w=1920&q=80',
                'https://images.unsplash.com/photo-1557682250-33bd709cbe85?w=1920&q=80',
                'https://images.unsplash.com/photo-1553356084-58ef4a67b2a7?w=1920&q=80',
            ],
            'abstract' => [
                'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=1920&q=80',
                'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?w=1920&q=80',
                'https://images.unsplash.com/photo-1558591710-4b4a1ae0f04d?w=1920&q=80',
                'https://images.unsplash.com/photo-1567095761054-7a02e69e5c43?w=1920&q=80',
                'https://images.unsplash.com/photo-1604076913837-52ab5f7c1ac4?w=1920&q=80',
            ],
            'dark' => [
                'https://images.unsplash.com/photo-1536859355448-76f92ebdc33d?w=1920&q=80',
                'https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?w=1920&q=80',
                'https://images.unsplash.com/photo-1534796636912-3b95b3ab5986?w=1920&q=80',
                'https://images.unsplash.com/photo-1557682224-5b8590cd9ec5?w=1920&q=80',
                'https://images.unsplash.com/photo-1507400492013-162706c8c05e?w=1920&q=80',
            ],
            'thanh-pho' => [
                'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=1920&q=80',
                'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?w=1920&q=80',
                'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=1920&q=80',
                'https://images.unsplash.com/photo-1514565131-fce0801e5785?w=1920&q=80',
                'https://images.unsplash.com/photo-1519501025264-65ba15a82390?w=1920&q=80',
            ],
            'vu-tru' => [
                'https://images.unsplash.com/photo-1462331940025-496dfbfc7564?w=1920&q=80',
                'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?w=1920&q=80',
                'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1920&q=80',
                'https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?w=1920&q=80',
                'https://images.unsplash.com/photo-1506318137071-a8e063b4bec0?w=1920&q=80',
            ],
        ];

        $wallpaperNames = [
            'thien-nhien' => ['Bình minh trên núi', 'Thung lũng xanh', 'Rừng sương mù', 'Cánh đồng hoa', 'Hồ trong xanh', 'Thác nước hùng vĩ', 'Đồi chè bậc thang', 'Cầu vồng sau mưa'],
            'anime' => ['Sakura Dreams', 'Neon Tokyo', 'Spirit World', 'Anime Sky', 'Chibi Fantasy'],
            'gaming' => ['Cyberpunk Arena', 'Neon Racer', 'Fantasy Quest', 'Space Battle', 'Pixel World'],
            'minimal' => ['Clean Lines', 'Simple Horizon', 'Gradient Flow', 'Pastel Waves', 'Mono Shapes'],
            'abstract' => ['Color Explosion', 'Fluid Art', 'Geometric Chaos', 'Digital Dreams', 'Paint Splash'],
            'dark' => ['Deep Dark', 'Midnight Glow', 'Shadow Play', 'Dark Ocean', 'Night Sky'],
            'thanh-pho' => ['Skyline Night', 'Urban Jungle', 'City Lights', 'Downtown View', 'Bridge at Dusk'],
            'vu-tru' => ['Nebula Glow', 'Galaxy Spiral', 'Earth from Space', 'Star Field', 'Aurora Borealis'],
        ];

        $albumMapping = [
            'thien-nhien' => $albumIds[0],
            'thanh-pho' => $albumIds[1],
            'vu-tru' => $albumIds[2],
            'minimal' => $albumIds[3],
            'anime' => $albumIds[4],
        ];

        foreach ($unsplashImages as $catSlug => $images) {
            $catId = $categoryIds[$catSlug];
            $names = $wallpaperNames[$catSlug];

            foreach ($images as $i => $url) {
                $photoPath = null;
                if (str_starts_with($url, 'http')) {
                    try {
                        $contents = @file_get_contents($url);
                        if ($contents) {
                            $name = time() . '_' . uniqid() . '.jpg';
                            \Illuminate\Support\Facades\Storage::disk('public')->put('photos/' . $name, $contents);
                            $photoPath = 'photos/' . $name;
                        }
                    } catch (\Exception $e) {
                        $photoPath = null;
                    }
                }

                if ($photoPath) {
                    Photos::updateOrCreate(['src' => $photoPath], [
                        'name' => $names[$i] ?? "Wallpaper " . ($i + 1),
                        'description' => "Hình nền {$catSlug} chất lượng cao, phù hợp cho desktop và điện thoại.",
                        'category_id' => $catId,
                        'partner_id' => $partnerIds[array_rand($partnerIds)],
                        'album_id' => $albumMapping[$catSlug] ?? $albumIds[array_rand($albumIds)],
                        'status' => 1,
                        'view_count' => rand(100, 15000),
                        'like_count' => rand(10, 2000),
                        'price' => rand(0, 1) ? 0 : rand(10, 50) * 1000,
                    ]);
                }
            }
        }

        $this->command->info('✅ Demo data seeded: ' . count($partnerIds) . ' artists, ' . count($categoryIds) . ' categories, ' . count($albumIds) . ' albums, ' . Photos::count() . ' wallpapers');
    }
}
