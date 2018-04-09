<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
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
    /** @var null array */
    protected $withCountField = null;

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

    /**
     * @return string|null
     */
    public function getWithCountFields()
    {
        return $this->withCountField;
    }
}
