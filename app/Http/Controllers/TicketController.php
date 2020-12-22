<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\Ticket as TicketResource;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index() {
        $tickets = Ticket::published()->get();

        return TicketResource::collection($tickets);
    }

    public function store(TicketRequest $request) {
        $validated = $request->validated();

        $ticket = Ticket::create($validated);

        return TicketResource::make($ticket);
    }
}
