<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    public function test_toggle_like()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->post("/item/{$item->id}/like");

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user)->delete("/item/{$item->id}/like");

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_send_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $commentText = 'これはテストコメントです。';

        $response = $this->actingAs($user)->post("/item/{$item->id}/comment", [
            'comment_body' => $commentText,
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => $commentText,
        ]);
    }

    public function test_purchase_item_completes()
    {
        $this->withoutMiddleware();

        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $seller->id, 'status' => 'selling']);

        \App\Models\Address::create([
            'user_id' => $buyer->id,
            'post_code' => '123-4567',
            'street_address' => '東京都テスト区テスト1-1-1',
            'building_name' => 'テストビル',
        ]);

        $response = $this->actingAs($buyer)->post("/item/{$item->id}/purchase", [
            'payment_method' => 'credit',
            'address_id'=> 1,
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertStatus(303);
    }
}