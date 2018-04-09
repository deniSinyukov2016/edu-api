<?php

namespace Tests\Feature\Lessons;

use App\Enum\PermissionEnum;
use App\Models\Lesson;
use App\Models\TypeLesson;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddLessonTest extends TestCase
{
    use RefreshDatabase;

    /** @var User $user  */
    protected $user;

    /** @var string  */
    protected $permission;

    /** @var array  */
    protected $lesson;

    public function setUp()
    {
        parent::setUp();
        $this->user = create(User::class);
        $this->permission = PermissionEnum::CREATE_LESSON;
        $this->lesson = make(Lesson::class)->toArray();
    }

    /** @test */
    public function it_user_can_create_lesson_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->postJson(route('lessons.store'), $this->lesson)
             ->assertStatus(201)
             ->assertJsonFragment($this->lesson);

        $this->assertDatabaseHas('lessons', $this->lesson);
    }

    /** @test */
    public function it_user_can_not_create_lesson_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->postJson(route('lessons.store'), $this->lesson)
             ->assertStatus(403);

        $this->assertDatabaseMissing('lessons', $this->lesson);
    }

    /** @test */
    public function it_user_can_not_add_lesson_if_not_authorize()
    {
        $this->postJson(route('lessons.store'))
            ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_add_lesson_if_invalid_data()
    {
        $this->user->givePermissionTo(PermissionEnum::CREATE_LESSON);
        /** @var array  */
        $lesson = make(Lesson::class, ['name' => null])->toArray();

        $this->signIn($this->user)
             ->postJson(route('lessons.store'), $lesson)
             ->assertStatus(422);

        $this->assertDatabaseMissing('lessons', $lesson);
    }
}
