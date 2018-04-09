<?php

namespace Tests\Feature\TestUser;

use App\Models\Test;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTestUserTest extends TestCase
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
    public function it_can_view_time_left_for_course()
    {
        $this->signIn();

        $this->test->testUser()->create([
            'user_id'       => auth()->id(),
            'start'         => Carbon::now(),
            'end'           => Carbon::now()->addHour($this->test->time_passing),
            'count_attemps' => $this->test->count_attemps
        ]);

        $response = $this->getJson(route('tests.time.test', [
            'test'  => $this->test,
            'user'  => auth()->user()
        ]))
            ->assertStatus(200)
            ->json();

//        $this->assertArrayHasKey('tile_left', $response);
    }
    /** @test */
    public function it_can_not_view_time_left_for_course_if_user_does_attemp_test()
    {
        $this->signIn();

        $response = $this->getJson(route('tests.time.test', [
            'test'  => $this->test,
            'user'  => auth()->user()
        ]))
            ->assertStatus(200)
            ->json();

        $this->assertEquals('not found', $response[0]);
    }


    /** @test */
    public function it_can_not_view_time_left_for_course_if_not_auth()
    {
        $this->getJson(route('tests.time.test', [
            'test'  => $this->test,
            'user'  => create(User::class)
        ]))->assertStatus(401);
    }
}
