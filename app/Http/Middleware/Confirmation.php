<?php

namespace App\Http\Middleware;

use App\Exceptions\NotConfirmedUser;
use Closure;

class Confirmation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     * @throws \App\Exceptions\NotConfirmedUser
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()->is_confirm) {
            throw new NotConfirmedUser();
        }

        return $next($request);
    }
}
