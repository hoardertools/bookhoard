<?php

namespace App\Jobs;

use App\Book;
use App\Library;
use App\Log;
use App\Managers\BookManager;
use App\Managers\MetadataManager;
use App\Managers\PodcastManager;
use App\Metadata;
use App\Podcast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetInitialMetaDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Library $library)
    {
    }

    public function handle(): void
    {

        $metaData = [];


        foreach($this->library->books()->where("metadata_set", "=", false)->take(1000)->get() as $book) {

            \Illuminate\Support\Facades\Log::info("Setting metadata for book: " . $book->id);
            $metadataManager = new MetadataManager($book);
            \Illuminate\Support\Facades\Log::info("Getting metadata for book: " . $book->id);
            $metaObject = $metadataManager->setDbMetaData();

            \Illuminate\Support\Facades\Log::info("Setting metadata for book: " . $book->id . " with metadata: " . json_encode($metaObject->metaData));
            foreach($metaObject->metaData as $metadata) {

                if(is_a($metadata, Metadata::class)){
                    $metaData[] = $metadata->toArray();
                }

            }
            $book->metadata_set = true;
            $book->save();
            \Illuminate\Support\Facades\Log::info("Metadata set for book: " . $book->id);
        }

        $chuckedMetadata = array_chunk($metaData,10000,true);

        foreach ($chuckedMetadata as $chuck)
        {
            Metadata::insert($chuck);
        }


        if($this->library->books()->where("metadata_set", "=", false)->exists()) {
            SetInitialMetaDataJob::dispatch($this->library)->onQueue('metadata');
            return;
        }

        Log::log("Finished setting metadata for library: " . $this->library->id, "Set Metadata", "info");

        SetThumbJob::dispatch()->onQueue('image');

    }
}
