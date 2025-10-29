<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Category;

class ItemController extends Controller
{
    public function create()
    {
        $categories = Category::pluck('name', 'id');

        return view('pages.items.create', [
            'categories' => $categories,
        ]);
    }

    public function store(Exhibitionrequest $request)
    {
        $imagePath = $request->file('item_image')->store('public/images');
        $imageUrl = Storage::url($imagePath);

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'brand_name' => $request->brand_name,
            'condition' => $request->condition,
            'image_url' => $imageUrl,
        ]);

        $item->categories()->attach($request->categories);

        return redirect()
            ->route('item.show', ['item_id' => $item->id])
            ->with('success', '商品を出品しました。');
    }

    public function show($item_id)
    {
        $item = Item::with([
            'user',
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

        return view('pages.items.show', [
            'item' => $item,
            'likeCount' => $likeCount,
            'commentCount' => $commentCount,
            'isLiked' => $isLiked,
        ]);
    }
}
