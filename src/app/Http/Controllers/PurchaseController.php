<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function create($item_id): \Illuminate\View\View|RedirectResponse
    {
        $item = Item::findOrFail($item_id);

        if (Purchase::where('item_id', $item_id)->exists()) {
            return redirect()->route('item.show', $item_id)
            ->with('error', 'この商品はすでに購入済です。');
        }

        $address = Address::where('user_id', Auth::id())->first();

        if (!$address || !$address->id) {
            return redirect()->route('profile.edit')
            ->with('error', '購入手続きに進む前に、プロフィール設定画面で配送先住所を登録してください。');
        }

        return view('pages.purchase.create', [
            'item' => $item,
            'address' => $address,
            'selectedPaymentMethod' => null,
        ]);
    }

    public function store(PurchaseRequest $request, $item_id): RedirectResponse
    {
        $item = Item::findOrFail($item_id);
        $userId = Auth::id();

        if (Purchase::where('item_id', $item->id)->exists()) {
            return redirect()->route('item.show', $item->id)
            ->with('error', 'この商品はすでに購入済です。');
        }

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
                'success_url' => route('purchase.success', ['item_id' => $item->id, 'session_id' => '{CHECKOUT_SESSION_ID}']),
                'cancel_url' => route('item.show', ['item_id' => $item->id]),

                'metadata' => [
                    'user_id' => $userId,
                    'item_id' => $item->id,
                    'address_id' => $request->address_id,
                    'payment_method' => 'card',
                ],
            ]);

            return redirect()->away($session->url);
        } else {

        Purchase::create([
            'user_id' => $userId,
            'item_id' => $item->id,
            'payment_method' => $paymentMethod,
            'address_id' => $request->address_id,
        ]);

        return redirect()->route('item.index')->with('success', 'コンビニ払いの手続きを開始しました。');
        }
    }

    public function success($item_id, $session_id): RedirectResponse
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $session = \Stripe\Checkout\Session::retrieve($session_id);

        $metadata = $session->metadata;

        if (Purchase::where('item_id', $metadata->item_id)->exists()) {
            return redirect()->route('item.index')->with('info', 'すでに購入処理が完了しています。');
        }

        Purchase::create([
            'user_id' => $metadata->user_id,
            'item_id' => $metadata->item_id,
            'payment_method' => $metadata->payment_method,
            'address_id' => $metadata->address_id,
        ]);

        return redirect()->route('item.index')->with('success', 'カードで商品を購入しました。');
    }
}