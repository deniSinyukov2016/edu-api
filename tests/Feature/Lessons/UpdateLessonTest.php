<?php

namespace Tests\Feature\Lessons;

use App\Enum\PermissionEnum;
use App\Models\Lesson;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateLessonTest extends TestCase
{
    use RefreshDatabase;

    /** @var Lesson $lesson  */
    protected $lesson;

    /** @var string  */
    protected $permission;

    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->lesson = create(Lesson::class);
        $this->permission = PermissionEnum::UPDATE_LESSON;

        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_update_lesson_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->putJson(route('lessons.update', $this->lesson), $newData = ['name' => 'newName'])
             ->assertStatus(200)
             ->assertJsonFragment($newData);

        $this->assertEquals($this->lesson->fresh()->name, $newData['name']);
    }

    /** @test */
    public function it_user_can_not_update_lesson_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->putJson(route('lessons.update', $this->lesson), ['name' => 'newName'])
             ->assertStatus(403);
    }

    /** @test */
    public function it_can_not_update_if_unauthorized()
    {
        $this->putJson(route('lessons.update', $this->lesson), ['name' => 'newName'])
            ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_update_if_invalid_data()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->putJson(route('lessons.update', $this->lesson), ['name' => 1111])
             ->assertStatus(422);
    }
}
