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

        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

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
        $categories = collect(Category::all());
        $conditions = collect(Condition::all());

        return view('item.create', [
            'categories' => $categories,
            'conditions' => $conditions,
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

            $item->categories()->attach($validated['categories']);

        });

        return redirect()->route('home')->with('success', '商品を出品しました。');
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
