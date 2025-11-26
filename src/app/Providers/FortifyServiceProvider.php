<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));
        //Fortify::profileInformationView(fn () => view('pages.profile.edit'));

        // ★ログイン後のカスタムリダイレクトを定義★
        // ログインが成功したときに実行されます
        Fortify::authenticateUsing(function (Request $request) {
            if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {

                $user = Auth::user();

                // 1. プロフィール未設定のチェック
                if (!$user->has_profile) {
                    // 初回ログイン時はプロフィール設定画面へ
                    return redirect()->route('profile.edit');
                }

                // 2. プロフィール設定済みの場合（2回目以降）は商品一覧へ
                return redirect()->intended('/');
            }
            return false; // 認証失敗
        });
        // ... (既存の RateLimiter) ...

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
