<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\Ticket as TicketResource;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

    public function show(Ticket $ticket) {
        if($ticket->published_at === Ticket::UNPUBLISHED) {
            return response(null, 404);
        }

        return TicketResource::make($ticket);
    }

    /**
     * Non-utilisée
     * mais imaginons, le traffic est important la première heures de la sortie d'un ticket, on peut exploiter le cache
     * C'est du vite écris, j'ai pas trop réfléchis car j'ai un gros manque de temps, c'est juste pour imaginer une solution apportée à un problème de performances
     * par la method show() ci-dessus
     */
    public function showCache($id) {
        if(Cache::has('ticket_id:'.$id)) {
            $ticket = Cache::get('ticket_id:'.$id);
            return TicketResource::make($ticket);
        }

        $ticket = Ticket::find($id);
        /**
         * Ici on pourrait utiliser l'observer quand Published::class est call on pourrait en profiter pour utiliser le cache
         * Ou alors ajouter un SendToCache::class en listener supplémentaire
         * Ce qui reduit le code de notre controller / model en fonction d'ou on place le content.
         */
        Cache::put('ticket_id:'.$id, $ticket, now()->addHours());
        return TicketResource::make($ticket);
    }

    public function update(TicketRequest $request, Ticket $ticket) {
        $validated = $request->validated();

        $ticket->update($validated);

        return TicketResource::make($ticket);
    }

    public function destroy(Ticket $ticket) {
        $ticket->delete();

        Log::info("Ticket deleted", ['user' => 'ticket deleted by user_id', 'date' => now(), 'slug' => $ticket->slug]);
    }
}
