<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @var User $user */
    protected $user;
    protected $data;

    public function setUp()
    {
        parent::setUp();

        $this->user = create(User::class);
        $this->data = [
            'email'    => $this->user->email,
            'password' => '111111'
        ];
    }

    /** @test */
    public function it_user_can_success_logged_in()
    {
        $this->postJson(route('login'), $this->data)
             ->assertStatus(200)
             ->assertJsonStructure(['id', 'name', 'email', 'created_at', 'updated_at', 'api_token']);
    }

    /** @test */
    public function it_user_entered_not_all_data_for_success_logged_in()
    {
        $this->postJson(route('login'))
             ->assertStatus(422);
    }
}
