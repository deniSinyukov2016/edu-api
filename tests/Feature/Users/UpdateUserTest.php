<?php

namespace Tests\Feature\Users;

use App\Enum\PermissionEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    /** @var User $user */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_has_permission_to_update_user()
    {
        $this->user->givePermissionTo(PermissionEnum::UPDATE_USER);

        $user = create(User::class, ['name' => 'John']);

        $this->signIn($this->user)
             ->putJson(route('users.update', $user), $newData = ['name' => 'Jane'])
             ->assertStatus(200);

        $this->assertEquals($newData['name'], $user->fresh()->name);
    }

    /** @test */
    public function it_can_update_avatar_for_user()
    {
        Storage::fake();

        $this->user->givePermissionTo(PermissionEnum::UPDATE_USER);
        /** @var User $user */
        $user = create(User::class, ['name' => 'John']);
        $user->addAvatar(UploadedFile::fake()->create("avatar.jpg"));

        $uploadFile = UploadedFile::fake()->create('new_avatar.png');

        $this->signIn()
            ->postJson(route('users.avatar.update', $user), [
                'avatar'    => $uploadFile
            ])
            ->assertStatus(204);

        $this->assertDatabaseHas('images', [
            'imageable_id'       => $user->id,
            'imageable_type'     => User::class,
            'image'              => $user->getAvatarDir() . $uploadFile->hashName()
        ]);

        Storage::disk('local')->assertExists($user->getAvatarDir() . $uploadFile->hashName());
    }

    /** @test */
    public function it_user_has_not_permission_to_update_user()
    {
        $user = create(User::class);

        $this->signIn($this->user)
             ->putJson(route('users.update', $user))
             ->assertStatus(403);

        $this->assertEquals($user->toArray(), $user->fresh()->toArray());
    }

    /** @test */
    public function it_unauth_user_can_not_update_user()
    {
        $this->putJson(route('users.update', 1))
             ->assertStatus(401);
    }
}
