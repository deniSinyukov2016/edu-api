<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfirmUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_user_can_confirm_email()
    {
        $user = create(User::class, ['is_confirm' => false]);

        $this->getJson(route('confirmation-account', $user->api_token))
             ->assertRedirect(url(config('app.frontend_url')));

        $this->assertNotEquals($user->api_token, $user->fresh()->api_token);

        $user = $user->fresh();

        $this->assertTrue($user->is_confirm);
    }

    /** @test */
    public function if_twice_link_click_do_nothing()
    {
        $user = create(User::class, ['is_confirm' => false]);

        $this->getJson(route('confirmation-account', $user->api_token))
             ->assertRedirect(url(config('app.frontend_url')));

        $this->assertNotEquals($user->api_token, $user->fresh()->api_token);

        $user = $user->fresh();

        $this->assertTrue($user->is_confirm);

        $this->getJson(route('confirmation-account', $user->api_token))
             ->assertRedirect(url(config('app.frontend_url')));
        $this->assertEquals($user->api_token, $user->fresh()->api_token);
    }

    /** @test */
    public function it_can_get_email_on_token()
    {
        /** @var User $user */
        $user = create(User::class, ['email' => 'email@email.ru']);

        $this->getJson(route('users.email', ['token' => $user->api_token]))
            ->assertStatus(200)
            ->assertSee($user->email);
    }

    /** @test */
    public function it_can_not_get_email_on_token_if_incorrect_token()
    {
        $this->getJson(route('users.email', ['token' => str_random(60)]))
             ->assertSee("null");
    }
}
