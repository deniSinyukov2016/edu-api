<?php

/**
 * @param string|\Illuminate\Database\Eloquent\Model $class
 * @param null $count
 * @param array $overrides
 *
 * @return mixed
 */
function create($class, array $overrides = [], $count = null)
{
    return factory($class, $count)->create($overrides);
}

/**
 * @param string|\Illuminate\Database\Eloquent\Model $class
 * @param null|int $count
 * @param array $overrides
 *
 * @return mixed
 */
function make($class, array $overrides = [], $count = null)
{
    return factory($class, $count)->make($overrides);
}
