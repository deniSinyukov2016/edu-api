<?php

namespace Tests\Feature\TestUser;

use App\Models\Test;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTestUserTest extends TestCase
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
    public function it_can_reset_time_for_test_if_exist_attemps()
    {
        $this->signIn();

        $this->test->testUser()->create([
            'user_id'       => auth()->id(),
            'start'         => Carbon::now(),
            'end'           => Carbon::now()->addHour($this->test->time_passing),
            'count_attemps' => $this->test->count_attemps
        ]);
        $this->deleteJson(route('tests.destroy.test', [
                'test'  => $this->test,
                'user'  => auth()->user()
        ]))->assertStatus(200);

        $this->assertDatabaseHas('test_users', [
            'user_id'   => auth()->id(),
            'test_id'   => $this->test->id,
            'start'     => Carbon::now(),
            'end'       => Carbon::now(),
            'count_attemps' => $this->test->count_attemps
        ]);
    }

    /** @test */
    public function it_can_not_reset_time_for_test_if_not_auth()
    {
        $this->deleteJson(route('tests.destroy.test', [
            'test'  => $this->test,
            'user'  => create(User::class)
        ]))->assertStatus(401);
    }
}
