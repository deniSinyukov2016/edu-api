<?php

namespace App\Http\Filters;


use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class CategoryFilter extends ParentFilter
{
    protected static $model = Category::class;

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

        if (!$request->exists('parent_id')) {
            $builder->whereNull('parent_id');
        }

        return static::allOrPaginate($request, $builder);
    }
}