<?php

namespace App\Http\Middleware\Admin;

use Closure;

class IPRestriction
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
        $adminRole = \Auth::user()->roleUser;
        $userIpAddress = $adminRole->registered_ip_address;
        $roleIpAddress = $adminRole->role->whitelisted_ip_addresses;

        if ($adminRole && $request->segment(1) == 'admin' && env('ENABLE_IP_RESTRICTION', 0) == 1) {
            $allowedIpAddress = null;
            $byPass = false;

            if ($userIpAddress == 'open') {
                $byPass = true;
            } else {
                if ($userIpAddress != null) {
                    $allowedIpAddress = $userIpAddress;
                } elseif ($roleIpAddress != null) {
                    $allowedIpAddress = $roleIpAddress;
                }
            }

            if ($byPass != true) {
                $allowedIpAddress = explode(',', $allowedIpAddress);

                // Get the IP address
                $ip = $request->ip();

                // Check if the ip is valid or if allowedIpAddress
                if (!filter_var($ip, FILTER_VALIDATE_IP) || !in_array($ip, $allowedIpAddress)) {
                    \Auth::logout();

                    return abort('403_admin');
                }
            }
        }

        return $next($request);
    }
}
