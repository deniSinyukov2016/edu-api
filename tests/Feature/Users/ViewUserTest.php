<?php

namespace Tests\Feature\Users;

use App\Enum\PermissionEnum;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->user       = create(User::class);
        $this->permission = PermissionEnum::VIEW_USERS;
    }

    /** @var  User */
    private $user;

    /** @var  Permission */
    private $permission;

    /** @test */
    public function it_can_view_users_list_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $otherUser = create(User::class);

        $response = $this->signIn($this->user)
                         ->getJson(route('users.index'))
                         ->assertStatus(200)
                         ->assertSee(auth()->user()->getAuthIdentifierName())
                         ->assertSee($otherUser->name)
                         ->json();

        $this->assertCount(2, $response['data']);
    }

    /** @test */
    public function it_user_can_not_view_users_list_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->getJson(route('users.index'))
             ->assertStatus(403);
    }

    /** @test */
    public function it_unauth_user_can_not_view_user_lists()
    {
        $this->getJson(route('users.index'))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_get_information_by_one_user()
    {
        $this->user->givePermissionTo($this->permission);

        $otherUser = create(User::class);

        $this->signIn($this->user)
             ->getJson(route('users.show', $otherUser))
             ->assertStatus(200)
             ->assertSee($otherUser->name)
             ->assertSee($otherUser->email);
    }

    /** @test */
    public function it_can_not_view_one_user_if_has_not_permission()
    {
        $otherUser = create(User::class);

        $this->signIn($this->user)
             ->getJson(route('users.show', $otherUser))
             ->assertStatus(403);
    }

    /** @test */
    public function it_unauth_user_can_not_view_one_user_information()
    {
        $this->getJson(route('users.show', 1))
             ->assertStatus(401);
    }

    /** @test */
    public function it_user_can_view_list_users_by_array_id()
    {
        $this->user->givePermissionTo($this->permission);
        /** @var User $users  */
        $users = create(User::class, [], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('users.index', ['id[0]' => $users[0]->id, 'id[1]' => $users[1]->id]))
            ->assertStatus(200)
            ->assertSee($users[0]->name)
            ->json();

        $this->assertEquals(2, $response['total']);
    }

    /** @test */
    public function it_user_can_view_list_users_by_name_where_like()
    {
        $this->user->givePermissionTo($this->permission);
        /** @var User $users  */
        $users = create(User::class, ['name' => 'Name is'.str_random(10)], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('users.index', ['name' => 'ame is']))
            ->assertStatus(200)
            ->assertSee($users[0]->name)
            ->json();

        $this->assertEquals(5, $response['total']);
    }
    /** @test */
    public function it_user_can_view_list_users_by_email_where()
    {
        $this->user->givePermissionTo($this->permission);
        /** @var User $user */
        $user = create(User::class, ['email' => 'email@email.ru']);

        $response = $this->signIn($this->user)
            ->getJson(route('users.index', ['email' => $user->email]))
            ->assertStatus(200)
            ->assertSee($user->name)
            ->json();

        $this->assertEquals(1, $response['total']);
    }
}
