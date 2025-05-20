<?php

namespace App\Http\Functions;

use App\Setting;
use Closure;
use Illuminate\Http\Request;

class HasReadPermissions
{

    public static function check(): bool
    {
        if(Setting::where('key', "=", "GlobalReadPermissions")->exists() && Setting::where('key', "=", "GlobalReadPermissions")->first()->value){

            return true;

        }elseif (\Auth::user()){

            if(\Auth::user()->hasReadPermissions()){
                return true;
            }

        }

        return false;

    }
}
