<?php

namespace App\Http\Functions;

use App\Setting;
use Closure;
use Illuminate\Http\Request;

class HasWritePermissions
{

    public static function check(): bool
    {
        if(Setting::where('key', "=", "GlobalWritePermissions")->exists() && Setting::where('key', "=", "GlobalWritePermissions")->first()->value){

            return true;

        }elseif (\Auth::user()){

            if(\Auth::user()->hasWritePermissions()){
                return true;
            }

        }

        return false;

    }
}
