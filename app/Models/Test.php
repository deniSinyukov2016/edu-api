<?php

namespace App\Models;

use App\Events\CourseUserEvent\NoMoreAttemptsTest;
use App\Pivots\TestUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * @property int id
 * @property boolean is_require
 * @property time time_passing
 * @property boolean is_random
 * @property int count_attemps
 * @property int count_correct;
 * @property boolean is_success;
 * @property int lesson_id;
 * @property Lesson lesson
 * @property Question questions
 * @property TestUser testUser
 */
class Test extends BaseModel
{
    protected $guarded = ['id'];
    protected $whereArray = ['is_random', 'count_attemps', 'lesson_id', 'count_correct'];
    protected $whereLikeArray = ['name', 'description'];
    protected $whereInArray = ['id'];
    protected $whereBetweenField = 'created_at';
    protected $casts = ['is_random' => 'boolean'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function testUser()
    {
        return $this->hasMany(TestUser::class);
    }


    public function isSuccess()
    {
        return $this->is_success;
    }

    /**
     * Show all question and correct answers for test
     */
    public function questionAnswers()
    {
        return $this->questions->each(function (Question $question) {
            $question->load([
                'answers' => function ($answer) {
                    $answer->select(['title', 'question_id', 'id']);
                }
            ]);
        });
    }

    /**
     * @param User $user
     *
     * @return array
     * @throws Exception
     */
    public function startTest(User $user)
    {
        $time = date_parse_from_format('H:i:s', $this->time_passing);

        if ($this->testUser()->where(function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists()) {
            return $this->resetTest($user);
        }

        return $this->testUser()->create([
            'user_id'       => $user->id,
            'start'         => Carbon::now(),
            'end'           => Carbon::now()->addHour($time['hour'])->addMinute($time['minute'])->addSecond($time['second']),
            'count_attemps' => $this->count_attemps - 1
        ]);
    }

    public function getEndTime(User $user)
    {
        if ($this->testUser()->where(function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists()) {
            return TestUser::getTestUser($user, $this)->first();
        }

        return false;
    }

    /**
     * @param User $user
     *
     * @return TestUser|array
     * @throws Exception
     */
    public function resetTest($user)
    {
        $time = date_parse_from_format('H:i:s', $this->time_passing);

        if (!$this->testUser()->where('user_id', $user->id)->exists()) {
            return [];
        }

        /** @var TestUser $testUser */
        $testUser = TestUser::getTestUser($user, $this)->first();

        if ($testUser->count_attemps > 0) {
            TestUser::query()->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('test_id', $this->id);
            })->update([
                'count_attemps' => $testUser->count_attemps - 1,
                'start'         => Carbon::now(),
                'end'           => Carbon::now()->addHour($time['hour'])->addMinute($time['minute'])->addSecond($time['second']),
            ]);

            return $testUser;
        }

        event(new NoMoreAttemptsTest($this->lesson->course, [$user->id]));

        return ['message' => trans('messages.no_more_attempts')];
    }


    /**
     *If not exist lesson throw exception
     *
     * @param $value
     */
    public function setLessonIdAttribute($value)
    {
        if (!isset($value) || !Lesson::query()->where('id', $value)->exists()) {
            throw new ModelNotFoundException('Lesson not found');
        }

        $this->attributes['lesson_id'] = $value;
    }

    /**
     * Value must be more zero
     *
     * @param $value
     *
     * @throws Exception
     */
    public function setCountAttempsAttribute($value)
    {
        if ($value < 0) {
            throw new Exception('The number of attempts can not be satisfied and less than 0');
        }

        $this->attributes['count_attemps'] = $value;
    }

    /**
     * Value must be more zero
     *
     * @param $value
     *
     * @throws Exception
     */
    public function setCountCorrectAttribute($value)
    {
        if ($value <= 0) {
            throw new Exception('Not correct answers must be more zero.');
        }

        $this->attributes['count_correct'] = $value;
    }
}
