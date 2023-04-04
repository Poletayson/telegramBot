<?php

namespace App\Providers;

use App\Events\ErrorOccurred;
use App\Events\NewDialogRejected;
use App\Events\NewDialogStarted;
use App\Listeners\ErrorOccurredNotification;
use App\Listeners\NewDialogRejectedNotification;
use App\Listeners\NewDialogStartedNotification;
use App\Models\Auth\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use App\Listeners\MessageNotification;
use App\Listeners\OperatorMessageSentNotification;
use App\Listeners\DialogCompleteNotification;

use App\Events\MessageReceived;
use App\Events\OperatorMessageSent;
use App\Events\DialogComplete;


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
        MessageReceived::class => [
            MessageNotification::class
        ],
        OperatorMessageSent::class => [
            OperatorMessageSentNotification::class
        ],
        NewDialogStarted::class => [
            NewDialogStartedNotification::class
        ],
        NewDialogRejected::class => [
            NewDialogRejectedNotification::class
        ],
        DialogComplete::class => [
            DialogCompleteNotification::class
        ],
        ErrorOccurred::class => [
            ErrorOccurredNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //Каналы диалогов прослушивают
        Broadcast::channel('user.{$userId}', function (User $user, $userId) {
            return $user->getUserId() === $userId;
        });
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
