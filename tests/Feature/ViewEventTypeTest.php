<?php

namespace Tests\Feature;

use App\Enum\PermissionEnum;
use App\Models\TypeEvent;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewEventTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @var string */
    protected $permission;
    /** @var User $user */
    protected $user;
    /** @var TypeEvent $typeEvent */
    protected $typeEvent;

    public function setUp()
    {
        parent::setUp();

        $this->permission   = PermissionEnum::VIEW_TYPE_EVENT;
        $this->user         = create(User::class);
    }

    /** @test */
    public function it_user_can_view_list_type_events_if_has_permissions()
    {
        create(TypeEvent::class, [], 3);
        $this->user->givePermissionTo($this->permission);

        $response = $this->signIn($this->user)
            ->getJson(route('eventstype.index'))
            ->assertStatus(200)
            ->json();
        $this->assertCount(3, $response['data']);
    }


    /** @test */
    public function it_user_can_not_view_list_type_events_if_has_not_permissions()
    {
        $this->signIn($this->user)
             ->getJson(route('eventstype.index'))
             ->assertStatus(403);
    }

    /** @test */
    public function it_user_not_authorized()
    {
        $this->getJson(route('eventstype.index'))->assertStatus(401);
    }

}
