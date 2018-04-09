<?php

namespace Tests\Feature\Courses;

use App\Enum\PermissionEnum;
use App\Events\StoreCourseEvent;
use App\Models\Course;
use App\Models\Permission;
use App\Models\TargetAudience;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course */
    protected $course;

    /** @var string */
    protected $permission;

    /** @var User $user */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->course = make(Course::class)->toArray();
        $this->permission = PermissionEnum::CREATE_COURSE;

        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_create_course_if_has_permission()
    {
        \Event::fake();

        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
            ->postJson(route('courses.store'), $this->course)
            ->assertStatus(201);

        \Event::assertDispatched(StoreCourseEvent::class, function ($e) {
            return $e->getEntity()->title === $this->course['title'];
        });

        $this->assertDatabaseHas('courses', $this->course);
    }

    /** @test */
    public function it_user_can_not_create_course_if_has_not_permission()
    {
        $this->signIn($this->user)
            ->postJson(route('courses.store'), $this->course)
            ->assertStatus(403);

        $this->assertDatabaseMissing('courses', $this->course);
    }

    /** @test */
    public function it_user_can_not_add_course_if_not_authorize()
    {
        $this->postJson(route('categories.store'))
            ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_add_course_with_exist_slug()
    {
        $this->user->givePermissionTo(PermissionEnum::CREATE_COURSE);
        create(Course::class, ['slug' => 'slug']);
        $course = make(Course::class, ['slug' => 'slug'])->toArray();

        $this->signIn($this->user)
            ->postJson(route('courses.store'), $course)
            ->assertStatus(422);

        $this->assertDatabaseMissing('courses', $course);
    }

    /** @test */
    public function it_can_not_create_course_if_category_not_exist_throw_exception()
    {
        $this->user->givePermissionTo(PermissionEnum::CREATE_COURSE);

        $this->expectException(ModelNotFoundException::class);

        $course = make(Course::class, ['category_id' => '99999999'])->toArray();

        $this->signIn($this->user)
            ->postJson(route('courses.store'), $course)
            ->assertStatus(422);

        $this->assertDatabaseMissing('courses', $course);
    }

    /** @test */
    public function it_user_can_create_course_and_target_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->course['target_audiences'] = ['target1', 'target2'];

        $response = $this->signIn($this->user)
             ->postJson(route('courses.store') , $this->course)
             ->assertStatus(201)
             ->json();

        $this->assertArrayHasKey('target_audiences', $response);
        $this->assertDatabaseHas('target_audiences', ['title' => $this->course['target_audiences'][0]]);
        $this->assertDatabaseHas('target_audiences', ['title' => $this->course['target_audiences'][1]]);
        $this->assertDatabaseHas('courses' , ['title'=> $this->course['title']]);
    }
}
