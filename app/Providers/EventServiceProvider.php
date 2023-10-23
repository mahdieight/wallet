<?php

namespace App\Providers;

use App\Events\Currency\CurrencyActivated;
use App\Events\Currency\CurrencyCreated;
use App\Events\Currency\CurrencyDeActivated;
use App\Events\Payment\PaymentApproved;
use App\Events\Payment\PaymentRejected;
use App\Listeners\NotifyToPaymentOwnerAfterChangePaymentStatus;
use App\Listeners\UpdateUserBalance;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,

        ],

        // Payment Event
        PaymentRejected::class => [
            UpdateUserBalance::class,
            NotifyToPaymentOwnerAfterChangePaymentStatus::class
        ],
        PaymentApproved::class => [
            UpdateUserBalance::class,
            NotifyToPaymentOwnerAfterChangePaymentStatus::class
        ],

        // Currency Event
        CurrencyCreated::class => [],
        CurrencyActivated::class => [],
        CurrencyDeActivated::class => [],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
