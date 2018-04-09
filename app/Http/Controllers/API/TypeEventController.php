<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Filters\ModelFilter;
use App\Http\Filters\ParentFilter;
use App\Models\TypeEvent;

class TypeEventController extends Controller
{
    /**
     * @apiDesc Display types event list
     *
     * @apiParam integer $count in_query | Count display events, 10 by default (nolimit - all)
     *
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', TypeEvent::class);

        return response()->json(ParentFilter::setModel(TypeEvent::class,request()));
    }
}
