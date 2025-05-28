<?php

namespace App\Jobs;

use App\Book;
use App\Image;
use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kiwilan\Ebook\Ebook;

class SetThumbJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {

        foreach(Book::where("has_image", "=", false)->where("has_image_been_tried", "=", false)->take(100)->get() as $book){

            if(str_ends_with(strtolower($book->path), ".cbr")){
                $book->has_image_been_tried = true;
                $book->save();

                $path = escapeshellarg($book->path);
                exec("unrar-free --list " . $path, $files);
                sort($files);
                echo "Processing CBR file: " . $book->path . "\n";
                foreach($files as $fil){
                    echo "Found file: " . $fil . "\n";
                    if(str_ends_with(strtolower($fil), ".png") || str_ends_with(strtolower($fil), ".jpg") || str_ends_with(strtolower($fil), ".jpeg")
                        || str_ends_with(strtolower($fil), ".bmp")  || str_ends_with(strtolower($fil), ".gif")){
                        $fileToExport = trim($fil);
                        break;
                    }
                }

                if(isset($fileToExport)){
                    echo "File to export: " . $fileToExport . "\n";
                    $escapedFileToExport = escapeshellarg($fileToExport);
                    exec("unrar-free --extract $path  $escapedFileToExport /tmp", $output, $returnCode);

                    if(file_exists("/tmp/" . $fileToExport)) {
                        $book->has_image = true;

                        $image = new Image();
                        $image->base64 = base64_encode(file_get_contents("/tmp/" . $fileToExport));
                        $image->book_id = $book->id;
                        $image->library_id = $book->library_id;
                        $image->type = "Book";
                        $image->directory_id = $book->directory_id;
                        $image->save();
                        $book->image_id = $image->id;

                        unlink("/tmp/" . $fileToExport);
                    }
                }

            }else{
                try{
                    $epubParser = Ebook::read($book->path);
                }catch (\ErrorException $e){
                    \Log::error("Error reading file: " . $book->path . " - " . $e->getMessage());
                    $book->has_image_been_tried = true;
                    $book->save();
                    continue;
                }catch (\Exception $e){
                    \Log::error("Error reading file: " . $book->path . " - " . $e->getMessage());
                    $book->has_image_been_tried = true;
                    $book->save();
                    continue;
                }
                $book->has_image_been_tried = true;
                if($epubParser->hasCover()) {
                    $book->has_image = true;

                    $image = new Image();
                    $cover =  $epubParser->getCover();

                    $image->base64 = $cover->getContents(true);
                    $image->book_id = $book->id;
                    $image->library_id = $book->library_id;
                    $image->type = "Book";
                    $image->directory_id = $book->directory_id;
                    $image->save();
                    $book->image_id = $image->id;



                    unset($cover);
                    unset($epubParser);
                }
            }

            $book->save();

        }

        if(Book::where("has_image", "=", false)->where("has_image_been_tried", "=", false)->exists()) {
                SetThumbJob::dispatch()->onQueue('image');
            return;
        }

    }
}
