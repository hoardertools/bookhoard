<?php

namespace App\Http\Controllers;

use App\Book;
use App\DBHandler\LikeHandler;
use App\Image;
use App\Jobs\AddNewPodcastJob;
use App\Jobs\RefreshPodcastJob;
use App\Jobs\RefreshRssJob;
use App\Library;
use App\Log;
use App\Models\Directory;
use App\Models\File;
use App\Podcast;
use App\Setting;
use GoncziAkos\Podcast\Feed;
use GoncziAkos\Podcast\Item;
use http\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhanAn\Poddle\Poddle;
use Saloon\XmlWrangler\Exceptions\QueryAlreadyReadException;
use Saloon\XmlWrangler\Exceptions\XmlReaderException;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use VeeWee\Xml\Exception\RuntimeException;

class LibraryController extends Controller
{
    public function showLibrary(Request $request, $slug, $directory = null){


        $library = Library::where("slug", $slug)->orderBy("name", "ASC")->first();

        return view('pages.library', [
            'library' => $library,
            "books" => Book::where("directory_id", "=", $directory)->orderBy("name")->get(),

            "directories" => \App\Directory::where("directory_id", "=", $directory)->where("library_id", $library->id)->get(),

        ]);

    }


    public function showBook($slug, \App\Directory $directory, Book $book){

        return view('pages.book', [
            'book' => $book,
            'directory' => $directory,
           ]);

    }

}
