<?php

namespace App\Scopes\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WhereLikeArrayScope extends BaseWhereScope
{
    public function apply(Builder $builder, Model $model)
    {
        if (!empty($model->getWhereLikeArray())) {
            foreach ($this->request->only($model->getWhereLikeArray()) as $key => $value) {
                if (!is_null($value)) {
                    $builder->where($key, 'LIKE', '%' . $value . '%');
                }
            }
        }
    }
}
