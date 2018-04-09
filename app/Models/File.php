<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property integer fileable_id
 * @property string fileable_type
 * @property string file
 * @property string type
 * @property double size
 * @property string original_name
 */
class File extends Model
{
    protected $guarded = ['id'];

    public function fileable()
    {
        return $this->morphTo();
    }

    public function getFileAttribute($value)
    {
        return str_replace('/public/', '/storage/', $value);
    }
}
