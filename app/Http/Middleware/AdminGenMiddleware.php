<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminGenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
      if (Auth::user()->profile->weight >= 6) {
          return $next($request);
      }
      if ($request->ajax() || $request->wantsJson()) {
          return response('Unauthorized.', 401);
      } else {
          return redirect()->guest('login');
      }
    }
}
