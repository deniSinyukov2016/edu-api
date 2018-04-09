<?php

namespace App\Models;

use App\Http\Controllers\API\Traits\Avatarable;
use App\Notifications\ResetPassword;
use App\Pivots\CourseUser;
use App\Pivots\LessonUser;
use App\Pivots\TestUser;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int id;
 * @property string name;
 * @property mixed email;
 * @property mixed password;
 * @property mixed api_token;
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed remember_token;
 * @property Course courses
 * @property TestUser testUser
 * */
class User extends Authenticate
{
    use Notifiable, HasRoles, Avatarable;

    protected $guard_name = 'api';
    protected $guarded = ['id'];
    protected $hidden = ['remember_token', 'password'];
    protected $whereArray = ['email'];
    protected $whereLikeArray = ['name'];
    protected $whereInArray = ['id'];
    protected $withField = ['courseUser'];
    protected $casts = ['is_confirm' => 'boolean'];

    public static function emailToken(string $token)
    {
        $query = User::query()->where('api_token', $token);

        if ($query->exists()) {
            return ['email' => $query->first()->email];
        }

        return ['email' => null];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->api_token = str_random(60);
        });
    }

    /************************ MUTATORS ************************/

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /************************ RELATIONS ************************/
    public function acceptorsCourse()
    {
        $courseUsers = $this->courseUser()->where('user_id', $this->id)->pluck('course_id')->toArray();

        $courses =  $this->courseWith($courseUsers)->get();

        $courses->map(function ($course) {
            $course->lessons->map(function ($lesson) {
                $query = $lesson->lessonUser()->where('user_id', $this->id);
                $lessonUser = $query->exists() ? $query->first()->is_complete : 0;
                $lesson->status = $lessonUser;
            });
        });

        return $courses;
    }

    public function courseWith(array $ids)
    {
        return Course::query()->whereIn('id', $ids)->with([
            'courseUser' => function ($courseUser) {
                $courseUser->where('user_id', $this->id)->with('courseStatus');
            },
        ])->with(['lessons.typeLessons', 'modules'])
            ->withCount('lessons')
            ->withCount(['lessonComplete' => function ($lesson) {
                $lesson->whereHas('lessonUser', function ($lessonUser) {
                    $lessonUser->where('user_id', $this->id);
                });
            }])
            ->withCount(['lessonUncomplete' => function ($lesson) {
                $lesson->whereHas('lessonUser', function ($lessonUser) {
                    $lessonUser->where('user_id', $this->id);
                });
            }]);
    }

    public function avatar()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * All course for user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseUser()
    {
        return $this->hasMany(CourseUser::class);
    }

    public function testUser()
    {
        return $this->hasMany(TestUser::class);
    }

    public function lessonUser()
    {
        return $this->hasMany(LessonUser::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function getAvatarDir(): string
    {
        return '/storage/images/avatars/' . $this->id . '/';
    }
}
