<?php

namespace App\Jobs;

use App\Book;
use App\Managers\BookManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddBookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $directory;
    private $file;

    public function __construct($directory, $file)
    {
        $this->directory = $directory;
        $this->file = $file;
    }

    public function handle()
    {
        BookManager::addNewBook($this->directory, $this->file);
    }
}
