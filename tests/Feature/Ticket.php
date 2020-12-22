<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Ticket extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_post_a_ticket()
    {
        $response = $this->post('/api/tickets', ['title' => 'foo bar baz', 'content' => 'lorem ipsum']);

        $response
            ->assertStatus(201)
            ->assertJson(
                [
                    'ticket' => [
                        'slug' => 'foo-bar-baz'
                    ],
                ]
            );
    }

    /** @test */
    public function can_see_all_published_tickets()
    {
        \App\Models\Ticket::factory(3)->create(['published_at' => now()]);
        \App\Models\Ticket::withoutEvents(function () {
            \App\Models\Ticket::factory(10)->create(['published_at' => null]);
        });

        $response = $this->get('/api/tickets');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function can_see_a_ticket()
    {
        $ticket = \App\Models\Ticket::factory()->create(['title' => 'single ticket', 'published_at' => now()]);

        $response = $this->get('/api/tickets/' . $ticket->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'ticket' => [
                    'title' => 'single ticket',
                ]
            ]);
    }

    /** @test */
    public function cannot_see_an_unpublished_ticket()
    {
       $ticket = \App\Models\Ticket::withoutEvents(function () {
            return \App\Models\Ticket::factory()->create(['published_at' => null]);
        });

        $response = $this->get('/api/tickets/' . $ticket->id);

        $response->assertStatus(404);
    }

    /** @test */
    public function can_update_a_ticket()
    {
        $ticket = \App\Models\Ticket::factory()->create(['title' => 'single ticket', 'content' => 'first content']);

        $this->assertEquals('first content', $ticket->content);

        $response = $this->patch('/api/tickets/' . $ticket->id, [
            'content' => 'updated content'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['ticket' => [
                'content' => 'updated content'
            ]]);
    }

    /** @test */
    public function can_delete_a_ticket()
    {
        $ticket = \App\Models\Ticket::factory()->create(['title' => 'single ticket', 'content' => 'first content']);

        $response = $this->delete('/api/tickets/' . $ticket->id);
        $response->assertStatus(200);

        $response = $this->get('/api/tickets/' . $ticket->id);
        $response->assertStatus(404);
    }
}
