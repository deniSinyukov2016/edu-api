<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Filters\ParentFilter;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Notifications\ConfirmationAccount;
use App\Scopes\Search\SearchScope;

class UsersController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \InvalidArgumentException|\Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Display users list
     * @apiParam integer $count in_query | Count display users, 10 by default
     * @apiParam string $with in_query | Set with : 'courseUser'. Delimiter ','
     * @apiParam string $whereLike in_query | Set whereLike : 'name'.
     * @apiParam string $whereArray in_query | Set whereLike : 'email'.
     * @apiParam string $whereInArray in_query | Set whereLike : 'id'. Sample:  array ids []
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function index()
    {
        $this->authorize('view', User::class);

        return response()->json(ParentFilter::setModel(User::class,request()));
    }

    /**
     * @param StoreUserRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Add new user
     * @apiParam string $name in_query required| User name
     * @apiParam string $email in_query required| User email
     * @apiParam string $password in_query required| User password
     * @apiParam file   $avatar in_query | Avatar
     * @apiErr 422 | Validation failed
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 201 | User success created
     * @throws \App\Exceptions\ImageException
     */
    public function store(StoreUserRequest $request)
    {
        /** @var User $user */
        $user = User::query()->create(array_except($request->validated(), 'avatar'));

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $user->addAvatar($request->file('avatar'));
        }

        $user->notify(new ConfirmationAccount($request->get('password')));

        return response()->json($user, 201);
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Show user by id
     *
     * @apiParam integer $user in_path required| User id
     * @apiParam string  $with in_query | Loads additionally info. Sample query: courseUser
     *
     * @apiErr  404 | User not found
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function show(User $user)
    {
        $this->authorize('view', User::class);

        if (request()->exists('with')) {
            $with = explode(',', request()->get('with'));
        }

        return response()->json($user->load($with ?? []));
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     * @apiDesc Update current user
     * @apiParam integer $user in_path required| User id
     * @apiParam string $name in_query | User name
     * @apiParam string $email in_query | User email
     * @apiParam string $password in_query | User password
     * @apiParam file $avatar in_query | Avatar
     * @apiErr 422 | Validation failed
     * @apiErr 404 | User not found
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 200 | User was success updated
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return response()->json($user);
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception|\Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Delete user by id
     * @apiParam integer $user in_path required| User id
     * @apiErr 404 | User not found
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 204 | User was success removed
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        return response()->json($user->delete(), 204);
    }

    /**
     * @apiDesc Update avatar user
     * @apiParam integer $user in_path required| User id
     * @apiErr 404 | User not found
     * @apiErr 401 | Unauthorized .
     * @apiResp 204 | User was success removed
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvatar(User $user)
    {
        $this->validate(request(), ['avatar' => 'required|image|mimes:jpeg,jpg,png,gif']);

        if (request()->hasFile('avatar') && request()->file('avatar')->isValid()) {
            $user->updateAvatar(request()->file('avatar'));

            return response()->json([], 204);
        }

        return response()->json($user->fresh()->load('avatar'));
    }
}
