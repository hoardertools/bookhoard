<?php

namespace App\Jobs;

use App\Directory;
use App\Managers\DirectoryManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshDirectoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $directory;

    public function __construct(Directory $directory)
    {
        $this->directory = $directory;
    }

    public function handle()
    {

        $directoryManager = new DirectoryManager($this->directory);
        $directoryManager->rescanDirectory();
        unset($directoryManager);
    }
}
