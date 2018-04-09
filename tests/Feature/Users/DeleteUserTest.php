<?php

namespace Tests\Feature\Users;

use App\Enum\PermissionEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    /** @var User $user */
    protected $user;

    /** @var Permission $permission */
    protected $permission;

    protected $userData;

    public function setUp()
    {
        parent::setUp();

        $this->user       = create(User::class);
        $this->permission = PermissionEnum::DELETE_USER;

        $this->user->givePermissionTo($this->permission);
        $this->userData = [
            'name'     => 'Jon',
            'email'    => 'sddf144as@doe.com',
            'password' => '111111'
        ];
    }


    /** @test */
    public function it_user_has_permission_to_delete_user()
    {
        $user = create(User::class);

        $this->actingAs($this->user, 'api')
             ->delete(route('users.destroy', $user))
             ->assertStatus(204);

        $this->assertFalse(User::query()->whereKey($user->id)->exists());
    }

    /** @test */
    public function it_user_has_not_permission_to_delete_user()
    {
        $user = create(User::class);

        $this->user->revokePermissionTo(PermissionEnum::DELETE_USER);

        $this->actingAs($this->user, 'api')
             ->deleteJson(route('users.destroy', $user))
             ->assertStatus(403);

        $this->assertTrue(User::query()->whereKey($user->id)->exists());
    }

    /** @test */
    public function it_user_can_not_remove_self()
    {
        $user = create(User::class);

        $this->signIn($this->user)
             ->deleteJson(route('users.destroy', $this->user))
             ->assertStatus(403);
    }
}
