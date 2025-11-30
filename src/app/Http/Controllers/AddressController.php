<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

        $paymentMethodCode = $request->input('payment_method_code');

        return redirect()
            ->route('purchase.show', ['item_id' => $item_id, 'payment_method_code' => $paymentMethodCode,])
            ->with('success', '配送先情報を更新しました。');
        }

    public function edit(Request $request, $item_id)
    {
        $address = Address::where('user_id', Auth::id())->first();

        if (!$address) {
            $address = new Address();
        }

        $paymentMethodCode = $request->query('payment_method_code');

        return view('address.edit', [
            'address' => $address,
            'item_id' => $item_id,
            'payment_method_code' => $paymentMethodCode,
        ]);
    }
}
