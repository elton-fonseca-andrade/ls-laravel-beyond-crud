<?php

namespace App\Providers;

use Domain\Inquiries\Listeners\InquirySubscriber;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::subscribe(InquirySubscriber::class);
    }
}
