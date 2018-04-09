<?php

namespace App\Models;

use App\Enum\LessonTypeEnum;
use App\Http\Controllers\API\Traits\Fileable;
use App\Models\Interfaces\IFileable;
use App\Observers\LessonObserver;
use App\Pivots\LessonUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * @property int id
 * @property string name
 * @property string description
 * @property string file
 * @property int type_lessons_id
 * @property int module_id
 * @property int course_id
 * @property Module module
 * @property Course course
 * @property Test test
 * @property File files
 */
class Lesson extends BaseModel implements IFileable
{
    use Fileable;

    protected $guarded        = ['id'];
    protected $whereArray     = ['file', 'module_id', 'course_id', 'type_lessons_id'];
    protected $whereLikeArray = ['name', 'description'];
    protected $whereInArray   = ['id'];
    protected $withField      = ['files', 'course', 'test', 'module', 'typeLessons'];

    public static function boot()
    {
        parent::boot();

        static::observe(LessonObserver::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Course where locate lesson
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function test()
    {
        return $this->hasOne(Test::class, 'lesson_id');
    }

    public function typeLessons()
    {
        return $this->belongsTo(TypeLesson::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function lessonUser()
    {
        return $this->hasMany(LessonUser::class);
    }
    public function lessonStatus()
    {
        return $this->lessonUser();
    }

    public function getFileDir(): string
    {
        return '/public/files/lessons/' . $this->id . '/';
    }

    /**
     * Set status test
     *
     * @param Course $course
     * @param bool $status
     *
     * @return bool
     * @throws Exception
     */
    public function setTestStatus(Course $course, bool $status): bool
    {
        $this->validate($course);

        return $this->test()->update(['is_success' => $status]);
    }

    /**
     * If not exist model throw exception
     *
     * @param $value
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function setTypeLessonsIdAttribute($value)
    {
        if (!isset($value) || !TypeLesson::query()->where('id', $value)->exists()) {
            throw new ModelNotFoundException('Type can not be added because model not exist in database');
        }
        $this->attributes['type_lessons_id'] = $value;
    }

    /**
     * If not exist model throw exception
     *
     * @param $value
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws Exception
     */
    public function setModuleIdAttribute($value)
    {
        if (!isset($value) || !Module::query()->where('id', $value)->exists()) {
            throw new ModelNotFoundException('Type can not be added because model not exist in database');
        }

        $this->attributes['module_id'] = $value;
    }

    /**
     * If not exist model throw exception
     *
     * @param $value
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws Exception
     */
    public function setCourseIdAttribute($value)
    {
        $query = Course::query()->where('id', $value);

        if (!isset($value) || !$query->exists()) {
            throw new ModelNotFoundException('Type can not be added because model not exist in database');
        }

        //if ($query->first()->modules()->count() > 0) {
        //    throw new Exception('Course has modules');
        //}

        $this->attributes['course_id'] = $value;
    }

    /**
     * @param Course $course
     *
     * @return bool
     * @throws Exception
     */
    private function validate(Course $course)
    {
        /** If exist course for auth user */
        if ($course->courseUser()->where('user_id', auth()->id())->exists()) {
            throw new Exception('Course not found for auth user');
        }
        /** If lesson not found for course */
        if (!$course->lessons()->where('id', $this->id)->exists()) {
            throw new Exception('Lesson not found for course');
        }
        /** Type lesson do not test */
        if ($course->lessons()->where('id', $this->id)->first()->type_lessons_id !== LessonTypeEnum::LESSON_TYPE_TEST) {
            throw new Exception('Type of lesson is not a test');
        }
        /** If test exists */
        if (!$this->test()->exists()) {
            throw new Exception('Test not found for this lesson');
        }

        return true;
    }

    public function setComplete(array $users)
    {
        $lessonNext = $this->next($this->course->lessons);

        foreach ($users as $userId) {
            $query = $this->lessonUser()->where('user_id', $userId);
            $query->update([
                'is_complete' => true,
            ]);

            $this->lessonUser()->where(function ($lessonUser) use ($userId, $lessonNext) {
                $lessonUser->where('user_id', $userId)->where('lesson_id', $lessonNext)
                           ->update(['is_close' => false]);
            });
        }
    }

    public function next(Collection $collection)
    {
        $previous = null;
        foreach ($collection as $item) {
            if (!empty($previous && $previous->id == $this->id)) {
                return $item->id;
            }
            $previous = $item;
        }
        return null;
    }

    public function previous(Collection $collection)
    {
        $previous = null;
        foreach ($collection as $item) {
            if (!empty($previous && $item->id == $this->id)) {
                return $previous;
            }
            $previous = $item;
        }
        return null;
    }
}
