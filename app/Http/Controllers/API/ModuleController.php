<?php

namespace App\Http\Controllers\API;

use App\Http\Filters\ParentFilter;
use App\Http\Requests\Module\StoreModuleRequest;
use App\Http\Requests\Module\UpdateModuleRequest;
use App\Models\Module;
use App\Http\Controllers\Controller;
use App\Scopes\Search\SearchScope;

class ModuleController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \InvalidArgumentException|\Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Display module list
     * @apiParam integer $count in_query | Count display modules, 10 by default
     * @apiParam string $course_id in_query | Set filter by course_id field, null by default
     * @apiErr  401 | Unauthorized
     * @apiErr  403 | Unauthorized access
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function index()
    {
        $this->authorize('view', Module::class);

        return response()->json(ParentFilter::setModel(Module::class,request()));
    }

    /**
     * @param StoreModuleRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Add new module
     * @apiParam string  $title in_query required| Module title
     * @apiParam string  $slug in_query required| Module slug
     * @apiParam string  $description in_query |Module description
     * @apiParam integer $course_id in_query required|Module course_id
     * @apiErr  422 | Validation failed
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 201 | Whatever module success created
     */
    public function store(StoreModuleRequest $request)
    {
        return response()->json(Module::query()->create($request->validated()), 201);
    }

    /**
     * @param Module $module
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Show module by id
     * @apiParam integer $module in_path required| Module id
     * @apiErr  404 | Module not found
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 200 | Whatever module success show
     */
    public function show(Module $module)
    {
        $this->authorize('view', Module::class);

        return response()->json($module);
    }

    /**
     * @param UpdateModuleRequest $request
     * @param Module $module
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Update current module
     * @apiParam integer $module in_path required| Module id
     * @apiParam string  $title in_query | Module title
     * @apiParam string  $slug in_query | Module slug
     * @apiParam string  $description in_query | Module description
     * @apiParam integer $course_id in_query | Module $course_id
     * @apiErr 422 | Validation failed
     * @apiErr  404 | Module not found
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 200 | Whatever module success updated
     */
    public function update(UpdateModuleRequest $request, Module $module)
    {
        $module->update($request->validated());

        return response()->json($module);
    }

    /**
     * @param Module $module
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Delete module by id
     * @apiParam integer $module in_path required| Module id
     * @apiErr  404 | Module not found
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 204 | Whatever module success removed
     */
    public function destroy(Module $module)
    {
        $this->authorize('delete', Module::class);

        return response()->json($module->delete(), 204);
    }
}
