<?php

namespace App\Scopes\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WithScope extends BaseWhereScope
{
    public function apply(Builder $builder, Model $model)
    {
        if ($this->request->exists('with')) {
            $with = explode(',', $this->request->get('with'));

            foreach ($with as $relation) {
                if (in_array($relation, $model->getWithField(), true)) {
                    $builder->with($relation);
                }
            }
        }
    }
}