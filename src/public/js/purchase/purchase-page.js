// 支払い方法の表示名マッピング
const paymentMethodNames = {
    'conbini': 'コンビニ支払い',
    'credit': 'カード支払い'
};

document.addEventListener('DOMContentLoaded', function() {
    // 要素の取得
    const paymentSelect = document.getElementById('paymentMethodSelect');
    const hiddenPaymentMethod = document.getElementById('hiddenPaymentMethod');
    const displayedPaymentMethod = document.getElementById('displayedPaymentMethod');
    const changeLink = document.querySelector('.change-link');

    // 要素が存在しない場合は処理を中断
    if (!paymentSelect || !hiddenPaymentMethod || !displayedPaymentMethod) {
        console.warn('購入ページの必要な要素が見つかりません');
        return;
    }

    // 初期表示の設定
    if (paymentSelect && hiddenPaymentMethod.value) {
        paymentSelect.value = hiddenPaymentMethod.value;
        updateDisplayedPaymentMethod(hiddenPaymentMethod.value);
    }

    // 支払い方法が変更されたときの処理
    if (paymentSelect) {
        paymentSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            
            // hidden inputを更新
            hiddenPaymentMethod.value = selectedValue;
            
            // 表示を更新
            updateDisplayedPaymentMethod(selectedValue);
            
            // 配送先変更リンクのURLを更新
            updateChangeLink(selectedValue);
        });
    }

    // 表示されている支払い方法を更新する関数
    function updateDisplayedPaymentMethod(methodCode) {
        if (displayedPaymentMethod) {
            displayedPaymentMethod.textContent = paymentMethodNames[methodCode] || '未選択';
        }
    }

    // 配送先変更リンクのURLを更新する関数
    function updateChangeLink(methodCode) {
        if (changeLink && methodCode) {
            const url = new URL(changeLink.href);
            url.searchParams.set('payment_method_code', methodCode);
            changeLink.href = url.toString();
        }
    }

    // ページ読み込み時に配送先変更リンクを初期化
    if (hiddenPaymentMethod.value) {
        updateChangeLink(hiddenPaymentMethod.value);
    }
});