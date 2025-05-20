<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;

    public static function log(string $message, string $type, string $level){
        $log = new Log();
        $log->message = $message;
        $log->type = $type;
        $log->level = $level;
        $log->save();
    }
}
