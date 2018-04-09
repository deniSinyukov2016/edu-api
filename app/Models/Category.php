<?php

namespace App\Models;

use App\Http\Controllers\API\Traits\Imageable;
use Exception;

/**
 * @property int id
 * @property int parent_id
 * @property int courses_count
 * @property Category parent
 * @property \Illuminate\Support\Collection subcategories
 */
class Category extends BaseModel
{
    use Imageable;

    protected $guarded = ['id'];
    protected $whereArray = ['parent_id'];
    protected $whereLikeArray = ['name'];
    protected $whereInArray = ['id'];
    protected $whereBetweenField = 'created_at';
    protected $withField = ['subcategories', 'parent', 'courses', 'images'];


    public function hasParent()
    {
        return $this->parent_id !== null;
    }

    public function subcategories()
    {
        if ($this->hasParent()) {
            throw new Exception('Subcategory can not has subcategory');
        }

        return $this->hasMany(static::class, 'parent_id');
    }

    public function parent()
    {
        if ($this->hasParent()) {
            return $this->belongsTo(static::class, 'parent_id');
        }

        throw new Exception('Category can not has parent');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }

    /**
     * Total count course
     *
     * @return int
     */
    public function getCoursesCountAttribute()
    {
        return $this->courses()->count();
    }


    /**
     * If on added parent category exist course in category throw exception
     *
     * @param $value
     *
     * @throws Exception
     */
    public function setParentIdAttribute($value)
    {
        if (isset($value)) {
            /** @var Category $category */
            $category = static::findOrFail($value);

            if ($category->courses_count > 0) {
                throw new Exception('Category has already courses');
            }
        }

        $this->attributes['parent_id'] = $value;
    }

    public function images()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getImage()
    {
        return $this->images()->first();
    }

    public function getImageDir(): string
    {
        return '/public/images/categories/' . $this->id . '/';
    }
}
