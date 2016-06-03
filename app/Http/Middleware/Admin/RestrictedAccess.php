<?php

namespace App\Http\Middleware\Admin;

use Closure;

class RestrictedAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $admin = \Auth::user();
        $allow = false;

        if ($admin->roles()->whereId(1)->first()) {
           $allow = true;
        }

        return ($allow) ? $next($request) : abort('403_admin');
    }
}
