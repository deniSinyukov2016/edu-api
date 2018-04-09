<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfirmationMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_throw_exception_if_auth_user_not_confirmed()
    {
        $user = create(User::class, ['is_confirm' => false]);

        $this->signIn($user)
             ->getJson(route('profile.show'))
             ->assertStatus(403);
    }

    /** @test */
    public function it_show_data_if_user_confirm()
    {
        $user = create(User::class, ['is_confirm' => true]);

        $this->signIn($user)
             ->getJson(route('profile.show'))
             ->assertStatus(200);
    }
}
