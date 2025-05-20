<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegexController extends Controller
{
    public function parse(Request $request)
    {
        $regex = $request->input('regex');

        // Extract named groups using PHP regex
        $pattern = '/\(\?P?<([a-zA-Z_][a-zA-Z0-9_]*)>/';
        preg_match_all($pattern, $regex, $matches);
        \Session::flash('regex', $regex);
        return response()->json([
            'groups' => $matches[1] ?? [],
        ]);

    }
}
