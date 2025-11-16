<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
    $user = Auth::user();

    $listType = $request->query('page', 'listed');
    $items = collect();

    if ($listType === 'listed') {
        $items = $user->items()->latest()->get();
    }elseif ($listType === 'purchased') {
        $purchases = $user->purchases()->with('item')->latest()->get();

        $items = $purchases->map(function ($purchase) {
            return $purchase->item;
        })->filter()->values();
    }

    return view('profile.index',[
        'user' => $user,
        'items' => $items,
    ]);
}

    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $validatedData = $request->validated();

        DB::transaction(function () use ($request, $user, $validatedData) {
            $imagePath = $user->profile_image_url;

            if ($request->hasFile('profile_image')) {

                if ($user->profile_image_url) {
                    $oldPath = str_replace(Storage::url(''), '', $user->profile_image_url);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $path = $request->file('profile_image')->store('profiles', 'public');

                $imagePath = Storage::url($path);
            }

            $user->update([
                'name' => $validatedData['user_name'],
                'profile_image_url' => $imagePath,
            ]);

            Address::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'post_code' => $validatedData['post_code'],
                    'prefecture' => $validatedData['prefecture'],
                    'city' => $validatedData['city'],
                    'street_address' => $validatedData['street_address'],
                    'building_name' => $validatedData['building_name'] ?? null,
                ]
            );
        });

        return redirect()->route('profile.show')->with('success', 'プロフィール情報を更新しました。');
    }
}