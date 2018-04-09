<?php

namespace Tests\Feature\Modules;

use App\Enum\PermissionEnum;
use App\Models\Module;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @var Module $module  */
    protected $module;

    /** @var string  */
    protected $permission;

    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->module = create(Module::class);
        $this->permission = PermissionEnum::DELETE_MODULE;
        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_delete_module_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->deleteJson(route('modules.destroy', $this->module))
             ->assertStatus(204);

        $this->assertDatabaseMissing('modules', $this->module->toArray());
    }

    /** @test */
    public function it_user_can_not_delete_module_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->deleteJson(route('modules.destroy', $this->module))
             ->assertStatus(403);

        $this->assertDatabaseHas('modules', $this->module->toArray());
    }

    /** @test */
    public function it_can_not_delete_if_unauthorized()
    {
        $this->deleteJson(route('modules.destroy', $this->module))
             ->assertStatus(401);
    }
}
