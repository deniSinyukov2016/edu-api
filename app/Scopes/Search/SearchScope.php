<?php

namespace App\Scopes\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SearchScope extends BaseWhereScope
{
    protected $builders = [
        WhereScope::class,
        WhereLikeArrayScope::class,
        WhereInArrayScope::class,
        WhereBetweenScope::class,
        WithScope::class,
        WithCountScope::class,
    ];

    public function apply(Builder $builder, Model $model)
    {
        foreach ($this->builders as $class) {
            (new $class($this->request))->apply($builder, $model);
        }
    }
}
