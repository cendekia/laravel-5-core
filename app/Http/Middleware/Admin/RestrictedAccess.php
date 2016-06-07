<?php

namespace App\Http\Middleware\Admin;

use App\User;
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
        $role = $admin->roles();
        $allow = false;

        if ($role->whereId(1)->first()) {
           $allow = true;
        } else {
            $action = \Route::getCurrentRoute()->getAction();

            if (isset($action['as']) && User::hasAccess($action['as'])) {
                $allow = true;
            }
        }

        return ($allow) ? $next($request) : abort('403_admin');
    }
}
