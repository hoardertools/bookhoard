<?php

namespace App\Http\Functions;


use App\Book;

use Kiwilan\Ebook\Ebook;
use function Symfony\Component\String\s;

class SetMetaDataClass
{

    public static function getFileMetaData(Book $book){

        $meta = [];


            try{
                $epubParser = Ebook::read($book->path);
                $meta["series"] = $epubParser->getSeries();
                $meta["issue"] = $epubParser->getVolume();
                $meta["title"] = $epubParser->getTitle();
                $meta["author"] = $epubParser->getAuthors();
                $meta["description"] = $epubParser->getDescription();
                $meta["language"] = $epubParser->getLanguage();
                $meta["publisher"] = $epubParser->getPublisher();
                $meta["identifier"] = $epubParser->getIdentifiers();
                $meta["published"] = $epubParser->getPublishDate();


                if(is_array( $meta["author"])){
                    $meta["author"] = implode(", ", $meta["author"]);
                }
                if(is_array( $meta["identifier"])){
                    $meta["identifier"] = implode(", ", $meta["identifier"]);
                }
                //if published is a DateTime, parse the date
                if(is_a($meta["published"], \DateTime::class)){
                    $meta["published"] = $meta["published"]->format("Y-m-d");
                }

                //Check if the book's directory has a regex, if so, parse it and use any groups as metadata
                $regex = $book->directory->regex;
                if($regex){
                    preg_match($regex, basename($book->path), $matches);
                    if(count($matches) > 0){
                        foreach($matches as $key => $value){
                            if($key == 0){
                                continue;
                            }
                            if(is_int($key)){
                                continue;
                            }
                            $meta[$key] = $value;
                        }
                    }
                }
            }catch (\Exception $e){

            }

        return $meta;

    }

}
