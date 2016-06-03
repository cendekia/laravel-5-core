<?php

namespace App\Http\Middleware\Admin;

use Closure;

class AdminAuthenticate
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
        if (!\Auth::check()) {
            return redirect('admin/signin');
        }

        if (\Auth::user()->restricted_access != 1) {
            return redirect('/');
        }

        return $next($request);
    }
}
