<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request, $item_id)
    {
        Like::firstOrCreate([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
        ]);

        return back();
    }

    public function destroy(Request $request, $item_id)
    {
        Like::where('user_id', Auth::id())
        ->where('item_id', $item_id)
        ->delete();

        return back();
    }
}
