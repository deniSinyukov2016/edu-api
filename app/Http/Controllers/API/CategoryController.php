<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Filters\CategoryFilter;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Scopes\Search\SearchScope;

class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     *
     * @apiDesc  Display categories or subcategories list
     * @apiParam integer $count in_query | Count display categories, 10 by default (nolimit - all categories)
     * @apiParam integer $parent_id in_query | Set filter by parent_id field, null by default
     * @apiParam string $with in_query | Set with : 'subcategories', 'courses'. Delimiter ','
     * @apiParam string $whereLike in_query | Set whereLike : name. Delimiter ','
     * @apiResp  200 | Whatever message is send from backend on success
     */
    public function index()
    {
        return response()->json(CategoryFilter::get(request()));
    }

    /**
     * @param StoreCategoryRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Store new category or subcategory
     * @apiParam string $name in_query required| Category name
     * @apiParam string $slug in_query required| Category slug
     * @apiParam string $parent_id in_query| Category id, who will be parent
     * @apiErr 422 | Validation failed
     * @apiErr 404 | Category or subcategory not found
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 201 | Whatever message is send from backend on success
     */
    public function store(StoreCategoryRequest $request)
    {
        return response()->json(Category::query()->create($request->validated()), 201);
    }

    /**
     * @param int $category
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Show category by id
     * @apiParam integer $category in_path required| Category id
     * @apiErr 404 | Category or subcategory not found
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function show(int $category)
    {
        return response()->json(Category::query()->with('subcategories')
                ->with('images')->whereKey($category)->first());
    }

    /**
     * @param UpdateCategoryRequest $request
     * @param Category $category
     *
     * @return Category
     *
     * @apiDesc Update category or subcategory
     * @apiParam string $category in_path required | Category id
     * @apiParam string $name in_query | Category name
     * @apiParam string $slug in_query | Category slug
     * @apiParam string $parent_id in_query| Category id, who will be parent
     * @apiErr 422 | Validation failed
     * @apiErr 404 | Category or subcategory not found
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return response()->json($category);
    }

    /**
     * @param Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception|\Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Delete category or subcategory
     * @apiParam string $category in_path required | Category id
     * @apiErr 404 | Category or subcategory not found
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 204 | If category or subcategory success removed
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', Category::class);

        return response()->json($category->delete(), 204);
    }
}
