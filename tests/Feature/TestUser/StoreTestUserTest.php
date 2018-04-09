<?php

namespace Tests\Feature\TestUser;

use App\Events\CourseUserEvent\NoMoreAttemptsTest;
use App\Models\Test;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTestUserTest extends TestCase
{
    use RefreshDatabase;

    /** @var Test $test */
    private $test;

    public function setUp()
    {
        parent::setUp();

        $this->test = create(Test::class);
    }

    /** @test */
    public function it_can_start_timer_for_test()
    {

        $response = $this->signIn()
             ->postJson(route('tests.store.test', [
                 'test'  => $this->test,
                 'user'  => auth()->user()
             ]))
            ->assertStatus(200)
            ->json();

        $this->assertDatabaseHas('test_users', [
            'user_id'   => auth()->id(),
            'test_id'   => $this->test->id
        ]);
        $this->assertArrayHasKey('count_attemps', $response);
    }

    /** @test */
    public function it_can_get_time_for_test_if_timer_started()
    {
        $this->signIn();

        $this->test->testUser()->create([
            'user_id'       => auth()->id(),
            'start'         => Carbon::now(),
            'end'           => Carbon::now()->addHour(15),
            'count_attemps' => $this->test->count_attemps
        ]);

        $response = $this->postJson(route('tests.store.test', [
                'test'  => $this->test,
                'user'  => auth()->user()
            ]))
            ->assertStatus(200)
            ->json();

        $this->assertArrayHasKey('user_id', $response);
    }

    /** @test */
    public function it_can_not_start_test_if_doesnt_exist_attemps()
    {
        $this->signIn();

        $this->test->testUser()->create([
            'user_id'       => auth()->id(),
            'start'         => Carbon::now(),
            'end'           => Carbon::now()->addMinute(-15),
            'count_attemps' => 0
        ]);
        $response = $this->postJson(route('tests.store.test', [
            'test'  => $this->test,
            'user'  => auth()->user()
        ]))->json();

        $this->assertDatabaseHas('events', [
            'user_id'       => auth()->id(),
            'course_id'     => $this->test->lesson->course->id
        ]);

        $this->assertArrayHasKey('message', $response);
    }



    /** @test */
    public function it_can_not_start_timer_for_test_if_not_auth()
    {

        $this->postJson(route('tests.store.test', [
            'test'  => $this->test,
            'user'  => create(User::class)
        ]))->assertStatus(401);
    }
}
