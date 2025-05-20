<?php
namespace App\Http\Controllers;


use App\Download;
use App\Book;

class MainController extends Controller {
    public function home() {
        return view('pages/home');
    }

    public function permissionDenied() {
        return view('pages/permission-denied');
    }

    public function downloadQueue()
    {
        return view('pages.download-queue')->with([
            'downloadQueue' => Download::orderBy("created_at", "ASC")->get()
        ]);
    }
}