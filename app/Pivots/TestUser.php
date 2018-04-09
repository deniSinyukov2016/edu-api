<?php

namespace App\Pivots;

use App\Models\Test;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string end
 * @property integer count_attemps
 * @property bool is_success
 */
class TestUser extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'test_users';
    protected $guarded = ['id'];

    public static function getTestUser(User $user, Test $test)
    {
        return TestUser::query()->where(function ($query) use ($user, $test) {
            $query->where('user_id', $user->id)->where('test_id', $test->id);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    public function getCreatedAtColumn()
    {
        return 'created_at';
    }

    public function getUpdatedAtColumn()
    {
        return 'updated_at';
    }
}
