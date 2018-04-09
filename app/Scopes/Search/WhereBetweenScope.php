<?php

namespace App\Scopes\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WhereBetweenScope extends BaseWhereScope
{
    /**
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        if (!empty($model->getWhereBetweenField())) {
            foreach ($this->request->only($model->getWhereBetweenField()) as $key => $value)
            {
                $builder->where(function ($query) use ($key, $value) {
                            if(!empty($value['value'])){
                               $query->where($key,'>=',$value['value']);
                            }
                            if(!empty($value['valueto'])) {
                               $query -> where($key, '<=', $value['valueto']);
                            }
                });
            }
        }
    }
}