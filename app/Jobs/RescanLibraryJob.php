<?php

namespace App\Jobs;

use App\Library;
use App\Log;
use App\Managers\LibraryManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RescanLibraryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Library $library)
    {
    }

    public function handle(): void
    {
        $libraryManager = new LibraryManager($this->library);
        Log::log("Rescanning Library: " . $this->library->name, "Library Refresh", "info");
        $libraryManager->rescanLibrary();
        unset($libraryManager);


    }
}
