<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function view(){
        return view('pages.log-center')->with([
            'logs' => Log::orderBy("id", "DESC")->take(2000)->get()
        ]);
    }
}
