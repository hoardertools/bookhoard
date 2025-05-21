<?php

namespace App\Managers;

use App\Book;
use App\Jobs\SetInitialMetaDataJob;
use App\Log;
use App\Metadata;

class BookManager
{
    private Book $episode;
    public function __construct(Book $episode)
    {
        $this->episode = $episode;
    }

    public static function getSupportedFileFormats(){
        return [
            "epub",
            "pdf",
            "cbr",
            "cbz",
            "mobi"
        ];
    }

    public static function addNewBook($directory, $file, $returnDontSave = false){

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        if(in_array(strtolower($fileExtension), self::getSupportedFileFormats())){

            if(Book::where("path", "=",$file)->exists()){
                return true;
            }

            $book = new Book();
            $book->name = pathinfo($file, PATHINFO_FILENAME);
            $book->path = $file;
            $book->directory_id = $directory->id;
            $book->library_id = $directory->library_id;
            $book->metadata_set = false;
            $book->created_at = now();
            $book->updated_at = now();

            if($returnDontSave){
                return $book;
            }else{
                $book->save();
                Log::log("Book added: " . $book->name,  "Book", "info");
                SetInitialMetaDataJob::dispatch($directory->library);
            }

        }

        return true;

    }

    public function setMetaData($returnDontSave = false){

        $metadata = new MetadataManager($this->episode);
        $metadata->setDbMetaData();
        unset($metadata);

        $this->episode->metadata_set = true;
        $this->episode->save();

        return true;
    }

}
