<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Condition;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ItemSellTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_item()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $categories = Category::factory()->count(3)->create();

        $data = [
            'name' => '出品テスト商品',
            'description' => 'これは出品テスト用の商品説明です。',
            'price' => 1000,
            'categories' => $categories->pluck('id')->toArray(),
            'condition_id' => $condition->id,
            'brand_name' => 'テストブランド',
            'item_image' => UploadedFile::fake()->create('test.jpg'),
        ];

        $response = $this->actingAs($user)->post('/sell', $data);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('items', [
            'name' => '出品テスト商品',
        ]);

        $item = \App\Models\Item::where('name', '出品テスト商品')->first();

        foreach ($categories as $category) {
        $this->assertDatabaseHas('item_category', [
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);
    }
}
}