<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDisplayTest extends TestCase
{
use RefreshDatabase;

    public function test_item_list_hides_own_items()
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        Item::factory()->create(['user_id' => $me->id, 'name' => '自分の商品']);
        Item::factory()->create(['user_id' => $other->id, 'name' => '他人の商品']);

        $response = $this->actingAs($me)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }

    public function test_sold_item_display()
    {
        // statusなどを利用して「売り切れ」状態の商品を作る
        // ※statusカラムがない場合は、create時に適切に調整してください
        Item::factory()->create(['name' => '売り切れ商品', 'status' => 'sold']); 

        $response = $this->get('/');
        $response->assertSee('Sold');
    }

    public function test_search_items()
    {
        Item::factory()->create(['name' => 'iPhone 13']);
        Item::factory()->create(['name' => 'MacBook Pro']);

        $response = $this->get('/?keyword=iPhone');

        $response->assertSee('iPhone 13');
        $response->assertDontSee('MacBook Pro');
    }

    public function test_item_detail_display()
    {
        $item = Item::factory()->create([
            'name' => '詳細テスト商品',
            'description' => '詳しい説明文です',
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('詳細テスト商品');
    }
}