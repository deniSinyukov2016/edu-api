<?php

namespace Tests\Feature\TypeLesson;

use App\Enum\PermissionEnum;
use App\Models\TypeLesson;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddTypeLessonTest extends TestCase
{
    use RefreshDatabase;

    /** @var User $user  */
    protected $user;
    /** @var string  */
    protected $permission;
    /** @var array $typeLesson  */
    protected $typeLesson;

    public function setUp()
    {
        parent::setUp();

        $this->typeLesson = make(TypeLesson::class)->toArray();
        $this->permission = PermissionEnum::CREATE_TYPE_LESSON;
        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_create_type_lesson_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->postJson(route('type_lessons.store'), $this->typeLesson)
             ->assertStatus(201);

        $this->assertDatabaseHas('type_lessons', $this->typeLesson);
    }

    /** @test */
    public function it_user_can_not_create_type_lesson_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->postJson(route('type_lessons.store'), $this->typeLesson)
             ->assertStatus(403);

        $this->assertDatabaseMissing('type_lessons', $this->typeLesson);
    }

    /** @test */
    public function it_can_not_create_type_lesson_if_not_authorized()
    {
        $this->postJson(route('type_lessons.store'), $this->typeLesson)
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_create_type_lesson_if_invalid_data()
    {
        $this->user->givePermissionTo($this->permission);

        $typeLesson = make(TypeLesson::class, ['title' => str_random(256)])->toArray();

        $this->signIn($this->user)
             ->postJson(route('type_lessons.store'), $typeLesson)
             ->assertStatus(422);

        $this->assertDatabaseMissing('type_lessons', $typeLesson);
    }
}
