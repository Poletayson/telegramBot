<?php

namespace App\Listeners;

use App\Events\OperatorMessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OperatorMessageSentNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OperatorMessageSent  $event
     * @return void
     */
    public function handle(OperatorMessageSent $event)
    {
        //
    }
}
