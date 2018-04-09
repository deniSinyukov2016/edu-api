<?php


namespace App\Scopes\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WhereScope extends BaseWhereScope
{
    public function apply(Builder $builder, Model $model)
    {
        if (!empty($model->getWhereArray())) {
            foreach ($this->request->only($model->getWhereArray()) as $key => $value) {
                if (!is_null($value)) {
                    $builder->where($key, $value);
                }
            }
        }
    }

}