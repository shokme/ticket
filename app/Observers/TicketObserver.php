<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Providers\TicketSubmitted;
use Illuminate\Support\Str;

class TicketObserver
{
    public function creating(Ticket $ticket) {
        $ticket->slug = Str::slug($ticket->title);
        abort_if(Ticket::where('slug', $ticket->slug)->exists(), 409);
    }

    public function created(Ticket $ticket) {
        TicketSubmitted::dispatch($ticket);
    }
}
