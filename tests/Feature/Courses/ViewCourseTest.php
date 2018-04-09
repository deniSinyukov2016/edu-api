<?php

namespace Tests\Feature\Courses;

use App\Enum\PermissionEnum;
use App\Enum\RoleEnum;
use App\Models\Course;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @var string */
    protected $permission;
    /** @var User $user */
    protected $user;
    /** @var string */
    protected $role;

    public function setUp()
    {
        parent::setUp();

        $this->permission   = PermissionEnum::VIEW_COURSE;
        $this->user         = create(User::class);
        $this->role         = RoleEnum::ROLE_ADMIN;
    }

    /** @test */
    public function it_user_can_view_list_courses()
    {
        create(Course::class, [], 3);

        $response = $this->getJson(route('courses.index'))
             ->assertStatus(200)
             ->json();
        $this->assertCount(3, $response['data']);
    }

    /** @test */
    public function it_user_can_view_one_course()
    {
        /** @var Course $course */
        $course = create(Course::class);
        $this->signIn();
        auth()->user()->givePermissionTo($this->permission);

        $course->courseUser()->create(['user_id' => auth()->id()]);

        $this->getJson(route('courses.show', $course))
             ->assertStatus(200)
             ->assertSee($course->body);
    }

    /** @test */
    public function it_user_can_not_view_one_course_if_dont_buy_course()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->getJson(route('courses.show', $course = create(Course::class)))
             ->assertStatus(403)
             ->assertDontSee($course->body);
    }
    
    /** @test */
    public function it_user_not_authorized()
    {
        $this->getJson(route('courses.show', $course = create(Course::class)))
             ->assertStatus(401);
    }


    /** @test */
    public function it_user_can_view_list_course_by_array_id()
    {
        /** @var Course $courses  */
        $courses = create(Course::class, [], 5);

        $response = $this->getJson(route('courses.index', ['id[0]' => $courses[0]->id, 'id[1]' => $courses[1]->id]))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(2, $response['total']);
    }

    /** @test */
    public function it_user_can_view_list_course_by_status_active()
    {
        create(Course::class, ['status' => 1], 5);

        $response = $this->getJson(route('courses.index', ['status' => true]))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(5, $response['total']);
    }

    /** @test */
    public function it_can_view_list_course_by_part_title()
    {
        create(Course::class, ['title' => 'This title in request '.str_random(10) ], 5);

        $response = $this->getJson(route('courses.index', ['title' => 'title in']))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(5, $response['total']);
    }

    /** @test */
    public function it_can_view_list_course_between_start_and_end_prices()
    {
        create(Course::class, ['price' => 1100.4]);
        create(Course::class, ['price' => 1100.8]);
        create(Course::class, ['price' => 45000]);

        $response = $this->getJson(route('courses.index', ['start' => 1100, 'end' => 50000]))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(3, $response['total']);
    }

    /** @test */
    public function it_user_can_view_any_course_if_role_admin()
    {
        $this->user->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);

        $this->signIn($this->user)
             ->getJson(route('courses.show', $course = create(Course::class)))
             ->assertStatus(200)
             ->assertSee($course->body);
    }

    /** @test */
    public function it_user_can_not_view_any_course_if_role_not_admin()
    {
        $this->user->givePermissionTo($this->permission);
        $this->user->assignRole(RoleEnum::ROLE_USER);

        $this->signIn($this->user)
             ->getJson(route('courses.show', $course = create(Course::class)))
             ->assertStatus(403)
             ->assertDontSee($course->body);
    }
    /** @test */
    public function it_user_can_view_list_courses_with_count_users_and_lessons()
    {
        $this->user->givePermissionTo($this->permission);
        create(Course::class, [], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('courses.index', ['withCount' => 'lessonsValues,usersValues']))
            ->assertStatus(200)
            ->json();

        $this->assertArrayHasKey('lessons_values_count', $response['data'][0]);
        $this->assertArrayHasKey('users_values_count', $response['data'][4]);

        $this->assertEquals(5, $response['total']);
    }
    /** @test */
    public function it_user_can_view_list_courses_with_count_users_and_lessons_and_sorting_by_this_fields()
    {
        $this->user->givePermissionTo($this->permission);
        create(Course::class, [], 5);

        $this->signIn($this->user)
            ->getJson(route('courses.index', [
                'withCount' => 'lessonsValues,usersValues',
                'sort_by'   => 'lessons_values_count',
                'order_by'  => 'desc',
                'count'     => '10'
                ]))
                ->assertStatus(200)
                ->assertExactJson(Course::query()
                ->withCount('lessonsValues', 'usersValues')
                ->orderBy('users_values_count', 'desc')
                ->paginate('10')->toArray());
    }
}
