<?php

namespace Tests\Feature\Tests;

use App\Enum\PermissionEnum;
use App\Models\Test;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTestModelTest extends TestCase
{
    use RefreshDatabase;

    /** @var Test $test  */
    protected $test;

    /** @var string */
    protected $permission;

    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user         = create(User::class);
        $this->permission   = PermissionEnum::VIEW_TEST;
        $this->test         = create(Test::class);
    }

    /** @test */
    public function it_user_can_view_list_tests()
    {
        $this->user->givePermissionTo($this->permission);

        /** @var Test $tests  */
        $tests = create(Test::class, [], 10);

        $response = $this->signIn($this->user)
            ->getJson(route('tests.index'))
            ->assertStatus(200)
            ->assertSee($tests[0]->time_passing)
            ->json();

        $this->assertEquals(11, $response['total']);
    }

    /** @test */
    public function it_user_can_not_view_list_tests_has_not_permission()
    {
        $this->signIn($this->user)
             ->getJson(route('tests.index'))
             ->assertStatus(403);
    }

    /** @test */
    public function it_user_can_not_view_list_tests_is_not_authorized()
    {
        $this->getJson(route('tests.index'))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_view_one_test()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->getJson(route('tests.show', $this->test))
             ->assertStatus(200)
             ->assertSee($this->test->time_passing);
    }

    /** @test */
    public function it_can_not_view_one_test_not_permission()
    {
        $this->signIn($this->user)
             ->getJson(route('tests.show', $this->test))
             ->assertStatus(403)
             ->assertDontSee($this->test->time_passing);
    }

    /** @test */
    public function it_can_not_view_one_test_not_authorized()
    {
        $this->getJson(route('tests.show', $this->test))
             ->assertStatus(401);
    }

    /** @test */
    public function it_user_can_view_list_course_by_array_id()
    {
        $this->user->givePermissionTo($this->permission);
        /** @var Test $tests  */
        $tests = create(Test::class, [], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('tests.index', ['id[0]' => $tests[0]->id, 'id[1]' => $tests[1]->id]))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(2, $response['total']);
    }

    /** @test */
    public function it_user_can_view_list_course_by_is_random_active()
    {
        $this->user->givePermissionTo($this->permission);

        create(Test::class, ['is_random' => true], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('tests.index', ['is_random' => true]))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(5, $response['total']);
    }


    /** @test */
    public function it_can_view_list_course_between_start_and_end_date()
    {
        $this->user->givePermissionTo($this->permission);

        create(Test::class, ['created_at' => '2020-02-27 12:20:36']);
        create(Test::class, ['created_at' => '2020-02-28 12:20:36']);
        create(Test::class, ['created_at' => '2020-02-29 12:20:36']);
        create(Test::class, ['created_at' => '2018-02-19 12:20:36']);

        $response = $this->signIn($this->user)
            ->getJson(route('tests.index', [
                'created_at[value]' => '2020-02-29 12:20:36']))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(1, $response['total']);
    }

    /** @test */
    public function it_can_view_all_tests_in_one_query()
    {
        $this->user->givePermissionTo($this->permission);
        create(Test::class, [], 20);

        $response = $this->signIn($this->user)
            ->getJson(route('tests.index', ['count' => 'nolimit']))->json();

        $this->assertCount(21, $response);
    }
}
