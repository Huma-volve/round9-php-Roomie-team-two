<?php

namespace App\Providers;

use App\Events\BookingCancelled;
use App\Events\BookingCreated;
use App\Events\ChatMessageReceived;
use App\Events\ContactMessageReceived;
use App\Events\PaymentReceived;
use App\Events\ReviewCreated;
use App\Listeners\SendBookingCancelledNotification;
use App\Listeners\SendChatMessageNotification;
use App\Listeners\SendContactMessageNotification;
use App\Listeners\SendNewBookingNotification;
use App\Listeners\SendNewReviewNotification;
use App\Listeners\SendPaymentReceivedNotification;
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

        // Booking Events
        BookingCreated::class => [
            SendNewBookingNotification::class,
        ],

        BookingCancelled::class => [
            SendBookingCancelledNotification::class,
        ],

        // Review Events
        ReviewCreated::class => [
            SendNewReviewNotification::class,
        ],

        // Chat Events
        ChatMessageReceived::class => [
            SendChatMessageNotification::class,
        ],

        // Contact Message Events
        ContactMessageReceived::class => [
            SendContactMessageNotification::class,
        ],

        // Payment Events
        PaymentReceived::class => [
            SendPaymentReceivedNotification::class,
        ],
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