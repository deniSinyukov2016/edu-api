<?php

namespace Tests\Feature\Modules;

use App\Enum\PermissionEnum;
use App\Models\Module;
use App\Models\Permission;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @var array $module  */
    protected $module;
    
    /** @var Permission $permission  */
    protected $permission;

    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->module = make(Module::class)->toArray();
        $this->permission = PermissionEnum::CREATE_MODULE;
        $this->user = create(User::class);
    }

    /** @test */
    public function it_can_create_module_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->postJson(route('modules.store'), $this->module)
             ->assertStatus(201);

        $this->assertDatabaseHas('modules', $this->module);
    }

    /** @test */
    public function it_can_not_create_module_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->postJson(route('modules.store'), $this->module)
             ->assertStatus(403);

        $this->assertDatabaseMissing('modules', $this->module);
    }

    /** @test */
    public function it_user_can_not_add_course_if_not_authorize()
    {
        $this->postJson(route('modules.store'))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_create_module_if_slug_exist()
    {
        $this->user->givePermissionTo($this->permission);

        create(Module::class, ['slug' => 'slugable']);
        $module = make(Module::class, ['slug' => 'slugable'])->toArray();

        $this->signIn($this->user)
             ->postJson(route('modules.store'), $module)
             ->assertStatus(422);
    }
}
