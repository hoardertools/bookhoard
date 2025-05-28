<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScheduleThumbCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:schedule-thumb-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting thumb check...');

        // Dispatch the SetThumbJob to the queue
        \App\Jobs\SetThumbJob::dispatch();

        $this->info('Thumb check scheduled successfully.');
    }

}
