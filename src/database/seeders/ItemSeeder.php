<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Condition;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $conditionIds = [
            '良好' => 1,
            '目立った傷や汚れなし' => 2,
            'やや傷や汚れあり' => 3,
            '状態が悪い' => 4,
        ];

        $itemsData = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand_name' => 'Rolex',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition_id' => $conditionIds['良好'],
                'user_id' => 1,
                'category_id' => 1, // ★追加
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_url' =>'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition_id' => $conditionIds['目立った傷や汚れなし'],
                'user_id' => 1,
                'category_id' => 2, // ★追加
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand_name' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition_id' => $conditionIds['やや傷や汚れあり'],
                'user_id' => 1,
                'category_id' => 3, // ★追加
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand_name' => null,
                'description' => 'クラッシックなデザインの革靴',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition_id' => $conditionIds['状態が悪い'],
                'user_id' => 1,
                'category_id' => 1, // ★追加
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand_name' => null,
                'description' => '高性能なノートパソコン',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition_id' => $conditionIds['良好'],
                'user_id' => 1,
                'category_id' => 2, // ★追加
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand_name' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition_id' => $conditionIds['目立った傷や汚れなし'],
                'user_id' => 1,
                'category_id' => 2, // ★追加
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition_id' => $conditionIds['やや傷や汚れあり'],
                'user_id' => 1,
                'category_id' => 1, // ★追加
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand_name' => 'なし',
                'description' => '使いやすいタンブラー',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition_id' => $conditionIds['状態が悪い'],
                'user_id' => 1,
                'category_id' => 4, // ★追加
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition_id' => $conditionIds['良好'],
                'user_id' => 1,
                'category_id' => 4, // ★追加
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand_name' => null,
                'description' => '便利なメイクアップセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition_id' => $conditionIds['目立った傷や汚れなし'],
                'user_id' => 1,
                'category_id' => 1, // 元々あった記述
            ],
        ];

        foreach ($itemsData as $data) {
            Item::create($data);
        }
    }
}