<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next){
        if($request->input('_token')) {
            if ( \Session::getToken() != $request->input('_token')) {
                return redirect()->back()->withInput(\Request::except('_token'))->withErrors('Your session has expired. Please try again, your session has been restored now.');
            }
        }

        return parent::handle($request, $next);
    }
}
