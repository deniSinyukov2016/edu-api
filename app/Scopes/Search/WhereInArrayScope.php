<?php

namespace App\Scopes\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WhereInArrayScope extends BaseWhereScope
{
    public function apply(Builder $builder, Model $model)
    {
        if (!empty($model->getWhereInArray())) {
            foreach ($this->request->only($model->getWhereInArray()) as $key => $value) {
                if (!is_null($value)) {
                    $builder->whereIn($key, $value);
                }
            }
        }
    }
}
