<?php

namespace App\Http\Filters;

use App\Models\Course;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class EventFilter extends ParentFilter
{
    protected static $model = Event::class;

    /**
     * @param Request $request
     *
     * @return Collection|Paginator
     *
     * @throws \InvalidArgumentException
     */
    public static function get(Request $request)
    {
        /** @var Builder $builder */
        $builder = static::getBuilder($request);

        $builder->where(function ($q) {
            /** @var Builder $q */
            if (request()->exists('title')) {
                $courses = Course::query()->where('title', 'LIKE', '%' . request('title') . '%')->pluck('id');
                $q->whereIn('course_id', $courses);
            }

            if (request()->exists('name')) {
                $users = User::query()->where('name', 'LIKE', '%' . request('name') . '%')->pluck('id');
                $q->orWhereIn('user_id', $users);
            }
        });

        return static::allOrPaginate($request, $builder);
    }
}