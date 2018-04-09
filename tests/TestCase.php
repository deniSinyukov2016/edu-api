<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signIn($user = null)
    {
        $user = $user ?? factory(User::class)->create();

        $this->be($user, 'api');

        return $this;
    }
}
