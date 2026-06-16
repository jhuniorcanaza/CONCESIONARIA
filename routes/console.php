<?php

use App\Tasks\ClearGuestsData;
use App\Tasks\ClearTempFolder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(ClearTempFolder::class)->hourly();
Schedule::call(ClearGuestsData::class)->hourly();