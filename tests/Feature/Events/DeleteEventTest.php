<?php

namespace Tests\Feature\Events;

use App\Enum\PermissionEnum;
use App\Models\Event;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteEventTest extends TestCase
{
    use RefreshDatabase;

    /** @var Event $event  */
    protected $event;

    /** @var string */
    protected $permission;

    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->event = create(Event::class);
        $this->permission = PermissionEnum::DELETE_EVENT;
        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_delete_event_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->deleteJson(route('events.destroy', $this->event))
             ->assertStatus(204);

        $this->assertDatabaseHas('events', $this->event->toArray());
    }

    /** @test */
    public function it_user_can_not_delete_events_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->deleteJson(route('events.destroy', $this->event))
             ->assertStatus(403);

        $this->assertDatabaseHas('events', $this->event->toArray());
    }

    /** @test */
    public function it_can_not_delete_if_unauthorized()
    {
        $this->deleteJson(route('events.destroy', $this->event))
             ->assertStatus(401);
    }
}
