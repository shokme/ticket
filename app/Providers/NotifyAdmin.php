<?php

namespace App\Providers;

use App\Notifications\TicketPublished;
use App\Providers\TicketSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdmin implements ShouldQueue
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
     * @param  TicketSubmitted  $event
     * @return void
     */
    public function handle(TicketSubmitted $event)
    {
        Notification::route('mail', config('mail.from.address'))->notify(new TicketPublished($event->ticket));
    }
}
