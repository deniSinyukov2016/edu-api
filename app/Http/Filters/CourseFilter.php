<?php

namespace App\Http\Filters;


use App\Models\Course;
use App\Scopes\Search\SearchScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class CourseFilter extends ParentFilter
{
    protected static $model = Course::class;

    /**
     * @param Request $request
     * @return Collection|Paginator
     */
    public static function get(Request $request)
    {
        /** @var Builder $builder */
        $builder = static::getBuilder($request);

        if (!$request->exists('sort_by')) {
            $builder->orderBy('id', 'desc');
        } else {
            $builder->orderBy($request->get('sort_by'), $request->get('order_by', 'desc'));
        }

        return static::allOrPaginate($request, $builder);
    }
}
