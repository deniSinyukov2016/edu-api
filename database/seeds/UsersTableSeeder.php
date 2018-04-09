<?php

use App\Models\User;

class UsersTableSeeder extends BaseSeeder
{
    public function run()
    {
        /** @var User $admin */
        $admin = create(User::class, [
            'name'     => 'admin',
            'email'    => 'admin@admin.ru',
            'password' => '111111'
        ]);
        $admin->update(['api_token' => 'hE5YUn3s2SyYTNTjXg1EBaVOxGAfYN2coWTwR84ryTbpsfF4GcuHZCWNDxvS']);
        $role = \App\Models\Role::query()->where(['name' => 'admin'])->first();

        $admin->assignRole($role);

        if (config('app.env') !== 'production') {
            create(User::class, [], 10);
        }
    }
}
