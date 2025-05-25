<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\DownloadCompleted;
use App\Events\TokenLow;
use App\Listeners\SendDownloadNotification;
use App\Listeners\NotifyLowTokenBalance;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Anda bisa menghapus ini jika tidak menggunakan email verification
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],

        // Tetap daftarkan event dan listener untuk log
        DownloadCompleted::class => [
            SendDownloadNotification::class,
        ],
        TokenLow::class => [
            NotifyLowTokenBalance::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
