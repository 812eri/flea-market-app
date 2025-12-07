<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();

        \Illuminate\Support\Facades\View::share('errors', new \Illuminate\Support\ViewErrorBag);
    }

    public function test_mypage_displays_correct_info()
    {
        $user = User::factory()->create(['name' => 'マイページ太郎']);

        Item::factory()->create(['user_id' => $user->id, 'name' => '俺の出品物']);

        Item::factory()->create([
            'buyer_id' => $user->id,
            'name' => '俺の購入物',
            'status' => 'sold'
        ]);
        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);

        $response->assertSee('マイページ太郎');

        $response->assertSee('俺の出品物');
    }

    public function test_profile_edit_page_shows_initial_values()
    {
        $user = User::factory()->create(['name' => '変更前の名前']);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee('変更前の名前');
    }
}
