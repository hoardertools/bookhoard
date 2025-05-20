<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyDownloadPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Setting::where('key', "=", "GlobalDownloadPermissions")->exists() && Setting::where('key', "=", "GlobalDownloadPermissions")->first()->value){

            return $next($request);

        }elseif (\Auth::user()){

            if(\Auth::user()->hasDownloadPermissions()){
                return $next($request);
            }

        }

        return redirect('/permission-denied')->with('error', 'You do not have permission to download files.');

    }
}
