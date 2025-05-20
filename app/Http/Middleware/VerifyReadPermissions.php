<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyReadPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Setting::where('key', "=", "GlobalReadPermissions")->exists() && Setting::where('key', "=", "GlobalReadPermissions")->first()->value){

            return $next($request);

        }elseif (\Auth::user()){

            if(\Auth::user()->hasReadPermissions()){
                return $next($request);
            }

        }

        return redirect('/permission-denied')->with('error', 'You do not have permission to view these pages.');

    }
}
