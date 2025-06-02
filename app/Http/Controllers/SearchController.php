<?php

namespace App\Http\Controllers;

use App\Book;
use App\DBHandler\LikeHandler;
use App\Metadata;
use App\Models\Directory;
use App\Models\File;
use App\Models\MetaType;
use App\Models\MetaValue;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SearchController extends Controller
{

    public function search(){

        return view("pages.search")->with([
            "types" => Metadata::distinct('key')->orderBy("key")->get()
        ]);

    }

    public function searchPOST(Request $request){

        $type = $request->input("type");
        $searchString = $request->input("keyword");

        if($type == "Filename"){

            $results = Book::where("name", 'ILIKE', "%$searchString%")->orderBy("name")->get();
            $metadata = [];

        }elseif($type == "Directory"){

            $results = Book::where("path", 'ILIKE', "%$searchString%")->orderBy("path")->get();
            $metadata = [];

        }else{

            $metadata = Metadata::where("key", "=", $type)->where("value", 'ILIKE', "%$searchString%")->get();
            $results = Book::whereIn("id", $metadata->pluck("book_id"))->orderBy("name")->get();

        }

        return view("pages.searchResults")->with([
            "results" => $results,
            "metadata" => $metadata,
            "type" => $type,
        ]);

    }

}
