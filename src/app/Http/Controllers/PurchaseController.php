<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * 購入画面表示
     */
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        // すでに売り切れなら購入画面に進ませない
        if ($item->is_sold) {
            return redirect()->route('items.index')->with('error', 'この商品はすでに購入されています。');
        }

        // ユーザーの住所
        $address = Address::where('user_id', Auth::id())->first();

        // 支払い方法（表示用）
        $selectedPaymentMethodCode = request()->query('payment_method_code');
        $selectedPaymentMethod = $this->getPaymentMethodDisplay($selectedPaymentMethodCode);

        return view('purchase.show', compact('item', 'address', 'selectedPaymentMethod', 'selectedPaymentMethodCode'));
    }

    /**
     * 購入処理
     */
    public function store(Request $request, $item_id)
    {
        $request->validate([
            'payment_method' => 'required|in:conbini,credit'
        ]);

        $item = Item::findOrFail($item_id);

        // 売り切れチェック
        if ($item->is_sold) {
            return redirect()->route('items.index')->with('error', 'この商品はすでに購入されています。');
        }

        $address = Address::where('user_id', Auth::id())->first();
        if (!$address) {
            return back()->withErrors(['address' => '配送先を設定してください。']);
        }

        // -----------------------
        // ① 購入履歴を作成
        // -----------------------
        Purchase::create([
            'user_id'        => Auth::id(),
            'item_id'        => $item_id,
            'address_id'     => $address->id,
            'payment_method' => $request->payment_method,
        ]);

        // -----------------------
        // ② 商品の購入フラグを更新
        // -----------------------
        $item->update([
            'is_sold'  => true,
            'buyer_id' => Auth::id(),
        ]);

        // 購入完了画面へ
        return redirect()->route('purchase.complete')->with('success', '購入が完了しました。');
    }

    /**
     * 購入完了画面
     */
    public function complete()
    {
        return view('purchase.complete');
    }

    /**
     * 支払い方法の表示名
     */
    private function getPaymentMethodDisplay($code)
    {
        return [
            'conbini' => 'コンビニ支払い',
            'credit'  => 'カード支払い',
        ][$code] ?? '未選択';
    }
}