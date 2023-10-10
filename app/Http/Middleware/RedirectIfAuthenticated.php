<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         return redirect(RouteServiceProvider::HOME);
        //     }
        // }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $roleCode = Auth::user()->role_code;
                $userAppsCategory = Auth::user()->user_apps_category;

                if ($userAppsCategory == 'evoria') {
                    return redirect('/evoria-dashboard');
                }
                if ($userAppsCategory == 'blocx' || is_null($userAppsCategory)) {
                    if ($roleCode == 'CASHIER') {
                        return redirect('/cashier');
                    } else {
                        return redirect('/home');
                    }
                }
            }
        }

        return $next($request);
    }
}
