<?php

namespace App\Http\ViewComposers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class CategoriesComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
       $view->with('categories', Category::query()
            ->whereNull('parent_id')
            ->whereHas('subcategories', function ($q) {
                /** @var Builder $q */
                $q->whereHas('courses', function ($q) {
                    /** @var Builder $q */
                    $q->where('status', 1);
                });
            })->get());
    }
}