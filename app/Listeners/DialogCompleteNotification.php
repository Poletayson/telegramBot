<?php

namespace App\Listeners;

use App\Events\DialogComplete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DialogCompleteNotification
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
     * @param  \App\Events\DialogComplete  $event
     * @return void
     */
    public function handle(DialogComplete $event)
    {
        //
    }
}
