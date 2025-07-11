<?php

//General routes

Route::get('/login', 'LoginController@login')->name('login');
Route::post('/login', 'LoginController@authenticate');
Route::get('/logout', 'LoginController@logout')->name('logout');
Route::get('/permission-denied', 'MainController@permissionDenied')->name('permission-denied');

Route::get('/rss/{id}/{key}', 'LibraryController@getRssFeed');


Route::get('/downloadRssFile/{id}/{key}/{filename}', function ($id, $key) {

    $episode = \App\Book::where('id', $id)->first();
    $podcast = \App\Podcast::findOrFail($episode->podcast_id);
    if($key == $podcast->rss_access_key) {
        return Response::download($episode->path);
    }else{
            return response('Unauthorized.', 401);
        }

});

//Read permissions
Route::middleware(['verifyReadPermissions'])->group(function () {
    Route::get('/api/books/{book}/details', function (\App\Book $book) {
        return response()->json([
            'series' => $book->series,
            'issue' => $book->issue,
            'title' => $book->title,
        ]);
    });
    Route::get('/', 'MainController@home')->name('home');


    //Redirect /book/{id} to /library/{slug}/directory/{directoryId}/book/{book}
    Route::get('/book/{id}', function ($id) {
        $book = \App\Book::findOrFail($id);
        $directory = \App\Directory::findOrFail($book->directory_id);
        $library = \App\Library::findOrFail($directory->library_id);
        return redirect("/library/{$library->slug}/directory/{$directory->id}/book/{$book->id}");
    })->name('redirectSingleFile');
    Route::get('/search', 'SearchController@search')->name('search');

    Route::post('/search', 'SearchController@searchPOST')->name('searchPOST');

    Route::get('/library/{slug}', 'LibraryController@showLibrary');
    Route::get('/authors/', 'LibraryController@authors');
    Route::get('/authors/{id}', 'LibraryController@author');
    Route::get('/library/{slug}/directory/{directoryId}', 'LibraryController@showLibrary');
    Route::get('/library/{slug}/directory/{directory}/book/{book}', 'LibraryController@showBook');

    // /getComic/id to download the file to the browser
    Route::get("/getComic/{id}", function ($id) {

        $book = \App\Book::findOrFail($id);


        return response()->download( $book->path);

    })->name('getComic');

    // /getComic/id to download the file to the browser
    Route::get("/getBook/{id}", function ($id) {

        $book = \App\Book::findOrFail($id);


        return response()->download( $book->path);

    })->name('getBook');

    Route::get("/getBook/{id}/customNamingScheme", function ($id) {

        $book = \App\Book::findOrFail($id);

//Download as filename using Metadata author - title
        $author = $book->metadata()->where('key', 'author')->first();
        $title = $book->metadata()->where('key', 'title')->first();
        $filename = $author ? $author->value : 'Unknown Author';
        $filename .= ' - ' . ($title ? $title->value : 'Unknown Title') . '.' . pathinfo($book->path, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        // Return the file as a download response with the custom filename
        return response()->download($book->path, $filename, $headers);



    })->name('getBook');


    Route::get('/profile', 'ProfileController@profile')->name('profile');
    Route::post('/profile', 'ProfileController@updateProfile');

    Route::get('/logCenter', 'LogController@view')->name('logCenter');

    Route::get('/downloadQueue', 'MainController@downloadQueue')->name('downloadQueue');


    Route::get('/getPodcastImage/{id}', function (Request $request, $id) {

        $podcast = \App\Podcast::where("id", "=", $id)->first();
        try{
            return "data:image/png;base64, " . $podcast->image()->first()->base64;
        }catch (\Exception $e){
            unset($e);
            return "/assets/img/Podcast_Placeholder_orig.webp";
        }
    });

    Route::get('/getPodcastCover/{id}', function (Request $request, $id) {

        $podcast = \App\Podcast::where("id", "=", $id)->first();
        try{
            return Response::make(base64_decode($podcast->image()->first()->base64), 200, [
                'Content-Type' => 'image/png', // Adjust the content type if necessary
                'Content-Disposition' => 'inline', // Ensure the browser renders it as an image
            ]);

        }catch (\Exception $e){
            unset($e);
            $placeholderPath = public_path('/assets/img/Podcast_Placeholder_orig.webp'); // Adjust the path if needed
            return Response::file($placeholderPath);
        }
    });

    Route::get('/library/{slug}/podcast/{podcastId}/getRssFeed', 'LibraryController@getRssFeedUrl');

});

//Edit permissions
Route::middleware(['verifyWritePermissions'])->group(function () {

    Route::get('/manage/settings/general', 'ManageSettingsController@settingsGeneral')->name('settingsGeneral');
    Route::post('/manage/settings/general', 'ManageSettingsController@updateGeneralSettings');

    Route::get('/manage/libraries', 'ManageLibrariesController@index')->name('manageLibraries');
    Route::get('/manage/libraries/create', 'ManageLibrariesController@create')->name('manageLibrariesCreate');
    Route::post('/manage/libraries', 'ManageLibrariesController@store')->name('manageLibrariesStore');

    Route::get('/manage/libraries/{slug}', 'ManageLibrariesController@show');
    Route::get('/manage/libraries/{slug}/rescan', 'ManageLibrariesController@rescan');
    Route::post('/manage/libraries/{slug}/updateName', 'ManageLibrariesController@updateName');
    Route::get('/manage/libraries/{slug}/addDirectory', 'ManageLibrariesController@addDirectory');
    Route::post('/manage/libraries/{slug}/addDirectory', 'ManageLibrariesController@addDirectoryPOST');
    Route::get('/manage/libraries/{slug}/addDirectory2', 'ManageLibrariesController@addDirectory2');
    Route::post('/manage/libraries/{slug}/addDirectory2', 'ManageLibrariesController@addDirectory2POST');
    Route::get('/manage/libraries/{slug}/removeDirectoryPath/{path}', 'ManageLibrariesController@removeDirectoryPath');
    Route::get('/manage/users/{user}/delete', 'ManageUsersController@destroy');
        Route::resource('/manage/users', 'ManageUsersController');
    Route::get('/library/{slug}/createPodcast', 'LibraryController@createPodcast');
    Route::post('/library/{slug}/createPodcast', 'LibraryController@createPodcastPost');

    Route::post('/library/{slug}/podcast/{podcastId}', 'LibraryController@updatePodcast');
    Route::get('/library/{slug}/podcast/{podcastId}/refreshPodcast', 'LibraryController@refreshPodcast');

    Route::view('/regex', 'regex-form');
    Route::post('/regex/parse', [\App\Http\Controllers\RegexController::class, 'parse'])->name('regex.parse');

    Route::get('/directoryBrowser/{base64path}', function (Request $request, $base64path) {

        $path = base64_decode($base64path);

        $dirs = glob(str_replace("..", "", $path) . '/*', GLOB_ONLYDIR);

        return json_encode($dirs);
    });

});

//Download permissions
Route::middleware(['verifyDownloadPermissions'])->group(function () {

    Route::get('/downloadFile/{id}', function ($id) {

        return Response::download(\App\Book::findOrFail($id)->path);

    });

});