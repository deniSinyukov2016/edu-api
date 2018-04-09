<?php

namespace Tests\Feature\Modules;

use App\Enum\PermissionEnum;
use App\Models\Module;
use App\Models\Permission;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @var Module $module */
    protected $module;

    /** @var Permission $permission  */
    protected $permission;

    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->module = create(Module::class);
        $this->permission = PermissionEnum::UPDATE_MODULE;

        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_update_module_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->putJson(route('modules.update', $this->module), $newData = ['description' => 'new description'])
             ->assertStatus(200)
             ->assertJsonFragment($newData);

        $this->assertEquals($this->module->fresh()->description, $newData['description']);
    }

    /** @test */
    public function it_user_can_not_update_module_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->putJson(route('modules.update', $this->module), ['description' => 'test'])
             ->assertStatus(403);

        $this->assertDatabaseHas('modules', $this->module->toArray());
    }

    /** @test */
    public function it_can_not_update_if_unauthorized()
    {
        $this->putJson(route('modules.update', 1))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_update_if_invalid_data()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->putJson(route('modules.update', $this->module), ['slug' => 'slug'])
             ->assertStatus(422);

        $this->assertDatabaseHas('modules', $this->module->toArray());
    }

    /** @test */
    public function it_can_not_update_if_slug_exist()
    {
        $this->user->givePermissionTo($this->permission);

        $module = create(Module::class, ['slug' => 'sluguble']);

        $this->signIn($this->user)
             ->putJson(route('modules.update', $module), ['slug' => 'sluguble'])
             ->assertStatus(422);

        $this->assertDatabaseHas('modules', $module->toArray());
    }
}
