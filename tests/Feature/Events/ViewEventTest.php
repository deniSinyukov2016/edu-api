<?php

namespace Tests\Feature\Events;

use App\Enum\PermissionEnum;
use App\Models\Course;
use App\Models\Event;
use App\Models\TypeEvent;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewEventTest extends TestCase
{
    use RefreshDatabase;

    /** @var string */
    protected $permission;
    /** @var User $user */
    protected $user;
    /** @var Event $event */
    protected $event;

    public function setUp()
    {
        parent::setUp();

        $this->permission   = PermissionEnum::VIEW_EVENT;
        $this->user         = create(User::class);
    }

    /** @test */
    public function it_user_can_view_list_events_if_has_permissions()
    {
        create(Event::class, [], 3);
        $this->user->givePermissionTo($this->permission);

        $response = $this->signIn($this->user)
            ->getJson(route('events.index'))
            ->assertStatus(200)
            ->json();
        $this->assertCount(3, $response['data']);
    }


    /** @test */
    public function it_user_can_not_view_list_events_if_has_not_permissions()
    {
        $this->signIn($this->user)
             ->getJson(route('events.index'))
             ->assertStatus(403);
    }

    /** @test */
    public function it_user_not_authorized()
    {
        $this->getJson(route('events.index'))->assertStatus(401);
    }

    /** @test */
    public function it_can_view_list_events_by_filters()
    {
        $type = create(TypeEvent::class);
        create(Event::class, [
            'event_type_id' => $type->id,
        ], 3);

        create(Event::class, [
            'event_type_id' => create(TypeEvent::class)->id
        ], 10);

        $this->user->givePermissionTo($this->permission);

        $response = $this->signIn($this->user)
            ->getJson(route('events.index', ['event_type_id' => $type->id]))
            ->assertStatus(200)
            ->json();

        $this->assertCount(3, $response['data']);
    }

    /** @test */
    public function it_can_view_list_events_by_params_with()
    {
        create(Event::class, [], 3);

        $this->user->givePermissionTo($this->permission);

        $response = $this->signIn($this->user)
            ->getJson(route('events.index', ['with' => 'user,course']))
            ->assertStatus(200)
            ->json();

        $this->assertArrayHasKey('user', $response['data'][0]);
        $this->assertArrayHasKey('course', $response['data'][1]);
    }

    /** @test */
    public function it_user_can_view_one_event_if_has_permissions()
    {
        $this->user->givePermissionTo($this->permission);
        $this->event = create(Event::class);

        $this->signIn($this->user)
             ->getJson(route('events.show', $this->event))
             ->assertStatus(200)
             ->assertJson($this->event->toArray());
    }

    /** @test */
    public function it_user_can_not_view_one_event_if_has_permissions()
    {
        $this->event = create(Event::class);

        $this->signIn($this->user)
            ->getJson(route('events.show', $this->event))
            ->assertStatus(403);
    }

    /** @test */
    public function it_user_can_not_view_single_event_if_not_authorized()
    {
        $this->getJson(route('events.show', create(Event::class)))
             ->assertStatus(401);
    }

    /** @test */
    public function it_user_can_view_one_event_with_loads()
    {
        $this->user->givePermissionTo($this->permission);
        $this->event = create(Event::class);

        $response = $this->signIn($this->user)
            ->getJson(route('events.show', ['event' => $this->event, 'with' => 'user,course']))
            ->assertStatus(200)
            ->assertJson($this->event->toArray())
            ->json();

        $this->assertArrayHasKey('user', $response);
        $this->assertArrayHasKey('course', $response);
    }

    /** @test */
    public function it_can_view_list_events_by_filters_with_name_or_and_title()
    {
        $type = create(TypeEvent::class);

        $user   = create(User::class, ['name' => 'Admin']);
        $course = create(Course::class, ['title' => 'veryyyy']);

        create(Event::class, [
            'event_type_id' => $type->id,
            'user_id'       => $user->id,
            'course_id'     => $course->id
        ], 3);

        create(Event::class, [
            'event_type_id' => $type->id,
            'user_id'       => $user->id,
            'course_id'     => create(Course::class)->id
        ]);

        create(Event::class, [
            'event_type_id' => create(TypeEvent::class)->id
        ], 10);

        $this->user->givePermissionTo($this->permission);

        $response = $this->signIn($this->user)
            ->getJson(route('events.index', [
                'name'  => $user->name,
                'title' => $course->title
            ]))
            ->assertStatus(200)
            ->json();

        $this->assertCount(4, $response['data']);
    }
}
