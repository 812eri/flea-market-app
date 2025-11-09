<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function update(AddressRequest $request, $item_id)
    {
        $existingAddress = Address::where('user_id', Auth::id())->first();

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if ($existingAddress) {
            $existingAddress->update($data);
        } else {
            Address::create($data);
        }

        return redirect()
            ->route('purchase.show', ['item_id' => $item_id])
            ->with('success', '配送先情報を更新しました。');
        }

    public function edit($item_id)
    {
        $address = Address::where('user_id', Auth::id())->first();
        if (!$address) {
            $address = new Address();
        }

        return view('pages.purchases.address.edit', [
            'address' => $address,
            'item_id' => $item_id,
        ]);
    }
}
