<?php

namespace Tests\Feature\Lessons;

use App\Enum\PermissionEnum;
use App\Models\Lesson;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteLessonTest extends TestCase
{
    use RefreshDatabase;

    /** @var Lesson $lesson  */
    protected $lesson;

    /** @var string */
    protected $permission;

    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->lesson = create(Lesson::class);
        $this->permission = PermissionEnum::DELETE_LESSON;

        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_delete_lesson_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->deleteJson(route('lessons.destroy', $this->lesson))
             ->assertStatus(204);

        $this->assertDatabaseMissing('lessons', $this->lesson->toArray());
    }

    /** @test */
    public function it_user_can_not_delete_lesson_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->deleteJson(route('lessons.destroy', $this->lesson))
             ->assertStatus(403);

        $this->assertDatabaseHas('lessons', $this->lesson->toArray());
    }

    /** @test */
    public function it_can_not_delete_if_unauthorized()
    {
        $this->deleteJson(route('lessons.destroy', $this->lesson))
             ->assertStatus(401);
    }
}
