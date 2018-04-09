<?php

namespace App\Http\Filters;

use App\Scopes\Search\SearchScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ParentFilter
{
    /** @var Model */
    protected static $model;

    public static function get(Request $request)
    {
        $builder = static::getBuilder($request);

        return static::allOrPaginate($request, $builder);
    }

    protected static function getBuilder($request)
    {
        return static::$model::query()->withGlobalScope('search',new SearchScope($request));
    }

    /**
     * @param Request $request
     * @param Builder $builder
     *
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected static function allOrPaginate(Request $request, $builder)
    {
        if ($request->get('count') === 'nolimit') {
            return $builder->get();
        }

        return $builder->paginate($request->get('count', 10));
    }

    public static function setModel($model, $request)
    {
        static::$model = $model;

        return static::get($request);
    }
}
