<?php

namespace App\Managers;

use App\Jobs\SetInitialMetaDataJob;
use App\Library;

class LibraryManager
{
    private Library $library;
    public function __construct(Library $library)
    {
        $this->library = $library;
    }

    public function rescanLibrary()
    {

        foreach($this->library->directories as $directory) {

            $directoryManager = new DirectoryManager($directory);
            $directoryManager->rescanDirectory();
            unset($directoryManager);

        }

        SetInitialMetaDataJob::dispatch($this->library);

        return true;
    }

    public function rescanSingleDirectory($directory)
    {

        $directoryManager = new DirectoryManager($directory);
        $directoryManager->rescanDirectory();
        unset($directoryManager);

        return true;
    }
}
