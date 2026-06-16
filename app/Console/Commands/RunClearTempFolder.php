<?php

namespace App\Console\Commands;

use App\Tasks\ClearTempFolder;
use Illuminate\Console\Command;

class RunClearTempFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:clear-temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the images temp folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new ClearTempFolder())();
        
        $this->info('Temp folder cleared');
    }
}
