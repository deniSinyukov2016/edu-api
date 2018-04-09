<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id;
 * @property string title;
 * @property Question question
 */
class TypeAnswer extends Model
{
    protected $table = 'type_answers';
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
