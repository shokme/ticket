<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Notifications\TicketPublished;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_publish() {
        $ticket = Ticket::create(['title' => 'foo bar baz', 'content' => 'must have a generated slug']);
        $this->assertNotNull($ticket->published_at);
    }

    /** @test */
    public function generate_slug_on_create()
    {
        $ticket = Ticket::create(['title' => 'foo bar baz', 'content' => 'must have a generated slug']);

        $this->assertEquals('foo-bar-baz', $ticket->slug);
    }

    /** @test */
    public function notification_is_sent() {
        Notification::fake();
        Notification::assertNothingSent();

        Ticket::create(['title' => 'foo bar baz', 'content' => 'must have a generated slug']);
        Notification::assertSentTo(new AnonymousNotifiable(), TicketPublished::class, function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'] === config('mail.from.address');
        });
    }
}
