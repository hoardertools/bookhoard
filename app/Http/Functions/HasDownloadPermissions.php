<?php

namespace App\Http\Functions;

use App\Setting;
use Closure;

class HasDownloadPermissions
{

    public static function check(): bool
    {
        if(Setting::where('key', "=", "GlobalDownloadPermissions")->exists() && Setting::where('key', "=", "GlobalDownloadPermissions")->first()->value){

            return true;

        }elseif (\Auth::user()){

            if(\Auth::user()->hasDownloadPermissions()){
                return true;
            }

        }

        return false;

    }
}
