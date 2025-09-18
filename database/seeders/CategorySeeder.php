<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['slug'=>'apartment', 'name'=>'شقق',      'icon'=>'apartment_outlined',            'sortOrder'=>1, 'enabled'=>true],
            ['slug'=>'villa',     'name'=>'فلل',      'icon'=>'house_outlined',                'sortOrder'=>2, 'enabled'=>true],
            ['slug'=>'office',    'name'=>'مكاتب',    'icon'=>'corporate_fare_outlined',       'sortOrder'=>3, 'enabled'=>true],
            ['slug'=>'land',      'name'=>'أراضي',    'icon'=>'villa_outlined',                'sortOrder'=>4, 'enabled'=>true],
            ['slug'=>'shop',      'name'=>'محلات',    'icon'=>'store_mall_directory_outlined', 'sortOrder'=>5, 'enabled'=>true],
            ['slug'=>'resthouse', 'name'=>'استراحات', 'icon'=>'hotel_outlined',               'sortOrder'=>6, 'enabled'=>true],
        ];

        foreach ($items as $it) {
            Category::updateOrCreate(['slug'=>$it['slug']], $it);
        }
    }
}

