<?php

namespace Tests\Feature\Users;

use App\Enum\PermissionEnum;
use App\Models\User;
use App\Notifications\ConfirmationAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AddUserTest extends TestCase
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
        $this->permission = PermissionEnum::CREATE_USER;

        $this->user->givePermissionTo($this->permission);
        $this->userData = [
            'name'     => 'Jon',
            'email'    => 'sddf144as@doe.com',
            'password' => '111111'
        ];

        Notification::fake();
    }

    /** @test */
    public function it_user_has_permission_to_add_user_success_create_user()
    {
        $response = $this->actingAs($this->user, 'api')
                         ->postJson(route('users.store'), $this->userData)
                         ->assertSee($this->userData['name'])
                         ->assertSee($this->userData['email'])
                         ->assertStatus(201)
                         ->json();

        $user = User::query()->where('id', $response['id'])->firstOrNew([]);
        $this->assertTrue($user->exists);

        Notification::assertSentTo($user, ConfirmationAccount::class);
    }

    /** @test */
    public function it_user_has_permission_to_add_user_success_create_user_with_avatar()
    {
        $this->userData['avatar'] =  UploadedFile::fake()->create('avatar.jpg');

        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('users.store'), $this->userData)
            ->assertStatus(201)
            ->json();

        /** @var User $user */
        $user = User::query()->whereKey($response['id'])->first();

        $this->assertDatabaseHas('images', [
            'imageable_id'       => $user->id,
            'imageable_type'     => User::class,
            'image'              => $user->getAvatarDir() . $this->userData["avatar"]->hashName()
        ]);

        Storage::disk('local')->assertExists($user->getAvatarDir() . $this->userData["avatar"]->hashName());
    }

    /** @test */
    public function it_user_has_not_permission_to_add_user_throw_exception()
    {
        $user = create(User::class);

        $this->actingAs($user, 'api')
             ->postJson(route('users.store'), $this->userData)
             ->assertStatus(403);

        $this->assertFalse(User::query()->where('email', $this->userData['email'])->exists());
    }

    /** @test */
    public function it_admin_create_not_confirmed_user_by_default()
    {
        $response = $this->actingAs($this->user, 'api')
                         ->postJson(route('users.store'), $this->userData)
                         ->json();

        $user = User::query()->where('id', $response['id'])->first();
        $this->assertFalse($user->is_confirm);
    }
}
