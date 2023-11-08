<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         return redirect(auth()->user()->getRedirectRoute());
        //     }
        // }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(Auth::user()->getRedirectRoute());
            }
        }

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         $role = $request->user()->role;
        //         switch ($role) {
        //             case 'Admin':
        //                 return redirect(route('radmin', ['id' => Auth::user()->id] ));
        //                 break;
        //             case 'Penghuni':
        //                 return redirect(route('rpenghuni', ['id' => Auth::user()->id]));
        //                 break;
        //         }
        //     }
        // }
        return $next($request);
    }
}
