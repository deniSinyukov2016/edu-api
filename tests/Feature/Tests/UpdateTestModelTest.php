<?php

namespace Tests\Feature\Tests;

use App\Enum\PermissionEnum;
use App\Models\Test;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTestModelTest extends TestCase
{
    use RefreshDatabase;

    /** @var Test $test */
    protected $test;

    /** @var string */
    protected $permission;

    /** @var User $user  */
    protected $user;

    /** @var array  */
    protected $data = [];

    public function setUp()
    {
        parent::setUp();

        $this->test = create(Test::class, ['count_attemps' => 10]);
        $this->permission = PermissionEnum::UPDATE_TEST;
        $this->user = create(User::class);
        $this->data = ['count_attemps' => 5];
    }

    /** @test */
    public function it_user_can_update_test_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);
        $this->signIn($this->user)
             ->putJson(route('tests.update', $this->test), $newData = $this->data)
             ->assertStatus(200)
             ->assertJsonFragment($newData);

        $this->assertEquals($this->test->fresh()->count_attemps, $newData['count_attemps']);
    }

    /** @test */
    public function it_user_can_not_update_test_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->putJson(route('tests.update', $this->test), $this->data)
             ->assertStatus(403);

        $this->assertDatabaseHas('tests', $this->test->toArray());
    }

    /** @test */
    public function it_can_not_update_if_unauthorized()
    {
        $this->putJson(route('tests.update', $this->data))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_update_test_if_invalid_data()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->putJson(route('tests.update', $this->test), ['time_passing' => false])
             ->assertStatus(422);

        $this->assertDatabaseHas('tests', $this->test->toArray());
    }
}
