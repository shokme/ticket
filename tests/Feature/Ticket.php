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
}
