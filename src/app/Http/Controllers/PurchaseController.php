<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function create($item_id)
    {
        $item = Item::findOrfail($item_id);
        $address = Address::Where('user_id', Auth::id())->first();
        if (!$address) {
            $address = new Address();
        }

        return view('pages.purchase.create', [
            'item' => $item,
            'address' => $address,
            'selectedPaymentMethod' => null,
        ]);
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $paymentMethod = $request->payment_method;
        if ($paymentMethod === 'card') {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'unit_amount' => $item->price,
                        'product_data' => [
                            'name' => $item->name,
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('purchase.success', ['item_id' => $item->id]),
                'cancel_url' => route('item.show', ['item_id' => $item->id]),

                'metadata' => [
                    'user_id' => Auth::id(),
                    'item_id' => $item->id,
                    'address_id' => $request->address_id,
                ],
            ]);

            return redirect()->away($session->url);
        } else {

        Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'payment_method' => $paymentMethod,
            'address_id' => $request->address_id,
        ]);

        return redirect()->route('item.index')->with('success', '商品を購入しました。');
        }
    }

    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);

        return redirect()->route('item.index')->with('success', 'カードで商品を購入しました。');
    }
}