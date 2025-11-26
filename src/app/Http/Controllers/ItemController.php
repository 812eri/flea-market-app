<?php

namespace App\Http\Controllers;

use App\http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $tab = $request->input('tab', 'recommended');
        $query = Item::query();

        if ($tab ==='mylist') {
            if (Auth::check()) {
                $query->whereHas('likes', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            }else {
                $items = collect();
                return view('item.index', [
                    'items' => $items,
                    'current_tab' => $tab,
                    'keyword' => $keyword,
                ]);
            }
        }

        if ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        $items = $query->latest()->get();

        return view('item.index', [
            'items' => $items,
            'current_tab' => $tab,
            'keyword' => $keyword,
        ]);
    }

    public function create()
    {
        $categories = collect(Category::all())->pluck('name', 'id')->all();
        $conditions = collect(Condition::all())->pluck('name', 'id')->all();

        return view('item.create', [
            'categories' => $categories ?? [],
            'conditions' => $conditions ?? [],
        ]);
    }

    public function store(ExhibitionRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $user) {
            $imagePath = $request->file('item_image')->store('items', 'public');
            $imageUrl = Storage::url($imagePath);

            $item = Item::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'brand_name' => $validated['brand_name'] ?? null,
                'condition_id' => $validated['condition_id'],
                'image_url' => $imageUrl,
            ]);

            if (isset($validated['categories'])) {
                $item->categories()->attach($validated['categories']);
            }
        });

        return redirect()->route('home')->with('success', '商品を出品しました。');
    }

    public function purchaseShow($item_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', '購入するにはログインが必要です。');
        }

        $item = Item::findOrFail($item_id);
        $user_id = Auth::id();

        if ($item->is_sold) {
            return redirect()->route('item.show',$item->id)->with('error', 'この商品はすでに購入されています。');
        }
        if ($item->user_id === $user_id) {
            return redirect()->route('item.show',$item->id)->with('error', 'ご自身が出品した商品は購入できません。');
        }

        $address = Address::where('user_id', $user_id)->latest()->first();

        $selectedPaymentMethod = null;

        return view('purchase.show', [
            'item' => $item,
            'address' => $address,
            'selectedPaymentMethod' => $selectedPaymentMethod,
        ]);
    }

    public function purchase(Request $request, $item_id)
    {
        $user = Auth::user();

        $request->validate([
            'payment_method' => ['required', 'string', 'in:conbini,credit'],
        ],[
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '無効な支払い方法が選択されました。',
        ]);

        $item = Item::findOrFail($item_id);

        if ($item->is_sold || $item->user_id === $user->id) {
            return redirect()->route('item.show', $item_id)->with('error', '購入できない商品です。');
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('purchase.complete') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('item.show', $item->id),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => $user->id,
            ],
        ]);

        return redirect($session->url, 303);
    }

    public function purchaseComplete(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('home')->with('error', '決済情報が見つかりませんでした。');
        }

        try {
            $session = Session::retrieve($sessionId);
            if ($session->payment_status !== 'paid') {
                return redirect()->route('home')->with('error', '決済が完了していません。');
            }

            $itemId = $session->metadata->item_id;
            $userId = $session->metadata->user_id;

            $item = Item::findOrFail($itemId);

            DB::transaction(function () use ($item, $userId) {
                if ($item->is_sold) {
                    return;
                }
                $item->update([
                    'is_sold' => true,
                    'buyer_id' => $userId,
                ]);
            });

            return redirect()->route('home')->with('success', '商品の購入が完了しました。');

        } catch (\Exception $e) {
            \Log::error('Stripe購入完了処理エラー: ' . $e->getMessage());
            return redirect()->route('home')->with('error', '購入処理中にエラーが発生しました。');
        }
    }

    public function show($item_id)
    {
        $item = Item::with([
            'user',
            'condition',
            'categories',
            'comments.user',
            'likes',
        ])->findOrFail($item_id);

        $likeCount = $item->likes->count();
        $commentCount = $item->comments->count();

        $isLiked = false;
        if (Auth::check()) {
            $isLiked = $item->likes()->where('user_id', Auth::id())->exists();
        }

        return view('item.show', [
            'item' => $item,
            'likeCount' => $likeCount,
            'commentCount' => $commentCount,
            'isLiked' => $isLiked,
        ]);
    }
}
