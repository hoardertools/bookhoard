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

            "directories" => \App\Directory::where("directory_id", "=", $directory)->where("library_id", $library->id)->orderBy("path")->paginate(24)->appends(request()->query()),

        ]);
    }



    public function updatePodcast(Request $request, $slug, $podcastId){

        $request->validate([
            "name" => "required"
        ]);

        $podcast = Podcast::where("id", "=", $podcastId)->first();

        $podcast->update([
            "name" => $request->input("name"),
            "description" => $request->input("description"),
            "publisher" => $request->input("publisher"),
            "rssUrl" => $request->input("rss")
        ]);

        if($request->hasFile("cover")){
            if($podcast->image_id > 0){
                $image = $podcast->image()->first();
                $image->update([
                    "base64" => base64_encode(file_get_contents($request->file("cover")->getRealPath()))
                ]);
            }else{
                $image = new Image();
                $image->base64 = base64_encode(file_get_contents($request->file("cover")->getRealPath()));
                $image->type = "Podcast";
                $image->save();
                $podcast->image_id = $image->id;
                $podcast->save();
            }
        }

        return redirect("/library/" . $slug . "/podcast/" . $podcastId);

    }

    public function refreshPodcast($slug, $podcastId){
        $podcast = Podcast::where("id", "=", $podcastId)->first();
        Log::log("Refreshing podcast: " . $podcast->name, "Podcast Refresh", "info");
        RefreshPodcastJob::dispatch($podcast);
        return redirect("/library/" . $slug . "/podcast/" . $podcastId)->with(["status" => "The podcast is being refreshed."]);
    }

    public function getRssFeedUrl($slug, $podcastId, $uuid = null)
    {
        return \Response::redirectTo($this->getRssUrl($slug, $podcastId, $uuid));
    }

    public function getRssUrl($slug, $podcastId, $uuid = null)
    {
        $podcast = Podcast::where('id', $podcastId)->first();
        $library = Library::where('slug', $slug)->first();

        if(!$podcast || !$library) {
            //Return 404
            return response('Podcast not found.', 404);
        }

        if(is_null($podcast->rss_access_key)){
            $podcast->rss_access_key = urlencode(Str::random(32));
            $podcast->save();
        }

        return "/rss/" . $podcast->id . "/" . $podcast->rss_access_key;
    }


    public function getRssFeed($id, $key)
    {
        $podcast = Podcast::where('id', $id)->where('rss_access_key', $key)->first();

        if(!$podcast) {
            //Return 404
            return response('Podcast not found.', 404);
        }

        $feed = new Feed(
            $podcast->name,
            "",
            $podcast->description,
        );
        $feed->setUpdatePeriod('daily');
        $feed->setUpdateFrequency(1);
        $feed->setLanguage('en-US');
        $feed->setItunesCategories('General');
        $feed->setItunesImage(config("app.url") . "/getRssPodcastCover/" . $podcast->id . "/" . $key  );
        $feed->setImage(config("app.url") . "/getRssPodcastCover/" . $podcast->id . "/" . $key  ); // min 1400 x 1400 pixel

        foreach($podcast->episodes()->orderBy("published_at", "DESC")->get() as $episode) {

            $feedItem = new Item();
            $feedItem->setTitle($episode->getEpisodeName());
            $feedItem->setDescription($episode->description);
            $feedItem->setPublishDate(new \DateTime($episode->published_at));
            $feedItem->setMediaSize($episode->filesize);
            $feedItem->setMediaDuration($episode->duration);
            $feedItem->setItunesImage(config("app.url") . "/getRssPodcastCover/" . $podcast->id . "/" . $key  );
            $feedItem->setMediaMimeType('audio/mp3');
            $feedItem->setMediaUrl(config("app.url") . '/downloadRssFile/' . $episode->id . '/' . $podcast->rss_access_key . '/' . str_replace(" ", "_", basename($episode->path)));
            $feed->addItem($feedItem);

        }

        return \Response::make($feed, 200, [
            'Content-Type' => 'application/xml',
        ]);

    }

    public function createPodcast($slug){

        return view("pages.createpodcast")->with([
            "library" => Library::where("slug", $slug)->first()
        ]);

    }

    public function createPodcastPost(Request $request, $slug){

        if($request->type == "rss"){
            $request->validate([
                "rss" => "required"
            ]);
        }elseif($request->type == "opml"){
            $request->validate([
                "opml" => "required"
            ]);
        }

        $library = Library::where("slug", $slug)->first();
        if($request->type == "rss") {
            $podcast = $this->addNewPodcast($request->rss, $library);
        }

        if($request->type == "opml") {
            $opml = simplexml_load_file($request->file("opml")->getRealPath());
            foreach($opml->body->outline as $outline){
                AddNewPodcastJob::dispatch($outline->attributes()->xmlUrl, $library);
            }
        }

        return redirect("/library/" . $slug)->with(["status" => "Podcast(s) added. Refreshing the library."]);

    }

    public function addNewPodcast($rss, $library){


        $directory = $library->directories()->first();

        try{
            if(Setting::where("key", "CustomUserAgent")->where("value", "!=", "")->exists()){
                $opts = [
                    "http" => [
                        "header" => "User-Agent: " . Setting::where("key", "CustomUserAgent")->first()->value
                    ]
                ];
                $context = stream_context_create($opts);
                $xml = file_get_contents($rss, false, $context);
            }else{
                $xml = file_get_contents($rss);
            }

            $poddle = Poddle::fromXml($xml);


            $description = $poddle->xmlReader->value("rss.channel.description")->first();
            $name = $poddle->xmlReader->value("rss.channel.title")->first();
        }catch (\TypeError $e){
            \Log::error("Failed to add podcast: " . $rss . PHP_EOL . "Error: " . $e->getMessage());
            exit(-1);
        }



        $podcast = new Podcast();
        $podcast->name = preg_replace('/^\s+|\s+$/u', '', $name);
        $podcast->rssUrl = $rss;
        $podcast->library_id = $library->id;
        $podcast->last_scanned_at = now();
        $podcast->last_rss_scanned_at = now();
        $podcast->path = $directory->path . "/" . preg_replace('/^\s+|\s+$/u', '', $name);
        $podcast->directory_id = $directory->first()->id;
        $podcast->total_playtime = 0;
        $podcast->total_episodes = 0;
        $podcast->total_size = 0;
        $podcast->description = $description;
        $podcast->latest_addition_at = now();

        if(Podcast::where("library_id", "=", $library->id)->where("rssUrl", "=", $rss)->exists()){
            return true;
        }

        if(empty($podcast->name)){

            \Log::error("Failed to add podcast. No name found: " . $rss . PHP_EOL);
            exit(-1);
        }
        $podcast->save();
        Log::log("Added podcast: " . $podcast->name,  "Podcast Added", "info");
        RefreshRssJob::dispatch($podcast);
        Log::log("Refreshing podcast: " . $podcast->name,  "Podcast Refresh", "info");
        try{
            $channel = $poddle->getChannel();
            if($channel->image) {
                $image = new Image();
                $image->base64 = base64_encode(file_get_contents($channel->image));
                $image->type = "Podcast";
                $image->directory_id = $directory->id;
                $image->library_id = $library->id;
                $image->podcast_id = $podcast->id;
                $image->save();
                $podcast->image_id = $image->id;
                $podcast->save();
            }
        }catch (\TypeError|RuntimeException $e){
            return $podcast;
        }




        return $podcast;

    }
}
