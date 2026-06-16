<?php

namespace App\Tasks;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ClearGuestsData
{
    /**
     * Execute the job.
     */
    // public function handle(): void
    public function __invoke(): void
    {
        User::where('id','>',22)->get()
            ->each(function ($user) {
                $user->cars->each->delete();            
                $user->delete();
            });        
    }
}
