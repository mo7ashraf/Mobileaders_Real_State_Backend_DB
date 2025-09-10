<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\Favorite;
use App\Models\Listing;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Policy;
use App\Models\PropertyRequest;
use App\Models\SellerProfile;
use App\Models\SupportSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $users = collect([
            ['id' => (string) Str::uuid(), 'name' => 'أحمد', 'phone' => '0511111111'],
            ['id' => (string) Str::uuid(), 'name' => 'سارة', 'phone' => '0522222222'],
            ['id' => (string) Str::uuid(), 'name' => 'محمد', 'phone' => '0533333333'],
            ['id' => (string) Str::uuid(), 'name' => 'ليلى', 'phone' => '0544444444'],
            ['id' => (string) Str::uuid(), 'name' => 'خالد', 'phone' => '0555555555'],
        ])->map(function ($u) {
            return array_merge($u, [
                'createdAt' => now(),
                'accRole' => 'user',
            ]);
        });
        foreach ($users as $u) {
            User::updateOrCreate(['id' => $u['id']], $u);
            SellerProfile::updateOrCreate(
                ['id' => (string) Str::uuid()],
                [
                    'userId' => $u['id'],
                    'verified' => fake()->boolean(),
                    'clients' => fake()->numberBetween(0, 200),
                    'rating' => fake()->randomFloat(1, 3, 5),
                    'badges' => json_encode(['موثّق']),
                    'joinedHijri' => '1446',
                    'joinedText' => now()->toDateString(),
                    'regionText' => 'الرياض',
                ],
            );
        }

        // Listings
        $cities = ['الرياض', 'جدة', 'الدمام', 'مكة', 'المدينة'];
        for ($i = 0; $i < 12; $i++) {
            Listing::updateOrCreate(
                ['id' => (string) Str::uuid()],
                [
                    'sellerId' => $users->random()['id'],
                    'title' => 'إعلان #' . ($i + 1),
                    'address' => 'عنوان تجريبي ' . ($i + 1),
                    'city' => $cities[array_rand($cities)],
                    'price' => fake()->numberBetween(200000, 1500000),
                    'bedrooms' => fake()->numberBetween(1, 5),
                    'bathrooms' => fake()->numberBetween(1, 4),
                    'areaSqm' => fake()->numberBetween(50, 400),
                    'status' => fake()->randomElement(['rent', 'sell']),
                    'category' => fake()->randomElement(['apartment', 'villa', 'office', 'resthouse']),
                    'imageUrl' => 'https://picsum.photos/seed/' . Str::random(8) . '/800/600',
                    'tags' => json_encode(['مدفوع']),
                    'createdAt' => now()->subDays(rand(0, 14)),
                ],
            );
        }

        // Orders
        foreach ($users->take(3) as $u) {
            Order::updateOrCreate(
                ['id' => (string) Str::uuid()],
                [
                    'userId' => $u['id'],
                    'status' => fake()->randomElement(['open', 'closed']),
                    'notes' => 'طلب تجريبي للمستخدم ' . $u['name'],
                    'createdAt' => now()->subDays(rand(0, 7)),
                ],
            );
        }

        // Property Requests
        foreach ($users->take(4) as $u) {
            PropertyRequest::updateOrCreate(
                ['id' => (string) Str::uuid()],
                [
                    'userId' => $u['id'],
                    'type' => fake()->randomElement(['buy', 'rent']),
                    'city' => $cities[array_rand($cities)],
                    'budgetMin' => 100000,
                    'budgetMax' => 800000,
                    'bedrooms' => 3,
                    'bathrooms' => 2,
                    'notes' => 'احتياج عقاري تجريبي',
                    'status' => 'open',
                    'createdAt' => now()->subDays(rand(0, 10)),
                ],
            );
        }

        // Notifications
        foreach ($users->take(3) as $u) {
            Notification::updateOrCreate(
                ['id' => (string) Str::uuid()],
                [
                    'userId' => $u['id'],
                    'title' => 'تنبيه جديد',
                    'subtitle' => 'رسالة ترحيبية',
                    'starred' => fake()->boolean(),
                    'readAt' => null,
                    'createdAt' => now(),
                ],
            );
        }

        // Favorites
        foreach ($users->take(2) as $u) {
            $listing = Listing::inRandomOrder()->first();
            if ($listing) {
                Favorite::updateOrCreate([
                    'userId' => $u['id'],
                    'listingId' => $listing->id,
                ]);
            }
        }

        // Settings & Policies
        AppSetting::updateOrCreate(['id' => 1], [
            'language' => 'ar',
            'theme' => 'system',
            'notifications' => json_encode(['enabled' => true]),
            'privacy' => json_encode(['shareData' => false]),
        ]);

        SupportSetting::updateOrCreate(['id' => 1], [
            'whatsapp' => '+966500000000',
            'email' => 'support@example.com',
        ]);

        Policy::updateOrCreate(['slug' => 'terms'], [
            'title' => 'الشروط والأحكام',
            'contentMd' => '# شروط الخدمة',
        ]);
        Policy::updateOrCreate(['slug' => 'privacy'], [
            'title' => 'سياسة الخصوصية',
            'contentMd' => '# خصوصيتك تهمنا',
        ]);
    }
}

