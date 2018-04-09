<?php

namespace Tests\Feature\Feedback;

use App\Jobs\SendFeedbackEmail;
use App\Models\Feedback;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateFeedbackTest extends TestCase
{
    use RefreshDatabase;

    /** @var Feedback $feedback  */
    protected $feedback;

    /** @test */
    public function all_user_can_create_feedback()
    {
        Queue::fake();
        $this->postJson(route('feedback.store', $feedback = make(Feedback::class)->toArray()))
             ->assertStatus(201)
             ->assertJsonFragment($feedback);

        $this->assertDatabaseHas('feedback', $feedback);
        Queue::assertPushed(SendFeedbackEmail::class, 1);
    }

    /** @test */
    public function it_user_must_fill_all_fields_for_success_send_feedback()
    {
        $this->postJson(route('feedback.store', $email = ['email' => 'mail@m.com']))
            ->assertStatus(422)->json();
        $this->assertFalse(Feedback::query()->where($email)->exists());

        $this->postJson(route('feedback.store', $name = ['name' => 'Bob']))
            ->assertStatus(422)->json();
        $this->assertFalse(Feedback::query()->where($name)->exists());

        $this->postJson(route('feedback.store', $message = ['message' => 'Hello']))
            ->assertStatus(422)->json();
        $this->assertFalse(Feedback::query()->where($message)->exists());
    }

}
