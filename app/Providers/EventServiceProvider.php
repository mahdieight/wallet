<?php

namespace App\Providers;

use App\Events\PaymentApproved;
use App\Events\PaymentRejected;
use App\Listeners\CreateTransactionAfterApprovedPayment;
use App\Listeners\NotifyToPaymentOwnerAfterChangePaymentStatus;
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
            NotifyToPaymentOwnerAfterChangePaymentStatus::class

        ],
        PaymentRejected::class => [
        ],
        PaymentApproved::class => [
            CreateTransactionAfterApprovedPayment::class,
            NotifyToPaymentOwnerAfterChangePaymentStatus::class
        ]
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
