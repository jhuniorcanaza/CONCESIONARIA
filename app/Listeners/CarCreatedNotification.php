<?php

namespace App\Listeners;

use App\Events\CarCreated;
use App\Mail\CarCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class CarCreatedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CarCreated $event): void
    {
        $car = $event->car;
        Mail::to(config('mail.from.address'))->send(new CarCreatedMail($car));
    }
}
