<?php


namespace App\Scopes\Search;

use Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

abstract class BaseWhereScope implements Scope
{
    /** @var Request $request */
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    abstract public function apply(Builder $builder, Model $model);
}
