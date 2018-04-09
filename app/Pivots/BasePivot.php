<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.03.18
 * Time: 14:16
 */

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BasePivot extends Pivot
{
    /** @var null|array */
    protected $whereArray = [];
    /** @var null|array */
    protected $whereLikeArray = [];
    /** @var array */
    protected $whereInArray = [];
    /** @var null|string */
    protected $whereBetweenField = null;
    /** @var null array */
    protected $withField = null;

    /**
     * @return array|null
     */
    public function getWhereArray()
    {
        return $this->whereArray;
    }

    /**
     * @return array|null
     */
    public function getWhereLikeArray()
    {
        return $this->whereLikeArray;
    }

    /**
     * @return array
     */
    public function getWhereInArray()
    {
        return $this->whereInArray;
    }

    /**
     * @return string|null
     */
    public function getWhereBetweenField()
    {
        return $this->whereBetweenField;
    }

    public function getWithField()
    {
        return $this->withField;
    }
}