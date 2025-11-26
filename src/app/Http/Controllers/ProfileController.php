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

    $listType = $request->query('page', 'sell');
    $items = collect();

    if ($listType === 'sell') {
        $items = $user->items()->latest()->get();
    }elseif ($listType === 'buy') {
        $items = $user->purchasedItems()->latest()->get();
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
                    'street_address' => $validatedData['street_address'],
                    'building_name' => $validatedData['building_name'] ?? null,
                ]
            );
        });

        return redirect()->route('profile.edit')->with('success', 'プロフィール情報を更新しました。');
    }

    public function updateProfileSetup(ProfileRequest $request)
    {
        $user = Auth::user();

        $validatedData = $request->validated();

    DB::transaction(function () use ($request, $user, $validatedData) {
        $imagePath = $user->profile_image_url;

        // --- 画像処理 ---
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

        // --- ユーザー情報の更新 ---
        $user->update([
            'name' => $validatedData['user_name'],
            'profile_image_url' => $imagePath,
            // ★【重要】ここで profile_completed を true に設定
            'profile_completed' => true,
        ]);

        // --- 住所情報の更新 ---
        Address::updateOrCreate(
            ['user_id' => $user->id],
            [
                'post_code' => $validatedData['post_code'],
                'street_address' => $validatedData['street_address'],
                'building_name' => $validatedData['building_name'] ?? null,
            ]
        );
    });

    // 【重要】設定完了後は商品一覧画面へリダイレクト
    return redirect()->route('home')->with('success', 'プロフィール設定が完了しました。');
    }
}
