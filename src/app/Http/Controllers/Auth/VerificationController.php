<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    protected function verified(Request $request)
    {
        $user = $request->user();

        // プロフィール未設定の判定ロジック（LoginControllerと同様）
        // Userモデルで定義した hasCompletedProfile() を使用
        if (!$user->hasCompletedProfile()) {
            // プロフィール編集ページへリダイレクト
            return redirect()->intended('/mypage/profile');
        }

        // プロフィール設定済みの場合、通常のリダイレクト先へ（商品一覧）
        return redirect($this->redirectTo);
    }
}

