<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.03.18
 * Time: 18:12
 */

namespace App\Scopes\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WithCountScope extends BaseWhereScope
{
    public function apply(Builder $builder, Model $model)
    {
        if ($this->request->exists('withCount')) {
            $withCount = explode(',', $this->request->get('withCount'));
            foreach ($withCount as $relation) {
                if (in_array($relation, $model->getWithCountFields(), true)) {
                    $builder->withCount($relation);
                }
            }
        }
    }
}