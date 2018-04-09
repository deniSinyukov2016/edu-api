<?php

namespace Tests\Feature\Tests;

use App\Enum\PermissionEnum;
use App\Models\Test;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTestModelTest extends TestCase
{
    use RefreshDatabase;

    /** @var Test $test  */
    protected $test;

    /** @var string  */
    protected $permission;

    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->test = create(Test::class);
        $this->permission = PermissionEnum::DELETE_TEST;
        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_delete_test_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->deleteJson(route('tests.destroy', $this->test))
             ->assertStatus(204);

        $this->assertDatabaseMissing('tests', $this->test->toArray());
    }

    /** @test */
    public function it_user_can_not_delete_test_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->deleteJson(route('tests.destroy', $this->test))
             ->assertStatus(403);

        $this->assertDatabaseHas('tests', $this->test->toArray());
    }

    /** @test */
    public function it_can_not_delete_test_if_unauthorized()
    {
        $this->deleteJson(route('tests.destroy', $this->test))
             ->assertStatus(401);
    }
}
