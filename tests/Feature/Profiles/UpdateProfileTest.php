<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function if_old_password_incorrect_throw_validation_exception()
    {
        $user = create(User::class, ['password '=> '111111']);
        $request = [
            'old_password'          => '111112',
            'password'              => 'qwerty',
            'password_confirmation' => 'qwerty'
        ];

        $this->signIn($user)
             ->putJson(route('profile.update'), $request)
             ->assertStatus(422);
    }

    /** @test */
    public function if_new_password_equals_old_password_throw_exception()
    {
        $user = create(User::class, ['password' => '222222']);
        $request = [
            'old_password'          => '222222',
            'password'              => '222222',
            'password_confirmation' => '222222'
        ];

        $this->signIn($user)
             ->putJson(route('profile.update'), $request)
             ->assertStatus(422);
    }

    /** @test */
    public function if_all_data_correct_update_password_and_api_token()
    {
        $user = create(User::class, ['password' => '333333']);
        $oldToken = $user->api_token;

        $request = [
            'old_password'          => '333333',
            'password'              => '1111111',
            'password_confirmation' => '1111111'
        ];

        $this->signIn($user)
             ->putJson(route('profile.update'), $request)
             ->assertStatus(200);

        $this->assertNotEquals($oldToken, $user->fresh()->api_token);
    }

    /** @test */
    public function it_can_update_all_user_information()
    {
        $user = create(User::class, ['name' => 'John']);
        $request = ['name'  => 'Bob'];

        $this->signIn($user)
            ->putJson(route('profile.update'), $request)
            ->assertJsonFragment($request);

        $this->assertEquals($request['name'], $user->fresh()->name);
    }
}
