<?php

namespace Tests\Feature\TypeLesson;

use App\Enum\PermissionEnum;
use App\Models\TypeLesson;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTypeLessonTest extends TestCase
{
    use RefreshDatabase;

    /** @var TypeLesson $answer */
    protected $typeLesson;
    /** @var User $user */
    protected $user;
    /** @var string */
    protected $permission;

    public function setUp()
    {
        parent::setUp();

        $this->typeLesson   = create(TypeLesson::class);
        $this->user         = create(User::class);
        $this->permission   = PermissionEnum::VIEW_TYPE_LESSON;
    }

    /** @test */
    public function it_user_can_view_list_type_lessons_if_has_permissions()
    {
        $this->user->givePermissionTo($this->permission);

        create(TypeLesson::class, [], 10);

        $response = $this->signIn($this->user)
            ->getJson(route('type_lessons.index'))
            ->assertStatus(200)
            ->json();

        $this->assertCount(10, $response['data']);
    }

    /** @test */
    public function it_user_can_not_view_list_type_lessons_if_has_not_permissions()
    {
        $this->signIn($this->user)
             ->getJson(route('type_lessons.index'))
             ->assertStatus(403);
    }

    /** @test */
    public function it_user_not_authorized()
    {
        $this->getJson(route('type_lessons.index'))
             ->assertStatus(401);
    }
}
