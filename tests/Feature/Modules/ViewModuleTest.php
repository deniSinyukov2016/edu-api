<?php

namespace Tests\Feature\Modules;

use App\Enum\PermissionEnum;
use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @var Module $module  */
    protected $module;

    /** @var Permission $permission  */
    protected $permission;

    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user         = create(User::class);
        $this->permission   = PermissionEnum::VIEW_MODULE;
        $this->module       = create(Module::class);
    }

    /** @test */
    public function it_user_can_view_list_modules()
    {
        $this->user->givePermissionTo($this->permission);

        /** @var Module $modules  */
        $modules = create(Module::class, [], 5);

        $response = $this->signIn($this->user)
             ->getJson(route('modules.index'))
             ->assertStatus(200)
             ->assertSee($modules[0]->title)
             ->json();

        //6(six) because, before insert one row module. Method setUp()
        $this->assertEquals(6, $response['total']);
    }

    /** @test */
    public function it_user_can_not_view_list_modules_has_not_permission()
    {
        $this->signIn($this->user)
             ->getJson(route('modules.index'))
             ->assertStatus(403);
    }

    /** @test */
    public function it_user_can_not_view_list_modules_is_not_authorized()
    {
        $this->getJson(route('modules.index'))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_view_one_module()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->getJson(route('modules.show', $response = $this->module))
             ->assertStatus(200)
             ->assertSee($response->title);
    }

    /** @test */
    public function it_can_not_view_one_module_not_permission()
    {
        $this->signIn($this->user)
             ->getJson(route('modules.show', $response = $this->module))
             ->assertStatus(403)
             ->assertDontSee($response->title);
    }

    /** @test */
    public function it_can_not_view_one_module_not_authorized()
    {
        $this->getJson(route('modules.show', $response = $this->module))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_view_module_by_title()
    {
        $this->user->givePermissionTo($this->permission);
        $moduleByTitle = create(Module::class, ['course_id' => $this->module->course_id], 3);

        $response = $this->signIn($this->user)
            ->getJson(route('modules.index', ['course_id' => $this->module->course_id]))
            ->assertStatus(200)
            ->assertSee($moduleByTitle[0]->title)
            ->assertSee($moduleByTitle[1]->slug)
            ->json();

        $this->assertEquals(4, $response['total']);
    }

    /** @test */
    public function it_user_can_view_list_modules_by_array_id()
    {
        $this->user->givePermissionTo($this->permission);
        /** @var Module $courses  */
        $modules = create(Module::class, [], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('modules.index', ['id[0]' => $modules[0]->id, 'id[1]' => $modules[1]->id]))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(2, $response['total']);
    }


    /** @test */
    public function it_can_view_list_course_by_part_title()
    {
        $this->user->givePermissionTo($this->permission);
        create(Module::class, ['title' => 'This title in request '.str_random(10) ], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('modules.index', ['title' => 'title in']))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(5, $response['total']);
    }

    /** @test */
    public function it_can_view_all_modules_in_one_query()
    {
        $this->user->givePermissionTo($this->permission);
        create(Module::class, [], 20);

        $response = $this->signIn($this->user)
            ->getJson(route('modules.index', ['count' => 'nolimit']))->json();

        $this->assertCount(21, $response);
    }
}
