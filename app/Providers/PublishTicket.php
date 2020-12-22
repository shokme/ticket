<?php

namespace App\Providers;

use App\Providers\TicketSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class PublishTicket
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  TicketSubmitted  $event
     * @return void
     */
    public function handle(TicketSubmitted $event)
    {
        $event->ticket->publish();
        Log::info('Ticket published', ['user' => 'get user_id', 'ticket_id' => $event->ticket->id]);
    }
}
