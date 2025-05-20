<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyWritePermissions
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Setting::where('key', "=", "GlobalWritePermissions")->exists() && Setting::where('key', "=", "GlobalWritePermissions")->first()->value){

            return $next($request);

        }elseif (\Auth::user()){

            if(\Auth::user()->hasWritePermissions()){
                return $next($request);
            }

        }

        return redirect('/permission-denied')->with('error', 'You do not have permission to write or make changes.');

    }
}
