<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        //
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return config('util.frontend_url') . "/" . $token;
        });


        VerifyEmail::createUrlUsing(function ($notifiable) {

            $hash = sha1($notifiable->getEmailForVerification());

            $url = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => $hash,
                ]
            );

            $request = Request::create($url);

            $expires = $request->expires;

            $signature = $request->signature;

            return config('util.frontend_url') . "/email/verify/{$notifiable->getKey()}/{$hash}?expires={$expires}&signature={$signature}";
        });
    }
}
