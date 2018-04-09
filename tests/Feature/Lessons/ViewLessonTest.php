<?php

namespace Tests\Feature\Lessons;

use App\Enum\PermissionEnum;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewLessonTest extends TestCase
{
    use RefreshDatabase;
    
    /** @var User $user  */
    protected $user;

    /** @var string  */
    protected $permission;

    /** @var Lesson $lesson  */
    protected $lesson;

    public function setUp()
    {
        parent::setUp();

        $this->user = create(User::class);
        $this->permission = PermissionEnum::VIEW_LESSON;
        $this->lesson = create(Lesson::class);
    }

    /** @test */
    public function it_user_can_view_list_lessons_if_has_permissions()
    {
        $this->user->givePermissionTo($this->permission);

        create(Lesson::class, [], 10);

        $response = $this->signIn($this->user)
            ->getJson(route('lessons.index'))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(11, $response['total']);
    }

    /** @test */
    public function it_user_can_not_view_list_lessons_if_has_not_permissions()
    {
        $this->signIn($this->user)
             ->getJson(route('lessons.index'))
             ->assertStatus(403);
    }

    /** @test */
    public function it_user_not_authorized()
    {
        $this->getJson(route('lessons.index'))->assertStatus(401);
    }

    /** @test */
    public function it_can_view_list_lessons_by_module()
    {
        $this->user->givePermissionTo($this->permission);
        /** @var Module $module  */
        $module = create(Module::class);

        /** @var Lesson $lessons  */
        $lessons = create(Lesson::class, ['module_id' => $module->id ], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('lessons.index', ['module_id' => $module->id]))
            ->assertStatus(200)
            ->assertSee($lessons[0]->name)
            ->json();

        $this->assertEquals(5, $response['total']);
    }

    /** @test */
    public function it_can_view_list_lessons_by_course()
    {
        $this->user->givePermissionTo($this->permission);
        /** @var Course $course  */
        $course = create(Course::class);

        /** @var Lesson $lessons  */
        $lessons = create(Lesson::class, ['course_id' => $course->id ], 5);

        $response = $this->signIn($this->user)
             ->getJson(route('lessons.index', ['course_id' => $course->id]))
             ->assertStatus(200)
             ->assertSee($lessons[0]->name)
             ->json();

        $this->assertEquals(5, $response['total']);
    }

    /** @test */
    public function it_can_view_list_lessons_by_course_and_module()
    {
        $this->user->givePermissionTo($this->permission);
        /** @var Course $course  */
        $course = create(Course::class);
        /** @var Module $module  */
        $module = create(Module::class);

        /** @var Lesson $lessons  */
        $lessons = create(Lesson::class, ['course_id' => $course->id, 'module_id' => $module->id], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('lessons.index', ['course_id' => $course->id, 'module_id' => $module->id]))
            ->assertStatus(200)
            ->assertSee($lessons[0]->name)
            ->json();

        $this->assertEquals(5, $response['total']);
    }

    /** @test */
    public function it_user_can_view_single_lesson_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);
        $this->signIn($this->user)
             ->getJson(route('lessons.show', $this->lesson))
             ->assertStatus(200)
             ->assertJson($this->lesson->toArray());
    }

    /** @test */
    public function it_user_can_not_view_single_lesson_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->getJson(route('lessons.show', $this->lesson))
             ->assertStatus(403);
    }

    /** @test */
    public function it_user_can_not_view_single_lesson_if_not_authorized()
    {
        $this->getJson(route('lessons.show', $this->lesson))
            ->assertStatus(401);
    }

    /** @test */
    public function it_user_can_view_list_lessons_by_array_id()
    {
        $this->user->givePermissionTo($this->permission);

        /** @var Lesson $lessons  */
        $lessons = create(Lesson::class, [], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('lessons.index', ['id[0]' => $lessons[0]->id, 'id[1]' => $lessons[1]->id]))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(2, $response['total']);
    }


    /** @test */
    public function it_can_view_list_course_by_part_title()
    {
        $this->user->givePermissionTo($this->permission);

        create(Lesson::class, ['name' => 'This title in request '.str_random(10) ], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('lessons.index', ['name' => 'title in']))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(5, $response['total']);
    }

    /** @test */
    public function it_can_view_all_lessons_in_one_query()
    {
        $this->user->givePermissionTo($this->permission);
        create(Lesson::class, [], 20);

        $response = $this->signIn($this->user)
            ->getJson(route('lessons.index', ['count' => 'nolimit']))->json();

        $this->assertCount(21, $response);
    }

    /** @test */
    public function it_can_view_list_lessons_with_all_fields()
    {
        $this->user->givePermissionTo($this->permission);

        $response = $this->signIn($this->user)
            ->getJson(route('lessons.index', ['with' => 'files,course,test,module,typeLessons']))
            ->assertStatus(200)
            ->json();

        $this->assertArrayHasKey('files', $response['data'][0]);
        $this->assertArrayHasKey('course', $response['data'][0]);
        $this->assertArrayHasKey('test', $response['data'][0]);
        $this->assertArrayHasKey('module', $response['data'][0]);
        $this->assertArrayHasKey('type_lessons', $response['data'][0]);
    }
}
