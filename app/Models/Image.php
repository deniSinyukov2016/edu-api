<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string image
 * @property integer imageable_id
 * @property string imageable_type
 */
class Image extends Model
{
    protected $guarded = ['id'];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getImageAttribute($value)
    {
        return str_replace('/public/', '/storage/', $value);
    }
}
