<?php

namespace App\Managers;

use App\Directory;
use App\Book;
use App\Episode;
use App\Image;
use App\Jobs\AddBookJob;
use App\Jobs\RefreshDirectoryJob;
use App\Jobs\RefreshPodcastJob;
use App\Jobs\SetInitialMetaDataJob;
use App\Log;
use App\Metadata;
use App\Podcast;
use function Psl\Type\instance_of;

class DirectoryManager
{
    private Directory $directory;
    public function __construct(Directory $directory)
    {
        $this->directory = $directory;
    }

    public function rescanDirectory()
    {

        $subdirectories = scandir($this->directory->path);
        $newBooks = [];
        foreach($subdirectories as $subdirectory) {

            if($subdirectory == '.' || $subdirectory == '..') {
                continue;
            }

            $subdirectoryPath = $this->directory->path . DIRECTORY_SEPARATOR . $subdirectory;

            if(is_dir($subdirectoryPath)) {

                if(Directory::where("path", "=", $subdirectoryPath)->count() == 0) {
                    $newDirectory = new Directory();
                    $newDirectory->path = $subdirectoryPath;
                    $newDirectory->directory_id = $this->directory->id;
                    $newDirectory->library_id = $this->directory->library_id;
                    $newDirectory->regex = $this->directory->regex;
                    $newDirectory->save();
                    Log::log("Directory added: " . $newDirectory->path, "Directory Scan", "info");

                    RefreshDirectoryJob::dispatch($newDirectory);
                }else {
                    $existingDirectory = Directory::where("path", "=", $subdirectoryPath)->get();
                    foreach ($existingDirectory as $dir) {
                        RefreshDirectoryJob::dispatch($dir);
                    }
                }

            }if(is_file($subdirectoryPath)) {

                $newBook = BookManager::addNewBook($this->directory, $subdirectoryPath, true);

                if($newBook instanceof Book){
                    $newBooks[] = $newBook->toArray();
                }

            }

        }
        if (!empty($newBooks)) {
            $chuckedBooks = array_chunk($newBooks, 1000, true);
            foreach ($chuckedBooks as $newBooksChuck) {
                Book::insert($newBooksChuck);
            }

        }
        SetInitialMetaDataJob::dispatch($this->directory->library)->onQueue('metadata');
        return true;


    }

    public function removeDirectory()
    {

        Image::where("directory_id", "=", $this->directory->id)->delete();
        Metadata::where("directory_id", "=", $this->directory->id)->delete();
        Book::where("directory_id", "=", $this->directory->id)->delete();
        $this->directory->removeAllChildren();
        $this->directory->delete();

    }
}
