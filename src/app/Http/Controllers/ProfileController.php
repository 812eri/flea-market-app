<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
    $user = Auth::user();

    $listType = $request->query('list', 'listed');
    $items = collect();

    if ($listType === 'listed') {
        $items = $user->items()->latest()->get();
    }elseif ($listType === 'purchased') {
        $purchases = $user->purchased()->with('item')->latest()->get;

        $items = $purchases->map(function ($purchase) {
            return $purchase->item;
        })->filter()->values();
    }

    return view('pages.users.show',[
        'user' => $user,
        'items' => $items,
    ]);
}
}