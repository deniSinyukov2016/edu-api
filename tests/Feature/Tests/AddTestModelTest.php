<?php

namespace Tests\Feature\Tests;

use App\Enum\PermissionEnum;
use App\Models\Test;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddTestModelTest extends TestCase
{
    use RefreshDatabase;

    /** @var User $user  */
    protected $user;
    /** @var string  */
    protected $permission;
    /** @var Test $test  */
    protected $test;

    public function setUp()
    {
        parent::setUp();

        $this->test = make(Test::class)->toArray();
        $this->permission = PermissionEnum::CREATE_TEST;
        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_create_test_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $response = $this->signIn($this->user)
             ->postJson(route('tests.store'), $this->test)
             ->assertStatus(201)
             ->json();

        $this->assertDatabaseHas('tests', $response);
    }

    /** @test */
    public function it_user_can_not_create_test_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->postJson(route('tests.store'), $this->test)
             ->assertStatus(403);

        $this->assertDatabaseMissing('tests', $this->test);
    }

    /** @test */
    public function it_can_not_create_test_if_not_authorized()
    {
        $this->postJson(route('tests.store'), $this->test)
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_create_test_if_invalid_data()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->postJson(route('tests.store'), ['time_passing' => 12451])
             ->assertStatus(422);

        $this->assertDatabaseMissing('tests', ['time_passing' => 12451]);
    }
}
