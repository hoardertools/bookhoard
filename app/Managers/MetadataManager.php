<?php

namespace App\Managers;

use App\Book;
use App\Http\Functions\SetMetaDataClass;
use App\Image;
use App\Metadata;
use App\Standards\MetadataClass;
use Kiwilan\Audio\Audio;

class MetadataManager
{
    private Book $book;
    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function setDbMetaData()
    {

        $returnObject = new \stdClass();

        $meta = (array) SetMetaDataClass::getFileMetaData($this->book);


        $existingMetadata = $this->book->metadata()->get(["book_id", "key"])->groupBy('key');
        $returnObject->metaData[] = [];
        foreach($meta as $key => $value) {
            if (strlen($value) > 0) {

                if (!isset($existingMetadata[$this->book->id][$key])) {
                    $metadata = new Metadata();
                    $metadata->book_id = $this->book->id;
                    $metadata->directory_id = $this->book->directory_id;
                    $metadata->library_id = $this->book->library_id;
                    $metadata->key = $key;
                    $metadata->value = $value;
                    $returnObject->metaData[] = $metadata;
                    unset($metadata);

                }
            }
        }

        $this->book->metadata_set = true;

            $returnObject->book = $this->book;

            return $returnObject;


    }


}
