<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
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
        $this->app->singleton(
            \App\Repositories\User\UserRepositoryInterface::class,
            \App\Repositories\User\UserEloquentRepository::class,
        );
        $this->app->singleton(
            \App\Repositories\Division\DivisionRepositoryInterface::class,
            \App\Repositories\Division\DivisionEloquentRepository::class,
        );
        $this->app->singleton(
            \App\Repositories\Schedule\ScheduleRepositoryInterface::class,
            \App\Repositories\Schedule\ScheduleEloquentRepository::class,
        );
        $this->app->singleton(
            \App\Repositories\Attendance\AttendanceRepositoryInterface::class,
            \App\Repositories\Attendance\AttendanceEloquentRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return config('util.frontend_url') . "/reset-password/" . $token;
        });

        VerifyEmail::createUrlUsing(function ($notifiable) {
            $hash = sha1($notifiable->getEmailForVerification());

            $url = URL::temporarySignedRoute(
                'api.v1.auth.verification.verify',
                now()->addMinutes(config('auth.verification.expire', 60)),
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

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject(Lang::get('Verify Email Address'))
                ->line(Lang::get('Your account has been created successfully by the administrator.'))
                ->line(Lang::get('Your username & password are as follows:'))
                ->line(Lang::get('Username: ' . $notifiable->username))
                ->line(Lang::get('Password: ' . config('util.default_password')))
                ->line(Lang::get('Please click the button below to verify your email address.'))
                ->action(Lang::get('Verify Email Address'), $url)
                ->line(Lang::get('After verifying your email address, you can login to the system using the above username and password.'))
                ->line(Lang::get('Please change your password after logging in for the first time.'))
                ->line(Lang::get('If you did not create an account, no further action is required.'));
        });
    }
}
