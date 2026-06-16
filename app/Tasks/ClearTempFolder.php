<?php

namespace App\Tasks;

use Illuminate\Support\Facades\Storage;

class ClearTempFolder
{
    /**
     * Execute the job.
     */
    // public function handle(): void
    public function __invoke(): void
    {
        $files = Storage::disk('public')->files('temp');

        foreach ($files as $file) {

            $lastModified = Storage::disk('public')->lastModified($file);

            if ($lastModified < now()->subHours(1)->timestamp) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
