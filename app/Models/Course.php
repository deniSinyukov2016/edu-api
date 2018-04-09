<?php

namespace App\Models;

use App\Enum\EventEnum;
use App\Events\CourseUserEvent\FinishCourse;
use App\Events\CourseUserEvent\BuyCourse;
use App\Events\LessonOpenEvent;
use App\Exceptions\CourseAcceptException;
use App\Http\Controllers\API\Traits\Fileable;
use App\Http\Controllers\API\Traits\Imageable;
use App\Models\Interfaces\IFileable;
use App\Observers\CourseObserver;
use App\Pivots\CourseUser;
use App\Target;
use Carbon\Carbon;
use Exception;

/**
 * @property int id
 * @property string title
 * @property string meta_keywords
 * @property string meta_description
 * @property string slug
 * @property string body
 * @property double price
 * @property int duration
 * @property boolean status
 * @property int category_id
 * @property mixed created_at
 * @property mixed updated_at
 * @property Category category
 * @property Module modules
 * @property Lesson lessons
 * @property User users
 * @property CourseUser courseUser
 * @property File files
 * */
class Course extends BaseModel implements IFileable
{
    use Imageable, Fileable;

    protected $guarded = ['id'];
    protected $whereArray = ['status', 'category_id'];
    protected $whereLikeArray = ['title', 'body'];
    protected $whereInArray = ['id'];
    protected $whereBetweenField = ['price', 'created_at'];
    protected $withField = [
        'lessons',
        'category',
        'modules',
        'events',
        'courseUser',
        'images',
        'sertificates',
        'targetAudiences'
    ];

    protected $withCountField = ['lessonsValues', 'usersValues'];

    /********************** RELATIONS *****************/
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function courseUser()
    {
        return $this->hasMany(CourseUser::class);
    }

    public function acceptorsUser()
    {
        $ids = $this->courseUser()->where('course_id', $this->id)->pluck('user_id');

        return User::query()->whereIn('id', $ids)->get();
    }

    public function images()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function sertificates()
    {
        return $this->files()->where('is_sertificate', true);
    }

    public function targetAudiences()
    {
        return $this->belongsToMany(TargetAudience::class);
    }

    public function lessonComplete()
    {
        return $this->lessons()->whereHas('lessonUser', function ($lessonUser) {
                $lessonUser->where('is_complete', true);
        });
    }
    public function lessonUncomplete()
    {
        return $this->lessons()->whereHas('lessonUser', function ($lessonUser) {
            $lessonUser->where('is_complete', false);
        });
    }


    public function addTargets(array $data)
    {
        $targets = [];

        foreach ($data as $title) {
            $targets[] = TargetAudience::query()->firstOrCreate(['title' => $title])->id;
        }

        $this->targetAudiences()->sync($targets);
    }

    public function lessonsValues()
    {
        return $this->lessons();
    }

    public function usersValues()
    {
        return $this->courseUser();
    }

    public function getImage()
    {
        return $this->images()->first();
    }

    public function hasLesson()
    {
        return $this->lessons()->count() > 0;
    }


    /**
     * Attach many users to course
     * @param array $users
     * @throws CourseAcceptException
     */
    public function attachUsers(array $users)
    {
        foreach ($users as $userId) {
            $this->validate($userId);

            $this->courseUser()->create([
                'user_id'          => $userId,
                'close_time'       => Carbon::now()->addDay($this->duration),
                'start_time'       => Carbon::now(),
                'course_status_id' => EventEnum::START_COURSE
            ]);
        }
        event(new BuyCourse($this, $users));
    }

    /**
     * Set success status for course_user
     *
     * @param array $ids
     *
     * @return int
     */
    public function setSuccess(array $ids)
    {
        $count = $this->courseUser()
            ->whereIn('user_id', $ids)
            ->update(['course_status_id' => EventEnum::FINISH_COURSE]);

        event(new FinishCourse($this, $ids));

        return $count;
    }

    public function getImageDir(): string
    {
        return '/public/courses/' . $this->id . '/';
    }

    public function getFileDir(): string
    {
        return '/public/courses/' . $this->id . '/';
    }


    /************************* MUTATORS ******************************/

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (bool)$value;
    }
    /**
     * @param $value
     *
     * @throws Exception
     */
    public function setCategoryIdAttribute($value)
    {
        /** @var Category $category */
        $category = Category::findOrFail($value);

        if (!$category->hasParent() && $category->subcategories()->count() !== 0) {
            throw new Exception('Course can not be added because category has subcategory');
        }

        $this->attributes['category_id'] = $value;
    }

    public function targets()
    {
        return $this->belongsToMany(Target::class, 'course_target');
    }

    /**
     * @param int $userId
     * @return void
     *
     * @throws CourseAcceptException
     */
    private function validate(int $userId)
    {
        if ($this->courseUser()->where('user_id', $userId)->exists()) {
            throw new CourseAcceptException();
        }
    }
}
