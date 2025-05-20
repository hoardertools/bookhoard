<?php

namespace App\Jobs;

use App\Directory;
use App\Library;
use App\Managers\DirectoryManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveLibraryDirectoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(private readonly Directory $directory)
    {

    }

    public function handle(): void
    {

        $directoryManager = new DirectoryManager($this->directory);
        $directoryManager->removeDirectory();
        unset($directoryManager);

    }
}
