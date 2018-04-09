<?php

namespace App\Http\Controllers\API;

use App\Http\Filters\EventFilter;
use App\Http\Filters\ParentFilter;
use App\Models\Course;
use App\Models\Event;
use App\Models\User;
use App\Scopes\Search\SearchScope;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use function PHPSTORM_META\type;

class EventController extends Controller
{
    /**
     * @apiDesc Display events list
     *
     * @apiParam integer $count in_query | Count display events, 10 by default. (nolimit - all)
     * @apiParam integer $user_id in_query | Set filter by user_id field, null by default
     * @apiParam integer $course_id in_query | Set filter by course_id field, null by default
     * @apiParam integer $name in_query | Set filter by name field for user, null by default
     * @apiParam integer $title in_query | Set filter by title field for course, null by default
     * @apiParam integer $event_type_id in_query | Set filter by event_type_id field, null by default
     * @apiParam string  $with in_query | Loads additionally info. Sample query: course,user
     *
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', Event::class);

        return response()->json(EventFilter::get(request()));
    }

    /**
     * @apiDesc Display single event
     * @apiParam integer $event in_path required| Event id
     * @apiParam string  $with in_query | Loads additionally info. Sample query: course,user
     *
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiErr  404 | Not fount .
     *
     * @param Event $event
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Event $event)
    {
        $this->authorize('view', Event::class);

        if (request()->exists('with')) {
            $with = explode(',', request()->get('with'));
        }

        return response()->json($event->load($with ?? []));
    }


    /**
     * @apiDesc Delete event by id
     * @apiParam integer $event in_path required| Event id
     *
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiErr  404 | Not fount .
     * @apiResp 204 | Whatever message is send from backend on success deleted
     *
     * @param Event $event
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', Event::class);

        return response()->json($event->delete(), 204);
    }
}
